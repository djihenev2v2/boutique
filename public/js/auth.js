/**
 * Auth entry point
 * Loads only the modules needed by auth pages (login / register).
 */
import Password from './modules/password.js';

document.addEventListener('DOMContentLoaded', () => {
    Password.init();
});
