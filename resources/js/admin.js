// Admin Panel JavaScript Module
window.Admin = {
    // Modal management
    modals: {},

    init() {
        this.setupEventListeners();
    },

    setupEventListeners() {
        // Close modals on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeAllModals();
            }
        });
    },

    // Modal functions
    openModal(id, data = null) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (data) {
            this.populateForm(modal, data);
        }

        // Trigger event
        modal.dispatchEvent(new CustomEvent('modal:open', { detail: { id, data } }));
    },

    closeModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;

        modal.classList.add('hidden');
        document.body.style.overflow = '';

        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
            // Remove any hidden ID fields
            const idField = form.querySelector('input[name="id"]');
            if (idField) idField.remove();
        }

        // Trigger event
        modal.dispatchEvent(new CustomEvent('modal:close', { detail: { id } }));
    },

    closeAllModals() {
        document.querySelectorAll('[id$="-modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                this.closeModal(modal.id);
            }
        });
    },

    populateForm(modal, data) {
        const form = modal.querySelector('form');
        if (!form) return;

        // Add ID field if editing
        if (data.id) {
            let idField = form.querySelector('input[name="id"]');
            if (!idField) {
                idField = document.createElement('input');
                idField.type = 'hidden';
                idField.name = 'id';
                form.appendChild(idField);
            }
            idField.value = data.id;
        }

        // Populate form fields
        Object.keys(data).forEach(key => {
            const field = form.querySelector(`[name="${key}"]`);
            if (field) {
                if (field.type === 'checkbox') {
                    field.checked = data[key];
                } else {
                    field.value = data[key];
                }
            }
        });

        // Update modal title for editing
        const title = modal.querySelector('h2');
        if (title && data.id) {
            title.dataset.originalTitle = title.dataset.originalTitle || title.textContent;
            title.textContent = `Edit ${title.dataset.originalTitle || 'Item'}`;
        }
    },

    // Table functions
    toggleSelectAll(checkbox, tableId) {
        const selector = tableId ? `#${tableId} .selectable-item` : '.selectable-item';
        document.querySelectorAll(selector).forEach(el => {
            el.checked = checkbox.checked;
        });
    },

    getSelectedIds(tableId = null) {
        const selector = tableId ? `#${tableId} .selectable-item:checked` : '.selectable-item:checked';
        return Array.from(document.querySelectorAll(selector)).map(cb => cb.value);
    },

    // CRUD operations
    async submitForm(form, modalId, options = {}) {
        const formData = new FormData(form);
        const url = form.dataset.action || window.location.href;
        const method = form.dataset.method || (formData.get('id') ? 'PUT' : 'POST');

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        submitBtn.disabled = true;

        try {
            const response = await fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            });

            const data = await response.json();

            if (response.ok) {
                this.showNotification('Success!', options.successMessage || 'Operation completed successfully', 'success');
                this.closeModal(modalId);
                if (options.onSuccess) options.onSuccess(data);
                if (options.refresh !== false) window.location.reload();
            } else {
                throw new Error(data.message || 'Something went wrong');
            }
        } catch (error) {
            this.showNotification('Error!', error.message, 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    },

    deleteItem(id, name, options = {}) {
        const message = options.message || `Are you sure you want to delete "${name || 'this item'}"?`;

        if (confirm(message)) {
            // Show loading
            const btn = event?.target;
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.disabled = true;
            }

            fetch(options.url || window.location.href, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.showNotification('Deleted!', data.message || 'Item deleted successfully', 'success');
                        if (options.onSuccess) options.onSuccess(data);
                        if (options.refresh !== false) window.location.reload();
                    } else {
                        throw new Error(data.message || 'Failed to delete');
                    }
                })
                .catch(error => {
                    this.showNotification('Error!', error.message, 'error');
                })
                .finally(() => {
                    if (btn) {
                        btn.innerHTML = '<i class="fas fa-trash"></i> Delete';
                        btn.disabled = false;
                    }
                });
        }
    },

    // Notifications
    showNotification(title, message, type = 'success') {
        // You can replace this with a toast notification library
        alert(`${title}\n${message}`);
    },

    // Search and filter
    filterTable(inputId, filterFn) {
        const input = document.getElementById(inputId);
        if (!input) return;

        input.addEventListener('input', () => {
            const value = input.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                if (filterFn) {
                    row.style.display = filterFn(row, value) ? '' : 'none';
                } else {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(value) ? '' : 'none';
                }
            });
        });
    }
};

// Initialize on load
document.addEventListener('DOMContentLoaded', () => {
    Admin.init();

    // Make functions globally available
    window.openModal = Admin.openModal.bind(Admin);
    window.closeModal = Admin.closeModal.bind(Admin);
    window.toggleSelectAll = Admin.toggleSelectAll.bind(Admin);
    window.submitModalForm = Admin.submitForm.bind(Admin);
    window.deleteItem = Admin.deleteItem.bind(Admin);
});

// Add CSRF meta tag if not present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const meta = document.createElement('meta');
    meta.name = 'csrf-token';
    meta.content = document.querySelector('input[name="_token"]')?.value || '';
    document.head.appendChild(meta);
}