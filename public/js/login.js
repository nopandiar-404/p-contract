(function() {
    const pwd = document.getElementById('password');
    const toggle = document.querySelector('.toggle-password');
    const strength = document.getElementById('pwdStrength');

    function setStrength(val) {
        if (!strength) return;
        strength.innerHTML = '<i></i>';
        const bar = strength.querySelector('i');
        if (val < 2) {
            bar.style.width = '20%';
            bar.style.background = 'linear-gradient(90deg,#fb7185,#fb7185)'
        } else if (val === 2) {
            bar.style.width = '50%';
            bar.style.background = 'linear-gradient(90deg,#f97316,#f97316)'
        } else {
            bar.style.width = '100%';
            bar.style.background = 'linear-gradient(90deg,#34d399,#059669)'
        }
    }

    function scorePassword(p) {
        let score = 0;
        if (!p) return 0;
        if (p.length >= 8) score++;
        if (/[A-Z]/.test(p)) score++;
        if (/[0-9]/.test(p)) score++;
        if (/[^A-Za-z0-9]/.test(p)) score++;
        return Math.min(score, 3);
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (pwd) {
            pwd.addEventListener('input', function() { setStrength(scorePassword(this.value)); });
        }

        if (toggle) {
            toggle.addEventListener('click', function() {
                const p = document.getElementById('password');
                if (p.type === 'password') { p.type = 'text';
                    this.textContent = '🙈' } else { p.type = 'password';
                    this.textContent = '👁' }
            });
        }

        // basic client-side validation to prevent obvious bad submits
        const form = document.querySelector('.login-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const email = document.getElementById('email');
                if (!email.value || !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email.value)) {
                    email.classList.add('invalid');
                    email.focus();
                    e.preventDefault();
                }
            });
        }
    });
})();