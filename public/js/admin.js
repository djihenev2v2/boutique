function toggleSidebar() {
    var s = document.getElementById('sidebar');
    var o = document.getElementById('sidebarOverlay');
    if (s.classList.contains('-translate-x-full')) {
        s.classList.remove('-translate-x-full');
        o.classList.remove('hidden');
    } else {
        s.classList.add('-translate-x-full');
        o.classList.add('hidden');
    }
}
