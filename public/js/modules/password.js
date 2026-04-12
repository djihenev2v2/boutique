/**
 * Password — toggle visibility on password fields
 */
const Password = {
    init() {
        // Expose global for onclick attributes already in Blade
        window.togglePwd = () => this.toggle();
    },

    toggle() {
        const input = document.getElementById('password');
        if (!input) return;
        input.type = input.type === 'password' ? 'text' : 'password';
    }
};

export default Password;
