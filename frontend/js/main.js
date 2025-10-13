// ================================
// Main UI behaviours: counters, smooth scroll, demo modal and contact form handling.
// Respect prefers-reduced-motion and provide accessible updates.

(function () {
    'use strict';

    // Utility: respects reduced motion
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Smooth scroll for internal anchors and the "Watch Demo" button
    function smoothScrollTo(element) {
        if (!element) return;
        if (prefersReducedMotion) {
            element.focus({ preventScroll: true });
            window.scrollTo({ top: element.offsetTop, behavior: 'auto' });
            return;
        }
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
        element.focus({ preventScroll: true });
    }

    window.scrollToSection = function (id) {
        const el = document.getElementById(id);
        smoothScrollTo(el);
    };

    // Bootstrap modal helper
    function getDemoModal() {
        if (!window.bootstrap) return null;
        const el = document.getElementById('demoModal');
        return el ? new bootstrap.Modal(el) : null;
    }

    window.showDemo = function () {
        const modal = getDemoModal();
        if (modal) modal.show();
    };

    window.openStudentDemo = function () {
        const modal = getDemoModal();
        if (modal) modal.hide();
        // TODO: connect to an in-page demo loader or route
        alert('Opening student demo — placeholder action.');
    };

    // Counters animation
    function animateCounter(el, to) {
        if (prefersReducedMotion) {
            el.textContent = String(to);
            return;
        }
        const duration = 1500;
        const start = performance.now();
        const from = 0;
        function tick(now) {
            const t = Math.min(1, (now - start) / duration);
            const eased = t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t; // easeInOut-ish
            el.textContent = Math.floor(from + (to - from) * eased).toLocaleString();
            if (t < 1) requestAnimationFrame(tick);
            else el.textContent = String(to).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }
        requestAnimationFrame(tick);
    }

    function observeCounters() {
        const counters = Array.from(document.querySelectorAll('.counter'));
        if (!counters.length) return;

        const onIntersection = (entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    const to = parseInt(el.getAttribute('data-count') || el.textContent || '0', 10);
                    animateCounter(el, to);
                    obs.unobserve(el);
                }
            });
        };

        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver(onIntersection, { threshold: 0.4 });
            counters.forEach(c => io.observe(c));
        } else {
            counters.forEach(c => animateCounter(c, parseInt(c.getAttribute('data-count') || '0', 10)));
        }
    }

    // Contact form handling (example only). Replace /api/contact with your server endpoint.
    function handleContactForm() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        const statusEl = document.getElementById('contactStatus');
        const submitBtn = document.getElementById('contactSubmit');

        form.addEventListener('submit', async (ev) => {
            ev.preventDefault();
            statusEl.classList.add('visually-hidden');
            statusEl.textContent = '';

            // Simple HTML5 validation
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            // Honeypot check
            const hp = form.querySelector('[name="hp_email"]');
            if (hp && hp.value) {
                statusEl.classList.remove('visually-hidden');
                statusEl.classList.add('text-danger');
                statusEl.textContent = 'Submission failed. Please try again.';
                return;
            }

            const payload = {
                name: form.name.value.trim(),
                email: form.email.value.trim(),
                subject: form.subject.value.trim(),
                message: form.message.value.trim()
            };

            submitBtn.disabled = true;
            submitBtn.classList.add('loading');

            try {
                // Replace this URL with your contact endpoint
                const res = await fetch('/api/contact', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                if (!res.ok) throw new Error('Network response was not ok');

                statusEl.classList.remove('visually-hidden');
                statusEl.classList.remove('text-danger');
                statusEl.classList.add('text-success');
                statusEl.textContent = 'Thanks! Your message has been sent.';
                form.reset();
                form.classList.remove('was-validated');
            } catch (err) {
                statusEl.classList.remove('visually-hidden');
                statusEl.classList.remove('text-success');
                statusEl.classList.add('text-danger');
                statusEl.textContent = 'Sorry, an error occurred while sending your message. Please try again later.';
                console.error('Contact form submit error:', err);
            } finally {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
            }
        });
    }

    // Anchor smooth scrolling for internal links
    function bindAnchorLinks() {
        document.addEventListener('click', (ev) => {
            const a = ev.target.closest('a[href^="#"]');
            if (!a) return;
            const href = a.getAttribute('href');
            if (href === '#' || href === '#!') return;
            const id = href.slice(1);
            const target = document.getElementById(id);
            if (target) {
                ev.preventDefault();
                smoothScrollTo(target);
            }
        });
    }

    // Initialization
    document.addEventListener('DOMContentLoaded', () => {
        observeCounters();
        handleContactForm();
        bindAnchorLinks();

        const spinner = document.getElementById('loadingSpinner');
        if (spinner) spinner.classList.add('visually-hidden');
    });

})();
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            createRipple(e, this);
        });
    });
    
    // Parallax effects
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        
        // Hero parallax
        const heroElements = document.querySelectorAll('.hero-dashboard');
        heroElements.forEach(element => {
            const speed = 0.3;
            element.style.transform = `translateY(${scrolled * speed}px) scale(${1 + scrolled * 0.0001})`;
        });
        
        // Background parallax
        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            heroSection.style.backgroundPosition = `center ${scrolled * 0.5}px`;
        }
    });


