document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Check required fields
            const requiredInputs = form.querySelectorAll('[required]');
            requiredInputs.forEach(function(input) {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            // Password match validation for register form
            if (form.id === 'register-form') {
                const password = form.querySelector('#reg-password');
                const confirmPassword = form.querySelector('#reg-confirm-password');
                
                if (password.value !== confirmPassword.value) {
                    isValid = false;
                    confirmPassword.classList.add('is-invalid');
                    confirmPassword.nextElementSibling.textContent = 'Passwords do not match';
                } else if (password.value.length < 6) {
                    isValid = false;
                    password.classList.add('is-invalid');
                    password.nextElementSibling.textContent = 'Password must be at least 6 characters';
                }
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});