/**
 * FABIO LEÃO IMOBILIÁRIA
 * JavaScript do Painel Administrativo
 */

document.addEventListener('DOMContentLoaded', function() {
    // ===== MOBILE SIDEBAR TOGGLE =====
    const sidebarToggle = document.querySelector('.mobile-sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
        
        // Fechar ao clicar fora
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('active');
            }
        });
    }

    // ===== DYNAMIC LOCATION SELECTS =====
    const paisSelect = document.getElementById('pais_id');
    const estadoSelect = document.getElementById('estado_id');
    const cidadeSelect = document.getElementById('cidade_id');

    if (paisSelect && estadoSelect) {
        paisSelect.addEventListener('change', function() {
            const paisId = this.value;
            estadoSelect.innerHTML = '<option value="">Selecione o Estado</option>';
            if (cidadeSelect) cidadeSelect.innerHTML = '<option value="">Selecione a Cidade</option>';

            if (paisId) {
                fetch(`../api/estados.php?pais_id=${paisId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(estado => {
                            const option = document.createElement('option');
                            option.value = estado.id;
                            option.textContent = estado.nome;
                            estadoSelect.appendChild(option);
                        });
                    });
            }
        });
    }

    if (estadoSelect && cidadeSelect) {
        estadoSelect.addEventListener('change', function() {
            const estadoId = this.value;
            cidadeSelect.innerHTML = '<option value="">Selecione a Cidade</option>';

            if (estadoId) {
                fetch(`../api/cidades.php?estado_id=${estadoId}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.id;
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                    });
            }
        });
    }

    // ===== FILE UPLOAD PREVIEW =====
    const fileUpload = document.querySelector('.file-upload input[type="file"]');
    const uploadedImages = document.querySelector('.uploaded-images');

    if (fileUpload && uploadedImages) {
        fileUpload.addEventListener('change', function() {
            const files = this.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'uploaded-image';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Preview">
                            <button type="button" class="uploaded-image-remove">&times;</button>
                        `;
                        uploadedImages.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        // Remove uploaded image
        uploadedImages.addEventListener('click', function(e) {
            if (e.target.classList.contains('uploaded-image-remove')) {
                e.target.closest('.uploaded-image').remove();
            }
        });
    }

    // ===== CONFIRM DELETE =====
    document.querySelectorAll('[data-confirm]').forEach(element => {
        element.addEventListener('click', function(e) {
            const message = this.dataset.confirm || 'Tem certeza que deseja excluir?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });

    // ===== MODAL =====
    const modalTriggers = document.querySelectorAll('[data-modal]');
    const modalOverlays = document.querySelectorAll('.modal-overlay');

    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('active');
            }
        });
    });

    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this || e.target.classList.contains('modal-close')) {
                this.classList.remove('active');
            }
        });
    });

    // ===== FORM VALIDATION =====
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Preencha todos os campos obrigatórios');
            }
        });
    });

    // ===== SELECT ALL CHECKBOX =====
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="selected[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    // ===== AUTO-HIDE ALERTS =====
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// ===== UTILITY FUNCTIONS =====

// Format currency for display
function formatCurrency(value) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1000; animation: slideIn 0.3s ease;';
    notification.innerHTML = `
        <div class="alert-content">
            <span class="alert-message">${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
