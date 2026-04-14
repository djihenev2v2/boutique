/**
 * Client entry point
 * Loads only the modules needed by client pages.
 */
import Sidebar from './modules/sidebar.js';
import Alerts from './modules/alerts.js';

document.addEventListener('DOMContentLoaded', () => {
    Sidebar.init();
    Alerts.init();
});
