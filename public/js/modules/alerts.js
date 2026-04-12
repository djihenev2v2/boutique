/**
 * Alerts — auto-dismiss flash messages
 */
const Alerts = {
    init(delay = 5000) {
        document.querySelectorAll('[data-alert]').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity .3s ease';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }, delay);
        });
    }
};

export default Alerts;
