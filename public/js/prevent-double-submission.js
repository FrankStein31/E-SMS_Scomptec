/**
 * Prevent Double Submission Helper
 * 
 * This script prevents users from submitting forms multiple times
 * and provides visual feedback during submission process.
 */

class PreventDoubleSubmission {
    constructor() {
        this.submittingForms = new Set();
        this.submittingButtons = new Map();
        this.init();
    }

    init() {
        // Auto-initialize when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setupFormProtection());
        } else {
            this.setupFormProtection();
        }
    }

    setupFormProtection() {
        // Protect all forms with CRUD operations
        this.protectForms();
        
        // Protect AJAX submissions
        this.protectAjaxSubmissions();
        
        // Protect button clicks
        this.protectButtons();
    }

    protectForms() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                if (this.isFormSubmitting(form)) {
                    e.preventDefault();
                    this.showWarning('Form sedang diproses. Mohon tunggu...');
                    return false;
                }
                
                this.setFormSubmitting(form, true);
                this.disableFormElements(form);
                
                // Re-enable form after 3 seconds as fallback
                setTimeout(() => {
                    this.setFormSubmitting(form, false);
                    this.enableFormElements(form);
                }, 3000);
            });
        });
    }

    protectAjaxSubmissions() {
        // Override jQuery ajax if available
        if (typeof $ !== 'undefined' && $.ajaxSetup) {
            const originalAjax = $.ajax;
            const self = this;
            
            $.ajax = function(options) {
                // Skip protection for DataTables AJAX requests
                if (self.isDataTablesRequest(options)) {
                    return originalAjax.call(this, options);
                }
                
                // Skip protection for GET requests (read-only operations)
                if (options.type && options.type.toUpperCase() === 'GET') {
                    return originalAjax.call(this, options);
                }
                
                // Create unique identifier for this request
                const requestId = self.generateRequestId(options);
                
                if (self.submittingForms.has(requestId)) {
                    self.showWarning('Permintaan sedang diproses. Mohon tunggu...');
                    return $.Deferred().reject().promise();
                }
                
                self.submittingForms.add(requestId);
                
                // Disable associated buttons
                const associatedButton = self.findAssociatedButton(options);
                if (associatedButton) {
                    self.disableButton(associatedButton);
                }
                
                const originalSuccess = options.success || function() {};
                const originalError = options.error || function() {};
                const originalComplete = options.complete || function() {};
                
                options.success = function(...args) {
                    self.submittingForms.delete(requestId);
                    if (associatedButton) {
                        self.enableButton(associatedButton);
                    }
                    originalSuccess.apply(this, args);
                };
                
                options.error = function(...args) {
                    self.submittingForms.delete(requestId);
                    if (associatedButton) {
                        self.enableButton(associatedButton);
                    }
                    originalError.apply(this, args);
                };
                
                options.complete = function(...args) {
                    self.submittingForms.delete(requestId);
                    if (associatedButton) {
                        self.enableButton(associatedButton);
                    }
                    originalComplete.apply(this, args);
                };
                
                return originalAjax.call(this, options);
            };
        }
    }

    protectButtons() {
        // Protect buttons with data-prevent-double attribute
        const buttons = document.querySelectorAll('[data-prevent-double], .btn-submit, button[type="submit"]');
        
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                if (this.isButtonSubmitting(button)) {
                    e.preventDefault();
                    this.showWarning('Sedang memproses. Mohon tunggu...');
                    return false;
                }
                
                // For submit buttons, let the form handle the submission
                if (button.type === 'submit') {
                    return true;
                }
                
                this.disableButton(button);
                
                // Re-enable after 2 seconds as fallback
                setTimeout(() => {
                    this.enableButton(button);
                }, 2000);
            });
        });
    }

    isFormSubmitting(form) {
        return this.submittingForms.has(this.getFormId(form));
    }

    setFormSubmitting(form, submitting) {
        const formId = this.getFormId(form);
        if (submitting) {
            this.submittingForms.add(formId);
        } else {
            this.submittingForms.delete(formId);
        }
    }

    isButtonSubmitting(button) {
        return this.submittingButtons.has(button);
    }

    disableButton(button) {
        if (!button || button.disabled) return;
        
        const originalText = button.innerHTML;
        const originalDisabled = button.disabled;
        
        this.submittingButtons.set(button, {
            originalText,
            originalDisabled
        });
        
        button.disabled = true;
        
        // Add loading text/spinner
        if (button.type === 'submit') {
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
        } else {
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Tunggu...';
        }
        
        button.classList.add('btn-loading');
    }

    enableButton(button) {
        if (!button || !this.submittingButtons.has(button)) return;
        
        const originalState = this.submittingButtons.get(button);
        button.innerHTML = originalState.originalText;
        button.disabled = originalState.originalDisabled;
        button.classList.remove('btn-loading');
        
        this.submittingButtons.delete(button);
    }

    disableFormElements(form) {
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(button => this.disableButton(button));
    }

    enableFormElements(form) {
        const submitButtons = form.querySelectorAll('button[type="submit"], input[type="submit"]');
        submitButtons.forEach(button => this.enableButton(button));
    }

    getFormId(form) {
        return form.id || form.action || 'form-' + Array.from(document.forms).indexOf(form);
    }

    generateRequestId(options) {
        const url = options.url || '';
        const type = options.type || 'GET';
        const data = typeof options.data === 'string' ? options.data : JSON.stringify(options.data || {});
        return `${type}-${url}-${btoa(data).substr(0, 20)}`;
    }

    findAssociatedButton(options) {
        // Try to find the button that triggered this AJAX request
        const activeElement = document.activeElement;
        if (activeElement && (activeElement.tagName === 'BUTTON' || activeElement.tagName === 'INPUT')) {
            return activeElement;
        }
        return null;
    }

    isDataTablesRequest(options) {
        // Check if this is a DataTables AJAX request
        const url = options.url || '';
        const data = options.data || {};
        
        // Check for DataTables parameters
        if (typeof data === 'object' && (
            data.hasOwnProperty('draw') ||
            data.hasOwnProperty('start') ||
            data.hasOwnProperty('length') ||
            data.hasOwnProperty('search') ||
            data.hasOwnProperty('order')
        )) {
            return true;
        }
        
        // Check for DataTables URL patterns
        if (url.includes('datatables') || url.includes('ajax')) {
            return true;
        }
        
        // Check if request originated from DataTables search
        if (typeof data === 'string' && (
            data.includes('draw=') ||
            data.includes('start=') ||
            data.includes('length=') ||
            data.includes('search%5Bvalue%5D=')
        )) {
            return true;
        }
        
        return false;
    }

    showWarning(message) {
        // Try different notification methods
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: message,
                timer: 2000,
                showConfirmButton: false
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.warning(message);
        } else {
            alert(message);
        }
    }

    // Public methods for manual control
    manualDisableButton(selector) {
        const button = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (button) this.disableButton(button);
    }

    manualEnableButton(selector) {
        const button = typeof selector === 'string' ? document.querySelector(selector) : selector;
        if (button) this.enableButton(button);
    }

    resetForm(formSelector) {
        const form = typeof formSelector === 'string' ? document.querySelector(formSelector) : formSelector;
        if (form) {
            this.setFormSubmitting(form, false);
            this.enableFormElements(form);
        }
    }
}

// Auto-initialize
const preventDoubleSubmission = new PreventDoubleSubmission();

// Add to global scope for manual access
window.PreventDoubleSubmission = preventDoubleSubmission;

// Add CSS for loading buttons
const style = document.createElement('style');
style.textContent = `
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading .spinner-border-sm {
        width: 0.75rem;
        height: 0.75rem;
    }
    
    .double-click-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.3);
        z-index: 9999;
        display: none;
        justify-content: center;
        align-items: center;
        color: white;
        font-size: 1.2rem;
    }
`;
document.head.appendChild(style);
