/**
 * product.js — Sélecteur de variantes + galerie + panier
 * Page détail produit client
 */

(function () {

  // ── Galerie ────────────────────────────────────────────────────
  window.setMainImage = function (src) {
    const img = document.getElementById('mainImage');
    if (img) img.src = src;
  };

  // ── Quantité ───────────────────────────────────────────────────
  window.changeQty = function (delta) {
    const input = document.getElementById('qtyInput');
    if (!input) return;
    const max = parseInt(input.max, 10) || 999;
    let val = parseInt(input.value, 10) + delta;
    if (val < 1)   val = 1;
    if (val > max) val = max;
    input.value = val;
  };

  // ── Variantes ──────────────────────────────────────────────────
  const selectorContainer = document.getElementById('variantSelectors');
  if (!selectorContainer) return;

  // variantMap keyed by sorted attribute_value ids joined with "-"
  const variantMap = JSON.parse(selectorContainer.dataset.variantMap || '{}');

  // { attrSlug: selectedValueId }
  const selected = {};

  // All attribute groups present on the page
  const attrGroups = selectorContainer.querySelectorAll('[data-attr]');
  const attrNames  = [...new Set([...attrGroups].map(g => g.dataset.attr))];

  function getSelectedKey() {
    const ids = attrNames.map(a => selected[a]).filter(Boolean).sort().join('-');
    return ids;
  }

  function findVariant() {
    if (attrNames.length === 0) return null;
    // Only resolve when all attrs are selected
    if (attrNames.some(a => !selected[a])) return null;
    const key = getSelectedKey();
    return variantMap[key] ?? null;
  }

  function updateUI() {
    const variant = findVariant();
    const priceEl   = document.getElementById('priceDisplay');
    const prefixEl  = document.getElementById('pricePrefix');
    const badgeEl   = document.getElementById('stockBadge');
    const addBtn    = document.getElementById('addToCartBtn');
    const hiddenId  = document.getElementById('selectedVariantId');
    const qtyInput  = document.getElementById('qtyInput');

    if (variant) {
      // Price
      priceEl.textContent = new Intl.NumberFormat('fr-DZ').format(variant.price) + ' DA';
      prefixEl.textContent = '';

      // Stock badge
      if (variant.stock > 0) {
        badgeEl.innerHTML = `<span class="w-2 h-2 rounded-full bg-green-500"></span>En stock (${variant.stock})`;
        badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-green-50 text-green-700';
        addBtn.disabled   = false;
      } else {
        badgeEl.innerHTML = `<span class="w-2 h-2 rounded-full bg-red-400"></span>Rupture de stock`;
        badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-red-50 text-red-600';
        addBtn.disabled   = true;
      }

      // Qty max
      qtyInput.max = variant.stock;
      if (parseInt(qtyInput.value) > variant.stock) qtyInput.value = variant.stock || 1;

      // Hidden input
      hiddenId.value = variant.id;
    } else {
      // Reset to "pick a variant" state
      badgeEl.innerHTML = `<span class="w-2 h-2 rounded-full bg-[#c4c6d1]"></span>Sélectionnez une variante`;
      badgeEl.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-semibold bg-[#f2f4f6] text-[#747780]';
      addBtn.disabled   = true;
      hiddenId.value    = '';
      qtyInput.max      = 999;
    }
  }

  function updateButtonStates() {
    const allBtns = selectorContainer.querySelectorAll('.variant-btn');
    allBtns.forEach(btn => {
      const attr  = btn.dataset.attr;
      const valId = parseInt(btn.dataset.valueId, 10);

      // Simulate selecting this value together with other selected attrs
      const simulated = { ...selected, [attr]: valId };
      const simulatedKey = attrNames.map(a => simulated[a]).filter(Boolean).sort().join('-');

      // Check if any variant with this partial combo has stock
      const available = Object.entries(variantMap).some(([key]) =>
        simulatedKey.split('-').every(id => key.split('-').includes(id))
      );

      const isSelected = selected[attr] === valId;

      btn.className = btn.className
        .replace(/border-\S+/g, '')
        .replace(/bg-\S+/g, '')
        .replace(/text-\[#002352\]/g, '')
        .replace(/text-\[#5d5f5f\]/g, '')
        .replace(/line-through/g, '')
        .replace(/opacity-40/g, '')
        .trim();

      if (isSelected) {
        btn.className += ' border-[#002352] bg-[#002352] text-white';
      } else if (!available) {
        btn.className += ' border-[#edeef0] text-[#c4c6d1] line-through opacity-40 cursor-not-allowed';
      } else {
        btn.className += ' border-[#edeef0] text-[#5d5f5f] hover:border-[#002352]';
      }
    });
  }

  // Delegate click to all variant buttons
  selectorContainer.addEventListener('click', function (e) {
    const btn = e.target.closest('.variant-btn');
    if (!btn) return;

    const attr  = btn.dataset.attr;
    const valId = parseInt(btn.dataset.valueId, 10);

    // Toggle: clicking the already-selected value deselects it
    if (selected[attr] === valId) {
      delete selected[attr];

      // Update displayed label
      const labelEl = document.getElementById('label-' + attr);
      if (labelEl) labelEl.textContent = '';
    } else {
      selected[attr] = valId;

      // Update displayed label
      const labelEl = document.getElementById('label-' + attr);
      if (labelEl) labelEl.textContent = btn.textContent.trim();
    }

    updateButtonStates();
    updateUI();
  });

  // Initial render
  updateButtonStates();
  updateUI();

  // ── Panier ────────────────────────────────────────────────────
  window.addToCart = function () {
    const variantId = document.getElementById('selectedVariantId').value;
    const qty       = parseInt(document.getElementById('qtyInput').value, 10);
    if (!variantId) return;

    const btn  = document.getElementById('addToCartBtn');
    const orig = btn.innerHTML;
    btn.disabled  = true;
    btn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/></svg>Ajout en cours...</span>';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    fetch('/panier/ajouter', {
      method  : 'POST',
      headers : {
        'Content-Type'     : 'application/json',
        'X-CSRF-TOKEN'     : csrfToken,
        'X-Requested-With' : 'XMLHttpRequest',
        'Accept'           : 'application/json',
      },
      body: JSON.stringify({ variant_id: parseInt(variantId, 10), quantity: qty }),
    })
    .then(async res => {
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Erreur');

      // Update badge(s)
      const count = data.cart_count ?? 0;
      document.querySelectorAll('[data-cart-badge]').forEach(el => {
        el.textContent = count;
        el.style.display = count > 0 ? '' : 'none';
      });

      // Success state
      btn.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>Ajouté au panier !</span>';
      btn.classList.add('bg-emerald-600');
      btn.classList.remove('bg-[#002352]', 'hover:bg-[#18396e]');

      setTimeout(() => {
        btn.innerHTML = orig;
        btn.disabled  = false;
        btn.classList.remove('bg-emerald-600');
        btn.classList.add('bg-[#002352]', 'hover:bg-[#18396e]');
      }, 2000);
    })
    .catch(err => {
      btn.innerHTML = '<span class="text-red-300">Erreur — réessayez</span>';
      setTimeout(() => {
        btn.innerHTML = orig;
        btn.disabled  = false;
      }, 2000);
    });
  };

  // ── WhatsApp ───────────────────────────────────────────────────
  const waBtn = document.getElementById('whatsappBtn');
  if (waBtn) {
    waBtn.addEventListener('click', function () {
      const phone   = '213700000000'; // TODO: configurable via setting ou env
      const name    = document.querySelector('h1')?.textContent.trim() ?? 'Produit';
      const variant = document.getElementById('selectedVariantId').value;
      const qty     = document.getElementById('qtyInput').value;
      const label   = variant
        ? Object.values(window._selectedLabels || {}).join(' / ')
        : '';
      const msg = encodeURIComponent(
        `Bonjour, je souhaite commander :\n• ${name}${label ? ' (' + label + ')' : ''} × ${qty}`
      );
      window.open(`https://wa.me/${phone}?text=${msg}`, '_blank');
    });
  }

})();
