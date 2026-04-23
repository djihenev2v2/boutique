function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

/* ── icon picker ── */
function selectIcon(prefix, key) {
    const input = document.getElementById(prefix + 'IconVal');
    if (input) input.value = key;
    document.querySelectorAll('[data-icon-prefix="' + prefix + '"]').forEach(function(btn) {
        if (btn.dataset.iconKey === key) {
            btn.classList.add('ring-2', 'ring-[#002352]', 'bg-[#e8edf5]');
        } else {
            btn.classList.remove('ring-2', 'ring-[#002352]', 'bg-[#e8edf5]');
        }
    });
}

/* ── auto-suggest icon from category name ── */
var iconKeywords = {
    shirt:       ['chemise','shirt','tshirt','t-shirt','polo','blouse','top','tunique','gilet'],
    dress:       ['robe','dress','jupe','skirt','combinaison'],
    pants:       ['pantalon','pants','jean','short','bermuda','legging','jogging','survêt','slim','chino'],
    shoe:        ['chaussure','shoe','botte','boot','talon','heel','mocassin','sandale','escarpin','ballerine','mule','derby'],
    sneaker:     ['basket','sneaker','baskets','running','tennis','training'],
    bag:         ['sac','bag','pochette','purse','wallet','handbag','cartable','maroquinerie'],
    jacket:      ['veste','jacket','manteau','coat','blouson','imperméable','parka','sweat','hoodie','cardigan','pull','sweater'],
    hat:         ['chapeau','hat','casquette','cap','bonnet','béret','cagoule'],
    watch:       ['montre','watch'],
    jewelry:     ['bijou','bijoux','jewelry','jewellery','bague','collier','necklace','ring','boucle','earring','bracelet','pendentif'],
    sunglasses:  ['lunette','lunettes','sunglasses','glasses'],
    kids:        ['enfant','kids','child','bébé','baby','garçon','fille','junior','nourrisson'],
    sport:       ['sport','gym','fitness','yoga','running','foot','football','tennis','natation'],
    accessories: ['accessoire','accessoires','accessory','ceinture','belt','foulard','scarf','écharpe','bonnet','gant','gants'],
    perfume:     ['parfum','perfume','fragrance'],
};

function suggestIcon(prefix, name) {
    var lc = name.toLowerCase();
    for (var key in iconKeywords) {
        var kws = iconKeywords[key];
        for (var i = 0; i < kws.length; i++) {
            if (lc.indexOf(kws[i]) !== -1) {
                selectIcon(prefix, key);
                return;
            }
        }
    }
}

/* ── open edit modal ── */
function openEdit(id, name, parentId, icon) {
    document.getElementById('editForm').action = '/admin/categories/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editParent').value = parentId || '';
    selectIcon('edit', icon || '');
    openModal('modalEdit');
}

/* ── bind auto-suggest on Add modal name input ── */
document.addEventListener('DOMContentLoaded', function() {
    var addNameInput = document.querySelector('#modalAdd input[name="name"]');
    if (addNameInput) {
        addNameInput.addEventListener('input', function() {
            suggestIcon('add', this.value);
        });
    }
    var editNameInput = document.getElementById('editName');
    if (editNameInput) {
        editNameInput.addEventListener('input', function() {
            suggestIcon('edit', this.value);
        });
    }
});

function confirmDelete(id, name, productCount, childCount) {
    document.getElementById('deleteForm').action = '/admin/categories/' + id;
    var msg = 'Vous êtes sur le point de supprimer <strong class="text-[#002352]">' + name + '</strong>.';
    if (productCount > 0) msg += '<br><br>' + productCount + ' produit(s) associé(s) seront dissocié(s).';
    if (childCount > 0) msg += '<br>' + childCount + ' sous-catégorie(s) deviendront des catégories racines.';
    document.getElementById('deleteMessage').innerHTML = msg;
    openModal('modalDelete');
}

function toggleChildren(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('hidden');
    var chevron = document.getElementById(id.replace('children-', 'chevron-'));
    if (chevron) chevron.classList.toggle('-rotate-90');
}
