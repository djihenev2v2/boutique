/**
 * products.js — Admin product pages (index + create/edit form)
 */

// ─────────────────────────────────────────────
// INDEX PAGE — Delete confirm + Toggle active
// ─────────────────────────────────────────────

function initIndexPage() {
    const modal      = document.getElementById('deleteModal');
    const backdrop   = document.getElementById('deleteModalBackdrop');
    const nameEl     = document.getElementById('deleteProductName');
    const cancelBtn  = document.getElementById('deleteCancelBtn');
    const confirmBtn = document.getElementById('deleteConfirmBtn');

    if (!modal) return;

    let pendingForm = null;

    // Open modal on delete button click
    document.querySelectorAll('.product-delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = btn.closest('.product-delete-form');
            pendingForm = form;
            nameEl.textContent = form.dataset.name;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    const closeModal = () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        pendingForm = null;
    };

    cancelBtn?.addEventListener('click', closeModal);
    backdrop?.addEventListener('click', closeModal);

    confirmBtn?.addEventListener('click', () => {
        if (pendingForm) pendingForm.submit();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

    // Toggle active AJAX
    document.querySelectorAll('.product-toggle').forEach(btn => {
        btn.addEventListener('click', async () => {
            const url  = btn.dataset.toggleUrl;
            const csrf = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const res  = await fetch(url, {
                    method: 'PATCH',
                    headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                });
                const data = await res.json();

                const active = Boolean(data.is_active);
                btn.dataset.active = active ? '1' : '0';

                // Update button classes
                btn.className = btn.className
                    .replace(/bg-emerald-100|text-emerald-700|hover:bg-emerald-200|bg-\[#f2f4f6\]|text-\[#747780\]|hover:bg-\[#e7e8ea\]/g, '').trim();

                if (active) {
                    btn.classList.add('bg-emerald-100', 'text-emerald-700', 'hover:bg-emerald-200');
                } else {
                    btn.classList.add('bg-[#f2f4f6]', 'text-[#747780]', 'hover:bg-[#e7e8ea]');
                }

                const dot = btn.querySelector('span');
                if (dot) {
                    dot.className = `w-1.5 h-1.5 rounded-full inline-block ${active ? 'bg-emerald-500' : 'bg-[#c4c6d1]'}`;
                }
                btn.querySelector('span:last-child') && (btn.querySelector('span:last-child').textContent = active ? 'Actif' : 'Inactif');

            } catch (e) {
                console.error('Toggle failed', e);
            }
        });
    });
}

// ─────────────────────────────────────────────
// FORM PAGE — Variants builder + Image preview
// ─────────────────────────────────────────────

function initFormPage() {
    const generateBtn       = document.getElementById('generateVariantsBtn');
    const tableWrapper      = document.getElementById('variantsTableWrapper');
    const tableBody         = document.getElementById('variantsTableBody');
    const attrValueSections = document.getElementById('attributeValueSections');
    const basePriceInput    = document.getElementById('basePriceInput');
    const applyPrice        = document.getElementById('applyPriceAll');
    const applyStock        = document.getElementById('applyStockAll');
    const imageInput        = document.getElementById('imageInput');
    const imagePreviews     = document.getElementById('imagePreviews');
    const dropZone          = document.getElementById('imageDropZone');

    if (!generateBtn && !imageInput) return;

    // ── Attribute state ───────────────────────────────
    // [{ id: number, name: string, values: string[] }]
    let selectedAttributes = [];
    let variantIndex = tableBody?.querySelectorAll('tr.variant-row').length || 0;

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Tag rendering ─────────────────────────────────
    function renderTags(attr) {
        const row = document.getElementById(`tag-row-${attr.id}`);
        if (!row) return;
        row.innerHTML = '';
        attr.values.forEach(val => {
            const tag = document.createElement('span');
            tag.className = 'inline-flex items-center gap-1 text-[12px] font-semibold px-3 py-1 rounded-full bg-[#002352] text-white';
            tag.innerHTML = `${escHtml(val)} <button type="button" class="ml-1 text-white/60 hover:text-white text-[14px] leading-none">&times;</button>`;
            tag.querySelector('button').addEventListener('click', () => {
                attr.values = attr.values.filter(v => v !== val);
                renderTags(attr);
            });
            row.appendChild(tag);
        });
    }

    function addTagValue(attr, input) {
        const val = input.value.trim();
        if (!val || attr.values.includes(val)) { input.value = ''; return; }
        attr.values.push(val);
        renderTags(attr);
        input.value = '';
        document.getElementById(`attr-input-${attr.id}`)?.focus();
    }

    // ── Build attribute section (free-text tag input) ─
    function buildAttrSection(attr) {
        const section = document.createElement('div');
        section.id = `attr-section-${attr.id}`;
        section.className = 'bg-[#f8f9fb] border border-[#edeef0] rounded-xl p-3 space-y-2';

        const lbl = document.createElement('p');
        lbl.className = 'text-[11px] font-bold uppercase tracking-widest text-[#747780]';
        lbl.textContent = `Valeurs — ${attr.name}`;
        section.appendChild(lbl);

        const tagRow = document.createElement('div');
        tagRow.id = `tag-row-${attr.id}`;
        tagRow.className = 'flex flex-wrap gap-1.5 min-h-[28px]';
        section.appendChild(tagRow);

        const inputRow = document.createElement('div');
        inputRow.className = 'flex gap-2 mt-1';

        const textInput = document.createElement('input');
        textInput.type = 'text';
        textInput.id = `attr-input-${attr.id}`;
        textInput.placeholder = `Ex: Rouge, S, 42… puis Entrée`;
        textInput.className = 'flex-1 text-[12.5px] text-[#191c1e] bg-white border border-[#e1e2e4] rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#002352]/20 transition-colors';
        textInput.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); addTagValue(attr, textInput); }
        });

        const addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.textContent = '+ Ajouter';
        addBtn.className = 'text-[11px] font-bold px-3 py-1.5 bg-[#002352] text-white rounded-lg hover:bg-[#18396e] transition-colors whitespace-nowrap';
        addBtn.addEventListener('click', () => addTagValue(attr, textInput));

        inputRow.appendChild(textInput);
        inputRow.appendChild(addBtn);
        section.appendChild(inputRow);

        attrValueSections.appendChild(section);

        if (attr.values.length) renderTags(attr);
    }

    // ── Attribute checkboxes ──────────────────────────
    document.querySelectorAll('.attribute-checkbox').forEach(cb => {
        cb.addEventListener('change', () => {
            const attrId   = parseInt(cb.value);
            const attrName = cb.dataset.name;

            if (cb.checked) {
                // Pre-populate from data-current-values (edit mode)
                const preVals = cb.dataset.currentValues
                    ? cb.dataset.currentValues.split(',').map(v => v.trim()).filter(Boolean)
                    : [];
                const attr = { id: attrId, name: attrName, values: [...preVals] };
                selectedAttributes.push(attr);
                buildAttrSection(attr);
            } else {
                selectedAttributes = selectedAttributes.filter(a => a.id !== attrId);
                document.getElementById(`attr-section-${attrId}`)?.remove();
            }
        });
    });

    // ── Cartesian product ─────────────────────────────
    function cartesian(arrays) {
        return arrays.reduce((acc, arr) =>
            acc.flatMap(a => arr.map(b => [...a, b])), [[]]
        );
    }

    // ── Generate variants ─────────────────────────────
    generateBtn?.addEventListener('click', () => {
        const activeAttrs = selectedAttributes.filter(a => a.values.length > 0);
        tableBody.innerHTML = '';
        variantIndex = 0;

        if (activeAttrs.length === 0) {
            addVariantRow('Défaut', []);
        } else {
            const combos = cartesian(
                activeAttrs.map(a => a.values.map(v => ({ attrId: a.id, value: v })))
            );
            combos.forEach(combo => {
                addVariantRow(combo.map(c => c.value).join(' / '), combo);
            });
        }

        showTable();
    });

    // ── Add variant row ──────────────────────────────
    // combo: Array<{ attrId: number, value: string }>
    function addVariantRow(label, combo) {
        const idx  = variantIndex++;
        const base = basePriceInput ? escHtml(basePriceInput.value) : '0';

        const hiddenInputs = Array.isArray(combo) && combo.length > 0
            ? combo.map(c => `<input type="hidden" name="variants[${idx}][attr_texts][${c.attrId}]" value="${escHtml(c.value)}">`).join('')
            : '';

        const tr = document.createElement('tr');
        tr.className = 'variant-row hover:bg-[#f8f9fb] transition-colors';
        tr.innerHTML = `
            ${hiddenInputs}
            <td class="px-4 py-3 text-[13px] font-medium text-[#191c1e]">${escHtml(label)}</td>
            <td class="px-4 py-3">
                <input type="text" name="variants[${idx}][sku]" placeholder="SKU"
                    class="w-28 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="variants[${idx}][price]" value="${base}" min="0" step="1"
                    class="variant-price w-24 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="variants[${idx}][stock]" value="0" min="0"
                    class="variant-stock w-20 text-[12.5px] text-[#191c1e] bg-[#f2f4f6] border-none rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-[#002352]/20 focus:bg-white transition-colors"/>
            </td>
            <td class="px-4 py-3">
                <button type="button" class="remove-variant p-1 text-[#c4c6d1] hover:text-red-500 transition-colors rounded">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </td>`;

        tableBody.appendChild(tr);
        wireRemoveBtn(tr.querySelector('.remove-variant'), tr);
    }

    function showTable() {
        tableWrapper?.classList.remove('hidden');
    }

    function wireRemoveBtn(btn, row) {
        btn?.addEventListener('click', () => {
            row.remove();
            if (!tableBody?.querySelector('tr')) {
                tableWrapper?.classList.add('hidden');
            }
        });
    }

    // Wire existing remove buttons (edit page load)
    tableBody?.querySelectorAll('.remove-variant').forEach(btn => {
        wireRemoveBtn(btn, btn.closest('tr'));
    });

    // ── Apply price / stock to all ──────────────────
    applyPrice?.addEventListener('click', () => {
        const val = document.getElementById('bulkPriceInput')?.value?.trim();
        if (val === '' || val === undefined) return;
        tableBody.querySelectorAll('.variant-price').forEach(inp => inp.value = val);
    });

    applyStock?.addEventListener('click', () => {
        const val = document.getElementById('bulkStockInput')?.value?.trim();
        if (val === '' || val === undefined) return;
        tableBody.querySelectorAll('.variant-stock').forEach(inp => inp.value = val);
    });

    // ── Image upload preview ────────────────────────
    function addPreviews(files) {
        [...files].forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const div = document.createElement('div');
                div.className = 'relative group rounded-xl overflow-hidden bg-[#f2f4f6] aspect-square';
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-full object-cover" alt="">
                    <button type="button" class="remove-preview absolute top-1 right-1 w-5 h-5 bg-[#191c1e]/60 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>`;
                div.querySelector('.remove-preview').addEventListener('click', () => div.remove());
                imagePreviews?.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    imageInput?.addEventListener('change', () => addPreviews(imageInput.files));

    dropZone?.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-[#002352]/60'); });
    dropZone?.addEventListener('dragleave', () => dropZone.classList.remove('border-[#002352]/60'));
    dropZone?.addEventListener('drop', e => {
        e.preventDefault();
        dropZone.classList.remove('border-[#002352]/60');
        addPreviews(e.dataTransfer.files);
    });

    // ── Image deletion (X button toggle) ───────────
    document.querySelectorAll('.delete-img-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const wrapper = btn.closest('[data-image-id]');
            const cb      = wrapper?.querySelector('.delete-image-checkbox');
            if (!cb) return;

            cb.checked = true;

            // Disparition animée — le checkbox reste dans le DOM donc soumis avec le form
            wrapper.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
            wrapper.style.opacity    = '0';
            wrapper.style.transform  = 'scale(0.85)';
            setTimeout(() => {
                wrapper.style.display = 'none';
            }, 200);
        });
    });
}

// ─────────────────────────────────────────────
// BOOT
// ─────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    initIndexPage();
    initFormPage();
});
