function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}

function openEdit(id, name, parentId) {
    document.getElementById('editForm').action = `/admin/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editParent').value = parentId || '';
    openModal('modalEdit');
}

function confirmDelete(id, name, productCount, childCount) {
    document.getElementById('deleteForm').action = `/admin/categories/${id}`;
    let msg = `Vous êtes sur le point de supprimer <strong class="text-[#002352]">${name}</strong>.`;
    if (productCount > 0) msg += `<br><br>${productCount} produit(s) associé(s) seront dissocié(s).`;
    if (childCount > 0) msg += `<br>${childCount} sous-catégorie(s) deviendront des catégories racines.`;
    document.getElementById('deleteMessage').innerHTML = msg;
    openModal('modalDelete');
}

function toggleChildren(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('hidden');
    const chevron = document.getElementById(id.replace('children-', 'chevron-'));
    if (chevron) chevron.classList.toggle('-rotate-90');
}