// Create ripple effect for buttons
function createRipple(event, element) {
    const ripple = document.createElement('span');
    ripple.classList.add('ripple-effect');
    
    const rect = element.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    
    ripple.style.left = x + 'px';
    ripple.style.top = y + 'px';
    
    element.appendChild(ripple);
    
    setTimeout(() => {
        ripple.remove();
    }, 600);
}

// Counter Animation
function animateCounter(element) {
    if (!element || element.classList.contains('counted')) return;
    
    const target = parseInt(element.getAttribute('data-count'));
    const duration = 2000;
    const increment = target / (duration / 16);
    let current = 0;
    
    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
            element.classList.add('counted');
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// Initialize Counters
function initializeCounters() {
    const counters = document.querySelectorAll('[data-count]');
    counters.forEach(counter => {
        counter.textContent = '0';
    });
}

// Setup Forms
function setupForms() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleContactSubmit(this);
        });
    }
    
    // Form validation
    setupFormValidation();
}

// Handle Contact Form Submission
function handleContactSubmit(form) {
    showLoading(true);
    
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    // Simulate API call
    setTimeout(() => {
        console.log('Contact form submitted:', data);
        showLoading(false);
        showNotification('Message sent successfully!', 'success');
        form.reset();
    }, 1500);
}

// Form Validation
function setupFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input[required], textarea[required]');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    });
}

// Validate Individual Field
function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    let isValid = true;
    let errorMessage = '';
    
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        errorMessage = 'This field is required.';
    } else if (type === 'email' && value && !isValidEmail(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address.';
    }
    
    if (!isValid) {
        showFieldError(field, errorMessage);
    } else {
        clearFieldError(field);
    }
    
    return isValid;
}

// Email Validation
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show Field Error
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.classList.add('is-invalid');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

// Clear Field Error
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Setup Modals
function setupModals() {
    // Login modal setup is handled by Bootstrap
    const loginModal = document.getElementById('loginModal');
    
    if (loginModal) {
        loginModal.addEventListener('show.bs.modal', function() {
            console.log('Login modal opened');
        });
        
        loginModal.addEventListener('hide.bs.modal', function() {
            console.log('Login modal closed');
        });
    }
}

// Open Demo Modal
function openDemoModal() {
    const modal = new bootstrap.Modal(document.getElementById('demoModal'));
    modal.show();
}

// Navigate to login
function goToLogin() {
    window.location.href = 'login.html';
}

// Redirect to Login
function redirectToLogin(role) {
    showLoading(true);
    
    setTimeout(() => {
        if (role === 'admin') {
            window.location.href = 'login.html?role=admin';
        } else {
            window.location.href = 'login.html?role=student';
        }
    }, 800);
}

// Show Demo
function showDemo() {
    showLoading(true);
    
    setTimeout(() => {
        window.location.href = 'student-dashboard.html';
    }, 800);
}

