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

// Additional UI enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Ripple effects for buttons
    document.querySelectorAll('.btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            createRipple(e, this);
        });
    });
    
    // Parallax effects (only if user hasn't requested reduced motion)
    if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
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

// Global loading state
let isLoading = false;