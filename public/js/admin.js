/**
 * Admin entry point
 * Loads only the modules needed by admin pages.
 */
import Sidebar from './modules/sidebar.js';
import Alerts  from './modules/alerts.js';

document.addEventListener('DOMContentLoaded', () => {
    Sidebar.init();
    Alerts.init();
});
