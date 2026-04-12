/**
 * Sidebar — toggle mobile sidebar + overlay
 */
const Sidebar = {
    init() {
        this.sidebar  = document.getElementById('sidebar');
        this.overlay  = document.getElementById('sidebarOverlay');
        if (!this.sidebar) return;

        // Expose global for onclick attributes already in Blade
        window.toggleSidebar = () => this.toggle();
    },

    toggle() {
        const hidden = this.sidebar.classList.contains('-translate-x-full');
        this.sidebar.classList.toggle('-translate-x-full', !hidden);
        this.overlay.classList.toggle('hidden', hidden);
    }
};

export default Sidebar;
