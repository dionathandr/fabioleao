/**
 * FABIO LEÃO IMOBILIÁRIA
 * JavaScript Principal
 */

document.addEventListener('DOMContentLoaded', function() {
    // ===== HEADER SCROLL =====
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }

    // ===== MOBILE MENU =====
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');
    const mobileOverlay = document.querySelector('.mobile-overlay');
    const mobileClose = document.querySelector('.mobile-menu-close');

    function openMobileMenu() {
        mobileMenu?.classList.add('active');
        mobileOverlay?.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu?.classList.remove('active');
        mobileOverlay?.classList.remove('active');
        document.body.style.overflow = '';
    }

    mobileToggle?.addEventListener('click', openMobileMenu);
    mobileClose?.addEventListener('click', closeMobileMenu);
    mobileOverlay?.addEventListener('click', closeMobileMenu);

    // ===== SEARCH TABS =====
    const searchTabs = document.querySelectorAll('.search-tab');
    const finalidadeInput = document.querySelector('input[name="finalidade"]');

    searchTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            searchTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            if (finalidadeInput) {
                finalidadeInput.value = this.dataset.finalidade || '';
            }
        });
    });

    // ===== DYNAMIC LOCATION SELECTS =====
    const paisSelect = document.getElementById('pais_id');
    const estadoSelect = document.getElementById('estado_id');
    const cidadeSelect = document.getElementById('cidade_id');

    if (paisSelect && estadoSelect) {
        paisSelect.addEventListener('change', function() {
            const paisId = this.value;
            estadoSelect.innerHTML = '<option value="">Selecione o Estado</option>';
            cidadeSelect.innerHTML = '<option value="">Selecione a Cidade</option>';

            if (paisId) {
                fetch(`api/estados.php?pais_id=${paisId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(estado => {
                            const option = document.createElement('option');
                            option.value = estado.id;
                            option.textContent = estado.nome;
                            estadoSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro:', error));
            }
        });
    }

    if (estadoSelect && cidadeSelect) {
        estadoSelect.addEventListener('change', function() {
            const estadoId = this.value;
            cidadeSelect.innerHTML = '<option value="">Selecione a Cidade</option>';

            if (estadoId) {
                fetch(`api/cidades.php?estado_id=${estadoId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.id;
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Erro:', error));
            }
        });
    }

    // ===== FAVORITE PROPERTIES =====
    document.addEventListener('click', function(e) {
        if (e.target.closest('.property-favorite')) {
            const btn = e.target.closest('.property-favorite');
            const imovelId = btn.dataset.id;

            fetch('api/favorito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ imovel_id: imovelId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    btn.classList.toggle('active');
                }
            })
            .catch(error => console.error('Erro:', error));
        }
    });

    // ===== PROPERTY GALLERY =====
    const galleryMain = document.querySelector('.gallery-main img');
    const galleryThumbs = document.querySelectorAll('.gallery-thumb');

    galleryThumbs.forEach(thumb => {
        thumb.addEventListener('click', function() {
            galleryThumbs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const newSrc = this.querySelector('img').src.replace('/thumb_', '/');
            if (galleryMain) {
                galleryMain.src = newSrc;
            }
        });
    });

    // ===== CONTACT FORM =====
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            submitBtn.textContent = 'Enviando...';
            submitBtn.disabled = true;

            fetch('api/contato.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Mensagem enviada com sucesso!', 'success');
                    contactForm.reset();
                } else {
                    showNotification(data.error || 'Erro ao enviar mensagem', 'error');
                }
            })
            .catch(error => {
                showNotification('Erro ao enviar mensagem', 'error');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // ===== PRICE RANGE SLIDER =====
    const priceRange = document.getElementById('priceRange');
    const priceDisplay = document.getElementById('priceDisplay');
    
    if (priceRange && priceDisplay) {
        priceRange.addEventListener('input', function() {
            const value = parseInt(this.value);
            priceDisplay.textContent = formatCurrency(value);
        });
    }

    // ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // ===== LAZY LOADING IMAGES =====
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    observer.unobserve(img);
                }
            });
        });

        document.querySelectorAll('img.lazy').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // ===== ANIMATIONS ON SCROLL =====
    if ('IntersectionObserver' in window) {
        const animateObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            animateObserver.observe(el);
        });
    }
});

// ===== UTILITY FUNCTIONS =====

// Format currency
function formatCurrency(value, currency = 'BRL') {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: currency
    }).format(value);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => notification.classList.add('show'), 10);
    
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    });
    
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// WhatsApp Share
function shareWhatsApp(text, url) {
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`;
    window.open(whatsappUrl, '_blank');
}

// Print Property
function printProperty() {
    window.print();
}

// Copy to clipboard
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Link copiado!', 'success');
    });
}