// Loading Functions
function showLoading(show = true) {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) {
        if (show) {
            spinner.classList.add('show');
            isLoading = true;
        } else {
            spinner.classList.remove('show');
            isLoading = false;
        }
    }
}

// Notification System
function showNotification(message, type = 'info', duration = 3000) {
    // Remove existing notifications
    const existing = document.querySelector('.custom-notification');
    if (existing) {
        existing.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${type === 'error' ? 'danger' : type} alert-dismissible`;
    notification.innerHTML = `
        <i class="fas fa-${getIconForType(type)} me-2"></i>
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    // Style the notification
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: '9999',
        minWidth: '300px',
        animation: 'slideInRight 0.3s ease'
    });
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Auto remove
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }
    }, duration);
}

// Get Icon for Notification Type
function getIconForType(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-triangle',
        warning: 'exclamation-circle',
        info: 'info-circle'
    };
    return icons[type] || icons.info;
}

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let lastFunc;
    let lastRan;
    return function() {
        const context = this;
        const args = arguments;
        if (!lastRan) {
            func.apply(context, args);
            lastRan = Date.now();
        } else {
            clearTimeout(lastFunc);
            lastFunc = setTimeout(function() {
                if ((Date.now() - lastRan) >= limit) {
                    func.apply(context, args);
                    lastRan = Date.now();
                }
            }, limit - (Date.now() - lastRan));
        }
    };
}

// Keyboard Navigation
document.addEventListener('keydown', function(e) {
    // Escape key closes modals
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('.modal.show');
        openModals.forEach(modal => {
            bootstrap.Modal.getInstance(modal)?.hide();
        });
    }
    
    // Enter key on role cards
    if (e.key === 'Enter' && e.target.classList.contains('role-card')) {
        e.target.click();
    }
});

// Touch/Swipe Support for Mobile
let touchStartX = 0;
let touchEndX = 0;

document.addEventListener('touchstart', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener('touchend', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        // Implement swipe navigation if needed
        console.log(diff > 0 ? 'Swiped left' : 'Swiped right');
    }
}

// Performance Optimization
function optimizeImages() {
    const images = document.querySelectorAll('img');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

// Call optimization functions
document.addEventListener('DOMContentLoaded', function() {
    optimizeImages();
});

// Accessibility Enhancements
function enhanceAccessibility() {
    // Add skip link
    const skipLink = document.createElement('a');
    skipLink.href = '#main';
    skipLink.textContent = 'Skip to main content';
    skipLink.className = 'sr-only sr-only-focusable';
    skipLink.style.cssText = `
        position: absolute;
        top: -40px;
        left: 6px;
        width: 1px;
        height: 1px;
        padding: 8px 16px;
        margin: -1px;
        overflow: hidden;
        clip: rect(0,0,0,0);
        border: 0;
        z-index: 10000;
    `;
    
    skipLink.addEventListener('focus', function() {
        this.style.cssText = `
            position: absolute;
            top: 6px;
            left: 6px;
            width: auto;
            height: auto;
            padding: 8px 16px;
            margin: 0;
            overflow: visible;
            clip: auto;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            z-index: 10000;
        `;
    });
    
    document.body.insertBefore(skipLink, document.body.firstChild);
    
    // Add ARIA labels to interactive elements
    const roleCards = document.querySelectorAll('.role-card');
    roleCards.forEach((card, index) => {
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Select ${card.querySelector('h5').textContent} role`);
    });
}

// Initialize accessibility enhancements
document.addEventListener('DOMContentLoaded', enhanceAccessibility);

// Error Handling
window.addEventListener('error', function(e) {
    console.error('JavaScript Error:', e.error);
    showNotification('Something went wrong. Please try again.', 'error');
});

// Service Worker Registration (for future PWA support)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        // navigator.serviceWorker.register('/sw.js')
        //     .then(registration => console.log('SW registered'))
        //     .catch(registrationError => console.log('SW registration failed'));
    });
}

// Export functions for global access
window.LibraryApp = {
    scrollToSection,
    openLoginModal,
    redirectToLogin,
    showNotification,
    showLoading
};

console.log('Library Management System - Frontend JavaScript Loaded');