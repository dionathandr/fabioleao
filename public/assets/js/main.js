/**
 * FABIOLEAO Imóveis - Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // ========================================
    // PRELOADER
    // ========================================
    const preloader = document.querySelector('.preload');
    if (preloader) {
        window.addEventListener('load', function() {
            setTimeout(() => {
                preloader.classList.add('loaded');
            }, 500);
        });
        
        // Fallback: hide preloader after 3 seconds
        setTimeout(() => {
            preloader.classList.add('loaded');
        }, 3000);
    }
    
    // ========================================
    // MOBILE MENU
    // ========================================
    const mobileToggler = document.querySelector('.mobile-nav-toggler');
    const mobileMenu = document.querySelector('.mobile-menu');
    const closeBtn = document.querySelector('.close-btn');
    const menuBackdrop = document.querySelector('.menu-backdrop');
    
    function openMobileMenu() {
        mobileMenu.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    if (mobileToggler) {
        mobileToggler.addEventListener('click', openMobileMenu);
    }
    
    if (closeBtn) {
        closeBtn.addEventListener('click', closeMobileMenu);
    }
    
    if (menuBackdrop) {
        menuBackdrop.addEventListener('click', closeMobileMenu);
    }
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileMenu && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
    });
    
    // ========================================
    // HEADER SCROLL
    // ========================================
    const header = document.querySelector('.main-header');
    let lastScroll = 0;
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        lastScroll = currentScroll;
    });
    
    // ========================================
    // BACK TO TOP BUTTON
    // ========================================
    const backToTop = document.getElementById('backToTop');
    
    if (backToTop) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });
        
        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // ========================================
    // SMOOTH SCROLL FOR ANCHOR LINKS
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                
                const target = document.querySelector(href);
                if (target) {
                    const headerHeight = header ? header.offsetHeight : 0;
                    const targetPosition = target.offsetTop - headerHeight - 20;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    closeMobileMenu();
                }
            }
        });
    });
    
    // ========================================
    // CONTACT FORM SUBMISSION
    // ========================================
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Disable button and show loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
            
            fetch('api/contato.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
                    contactForm.reset();
                } else {
                    showAlert('error', data.error || 'Erro ao enviar mensagem. Tente novamente.');
                }
            })
            .catch(error => {
                showAlert('error', 'Erro ao enviar mensagem. Tente novamente.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // ========================================
    // PROPERTY CONTACT FORM
    // ========================================
    const propertyContactForm = document.getElementById('propertyContactForm');
    
    if (propertyContactForm) {
        propertyContactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Enviando...';
            
            fetch('api/contato.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'Mensagem enviada com sucesso! O corretor entrará em contato em breve.');
                    propertyContactForm.reset();
                } else {
                    showAlert('error', data.error || 'Erro ao enviar mensagem. Tente novamente.');
                }
            })
            .catch(error => {
                showAlert('error', 'Erro ao enviar mensagem. Tente novamente.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    // ========================================
    // ALERT FUNCTION
    // ========================================
    function showAlert(type, message) {
        // Remove any existing alerts
        const existingAlerts = document.querySelectorAll('.alert-toast');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create alert element
        const alert = document.createElement('div');
        alert.className = `alert-toast alert-${type}`;
        alert.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle'}"></i>
            <span>${message}</span>
            <button type="button" class="alert-close">&times;</button>
        `;
        
        // Add styles
        alert.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#10b981' : '#ef4444'};
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease;
            max-width: 400px;
        `;
        
        // Add animation keyframes
        if (!document.querySelector('#alertStyles')) {
            const style = document.createElement('style');
            style.id = 'alertStyles';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        document.body.appendChild(alert);
        
        // Close button
        alert.querySelector('.alert-close').addEventListener('click', function() {
            alert.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        });
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    }
    
    // ========================================
    // FAVORITE BUTTON
    // ========================================
    document.querySelectorAll('.btn-action[title="Favoritar"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const icon = this.querySelector('i');
            
            if (icon.classList.contains('bi-heart')) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                this.style.color = '#ef4444';
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                this.style.color = '';
            }
        });
    });
    
    // ========================================
    // SHARE BUTTON
    // ========================================
    document.querySelectorAll('.btn-action[title="Compartilhar"]').forEach(btn => {
        btn.addEventListener('click', function() {
            const url = window.location.href;
            
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    showAlert('success', 'Link copiado para a área de transferência!');
                });
            }
        });
    });
    
    // ========================================
    // IMAGE GALLERY (for property details)
    // ========================================
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');
    const mainImage = document.querySelector('.main-image img');
    
    if (galleryThumbs.length > 0 && mainImage) {
        galleryThumbs.forEach(thumb => {
            thumb.addEventListener('click', function() {
                const newSrc = this.querySelector('img').src;
                mainImage.src = newSrc;
                
                // Update active state
                galleryThumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    // ========================================
    // PRICE FORMATTING
    // ========================================
    window.formatPrice = function(value) {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    };
    
    // ========================================
    // PHONE MASK
    // ========================================
    const phoneInputs = document.querySelectorAll('input[name="telefone"]');
    
    phoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            if (value.length > 11) {
                value = value.slice(0, 11);
            }
            
            if (value.length > 0) {
                value = '(' + value;
            }
            if (value.length > 3) {
                value = value.slice(0, 3) + ') ' + value.slice(3);
            }
            if (value.length > 10) {
                value = value.slice(0, 10) + '-' + value.slice(10);
            }
            
            e.target.value = value;
        });
    });
    
    // ========================================
    // SEARCH FORM ENHANCEMENT
    // ========================================
    const searchForms = document.querySelectorAll('.search-form');
    
    searchForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Remove empty fields from submission
            const inputs = this.querySelectorAll('input, select');
            
            inputs.forEach(input => {
                if (!input.value || input.value === '') {
                    input.removeAttribute('name');
                }
            });
        });
    });
    
    // ========================================
    // LAZY LOADING IMAGES
    // ========================================
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
    
    // ========================================
    // DROPDOWN MOBILE FIX
    // ========================================
    const mobileDropdowns = document.querySelectorAll('.navigation-mobile .dropdown2');
    
    mobileDropdowns.forEach(dropdown => {
        const link = dropdown.querySelector('a');
        
        link.addEventListener('click', function(e) {
            e.preventDefault();
            dropdown.classList.toggle('active');
        });
    });
    
});

// ========================================
// UTILITY FUNCTIONS
// ========================================

// Debounce function
function debounce(func, wait, immediate) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}
