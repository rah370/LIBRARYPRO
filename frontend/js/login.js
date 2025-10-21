// Login Page JavaScript
class LoginSystem {
    constructor() {
        this.currentRole = null;
        this.isLoading = false;
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.checkURLParams();
        this.setupFormValidation();
        console.log('Login System initialized');
    }

    setupEventListeners() {
        // Form submissions
        const authForm = document.getElementById('authForm');
        const registrationForm = document.getElementById('registrationForm');

        if (authForm) {
            authForm.addEventListener('submit', (e) => this.handleLogin(e));
        }

        if (registrationForm) {
            registrationForm.addEventListener('submit', (e) => this.handleRegistration(e));
        }

        // Password visibility toggle
        window.togglePassword = () => this.togglePasswordVisibility();
        
        // Navigation functions
        window.selectRole = (role) => this.selectRole(role);
        window.backToRoleSelection = () => this.backToRoleSelection();
        window.showDemo = () => this.showDemo();
        window.showForgotPassword = () => this.showForgotPassword();

        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyNavigation(e));

        // Demo autofill button on static page
        const fillDemoBtn = document.getElementById('fillDemoBtn');
        if (fillDemoBtn) {
            fillDemoBtn.addEventListener('click', () => {
                this.selectRole('student');
                setTimeout(() => {
                    document.getElementById('username').value = 'student';
                    document.getElementById('password').value = 'student123';
                    // auto-submit
                    document.getElementById('authForm').dispatchEvent(new Event('submit'));
                }, 400);
            });
        }
    }

    checkURLParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const role = urlParams.get('role');
        
        if (role && (role === 'student' || role === 'admin')) {
            this.selectRole(role);
        }
    }

    selectRole(role) {
        this.currentRole = role;
        
        // Hide role selection and show login form
        document.getElementById('roleSelection').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
        
        // Update form based on role
        this.updateFormForRole(role);
        
        // Focus on username field
        setTimeout(() => {
            document.getElementById('username').focus();
        }, 300);

        // Update URL without reload
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('role', role);
        window.history.pushState({}, '', newUrl);
    }

    updateFormForRole(role) {
        const formTitle = document.getElementById('formTitle');
        const roleBadgeIcon = document.getElementById('roleBadgeIcon');
        const selectedRoleInput = document.getElementById('selectedRole');
        
        if (role === 'admin') {
            formTitle.textContent = 'Administrator Login';
            roleBadgeIcon.className = 'fas fa-user-shield';
            document.querySelector('.role-badge').style.background = 'linear-gradient(135deg, #27ae60 0%, #2ecc71 100%)';
        } else {
            formTitle.textContent = 'Student Login';
            roleBadgeIcon.className = 'fas fa-graduation-cap';
            document.querySelector('.role-badge').style.background = 'var(--gradient-primary)';
        }
        
        selectedRoleInput.value = role;
    }

    backToRoleSelection() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('roleSelection').style.display = 'block';
        
        // Clear form
        document.getElementById('authForm').reset();
        this.clearFormErrors();
        
        // Update URL
        const newUrl = new URL(window.location);
        newUrl.searchParams.delete('role');
        window.history.pushState({}, '', newUrl);
        
        this.currentRole = null;
    }

    togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('passwordToggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.className = 'fas fa-eye-slash';
        } else {
            passwordField.type = 'password';
            toggleIcon.className = 'fas fa-eye';
        }
    }

    async handleLogin(e) {
        e.preventDefault();
        
        if (this.isLoading) return;
        
        const formData = new FormData(e.target);
        const credentials = {
            username: formData.get('username'),
            password: formData.get('password'),
            role: formData.get('role'),
            remember_me: formData.get('remember_me') ? 1 : 0
        };

        // Validate form
        if (!this.validateLoginForm(credentials)) {
            return;
        }

        try {
            this.setLoadingState(true);
            
            // Make API call to backend
            const response = await this.makeLoginRequest(credentials);
            
            if (response.success) {
                this.showAlert('Login successful! Redirecting...', 'success');

                // Determine role: prefer server/client response role, fall back to selected role
                const resolvedRole = response.role || credentials.role || 'student';

                // Redirect based on resolved role
                setTimeout(() => {
                    if (resolvedRole === 'admin') {
                        window.location.href = '../index.php/admin';
                    } else {
                        window.location.href = '../index.php/student';
                    }
                }, 800);
            } else {
                this.showAlert(response.message || 'Invalid credentials. Please try again.', 'danger');
            }
            
        } catch (error) {
            console.error('Login error:', error);
            this.showAlert('Connection error. Please check your internet connection and try again.', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    async makeLoginRequest(credentials) {
        // First try to make actual API call to backend
        try {
            const response = await fetch('../index.php/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(credentials)
            });

            if (response.ok) {
                const result = await response.json();
                return result;
            } else {
                throw new Error('Network response was not ok');
            }
        } catch (error) {
            console.log('Backend not available, using demo credentials');
            
            // Fallback to demo credentials
            return this.validateDemoCredentials(credentials);
        }
    }

    validateDemoCredentials(credentials) {
        const demoCredentials = {
            student: { usernames: ['student', 'john_doe'], password: 'student123' },
            admin: { usernames: ['admin'], password: 'admin123' }
        };

        // If role not provided, try both roles
        const rolesToCheck = credentials.role ? [credentials.role] : Object.keys(demoCredentials);

        for (const r of rolesToCheck) {
            const demo = demoCredentials[r];
            if (!demo) continue;

            const usernameToCheck = (credentials.username || '').split('@')[0];
            if ((demo.usernames && demo.usernames.includes(credentials.username)) || demo.usernames.includes(usernameToCheck)) {
                if (credentials.password === demo.password) {
                    // Store demo session
                    sessionStorage.setItem('demo_user', JSON.stringify({
                        role: r,
                        username: credentials.username,
                        name: r === 'admin' ? 'Demo Administrator' : 'Demo Student'
                    }));

                    return { success: true, message: 'Demo login successful', role: r };
                }
            }
        }

        return { 
            success: false, 
            message: 'Invalid credentials. Use demo credentials shown below the form.' 
        };
    }

    validateLoginForm(credentials) {
        this.clearFormErrors();
        let isValid = true;

        // Username validation
        if (!credentials.username || credentials.username.length < 3) {
            this.showFieldError('username', 'Username must be at least 3 characters long');
            isValid = false;
        }

        // Password validation
        if (!credentials.password || credentials.password.length < 4) {
            this.showFieldError('password', 'Password must be at least 4 characters long');
            isValid = false;
        }

        return isValid;
    }

    async handleRegistration(e) {
        e.preventDefault();
        
        if (this.isLoading) return;
        
        const formData = new FormData(e.target);
        const registrationData = {
            first_name: formData.get('first_name'),
            last_name: formData.get('last_name'),
            student_id: formData.get('student_id'),
            email: formData.get('email'),
            password: formData.get('password'),
            confirm_password: document.getElementById('confirmPassword').value
        };

        if (!this.validateRegistrationForm(registrationData)) {
            return;
        }

        try {
            this.setLoadingState(true);
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            this.showAlert('Account created successfully! Please login with your credentials.', 'success');
            
            setTimeout(() => {
                this.backToRoleSelection();
                // Pre-fill username
                setTimeout(() => {
                    this.selectRole('student');
                    document.getElementById('username').value = registrationData.student_id;
                }, 300);
            }, 2000);
            
        } catch (error) {
            this.showAlert('Registration failed. Please try again.', 'danger');
        } finally {
            this.setLoadingState(false);
        }
    }

    validateRegistrationForm(data) {
        this.clearFormErrors();
        let isValid = true;

        // Name validation
        if (!data.first_name || data.first_name.length < 2) {
            this.showFieldError('firstName', 'First name must be at least 2 characters');
            isValid = false;
        }

        if (!data.last_name || data.last_name.length < 2) {
            this.showFieldError('lastName', 'Last name must be at least 2 characters');
            isValid = false;
        }

        // Student ID validation
        if (!data.student_id || data.student_id.length < 3) {
            this.showFieldError('studentId', 'Student ID must be at least 3 characters');
            isValid = false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!data.email || !emailRegex.test(data.email)) {
            this.showFieldError('email', 'Please enter a valid email address');
            isValid = false;
        }

        // Password validation
        if (!data.password || data.password.length < 6) {
            this.showFieldError('regPassword', 'Password must be at least 6 characters');
            isValid = false;
        }

        // Confirm password validation
        if (data.password !== data.confirm_password) {
            this.showFieldError('confirmPassword', 'Passwords do not match');
            isValid = false;
        }

        return isValid;
    }

    setupFormValidation() {
        // Real-time validation
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('blur', () => this.validateField(input));
            input.addEventListener('input', () => this.clearFieldError(input.id));
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const id = field.id;
        
        this.clearFieldError(id);

        switch (id) {
            case 'username':
                if (value.length < 3) {
                    this.showFieldError(id, 'Username must be at least 3 characters');
                }
                break;
            case 'password':
                if (value.length < 4) {
                    this.showFieldError(id, 'Password must be at least 4 characters');
                }
                break;
            case 'email':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (value && !emailRegex.test(value)) {
                    this.showFieldError(id, 'Please enter a valid email address');
                }
                break;
            case 'confirmPassword':
                const password = document.getElementById('regPassword').value;
                if (value && value !== password) {
                    this.showFieldError(id, 'Passwords do not match');
                }
                break;
        }
    }

    showFieldError(fieldId, message) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        field.classList.add('is-invalid');
        
        // Remove existing error
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }

        // Add new error
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = message;
        
        if (field.parentNode.classList.contains('input-group')) {
            field.parentNode.parentNode.appendChild(errorDiv);
        } else {
            field.parentNode.appendChild(errorDiv);
        }
    }

    clearFieldError(fieldId) {
        const field = document.getElementById(fieldId);
        if (!field) return;

        field.classList.remove('is-invalid');
        
        const errorDiv = field.parentNode.querySelector('.invalid-feedback') || 
                         field.parentNode.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    clearFormErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
    }

    setLoadingState(loading) {
        this.isLoading = loading;
        const loginButton = document.getElementById('loginButton');
        const overlay = document.getElementById('loadingOverlay');
        
        if (loginButton) {
            const buttonText = loginButton.querySelector('.button-text');
            const buttonSpinner = loginButton.querySelector('.button-spinner');
            
            if (loading) {
                buttonText.style.display = 'none';
                buttonSpinner.style.display = 'flex';
                loginButton.disabled = true;
            } else {
                buttonText.style.display = 'flex';
                buttonSpinner.style.display = 'none';
                loginButton.disabled = false;
            }
        }
        
        if (overlay) {
            if (loading) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
        }
    }

    showAlert(message, type = 'info') {
        const container = document.getElementById('alertContainer');
        if (!container) return;

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
        `;

        container.appendChild(alertDiv);

        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentElement) {
                alertDiv.remove();
            }
        }, 5000);
    }

    showDemo() {
        this.showAlert('Demo mode: Use the credentials shown in the login form to test the system.', 'info');
        
        // Auto-fill demo credentials
        if (this.currentRole) {
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            
            if (this.currentRole === 'admin') {
                username.value = 'admin';
                password.value = 'admin123';
            } else {
                username.value = 'student';
                password.value = 'password';
            }
        }
    }

    showForgotPassword() {
        this.showAlert('Password reset functionality is not available in the demo. Contact your system administrator for assistance.', 'warning');
    }

    handleKeyNavigation(e) {
        // Escape key
        if (e.key === 'Escape') {
            if (document.getElementById('loginForm').style.display !== 'none') {
                this.backToRoleSelection();
            }
        }
        
        // Enter key on role cards
        if (e.key === 'Enter' && e.target.classList.contains('role-card')) {
            e.target.click();
        }
    }
}

// Initialize login system
document.addEventListener('DOMContentLoaded', () => {
    window.loginSystem = new LoginSystem();
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}