/**
 * Forms JavaScript
 * General form handling and utility functions
 */

/**
 * Initialize form functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeForms();
});

/**
 * Initialize all form features
 */
function initializeForms() {
    // Initialize form validation
    initializeFormValidation();
    
    // Initialize file upload handling
    initializeFileUploads();
    
    // Initialize dynamic form fields
    initializeDynamicFields();
    
    // Initialize form submission handling
    initializeFormSubmissions();
    
    console.log('Forms initialized');
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    // Add real-time validation to all forms
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        // Add validation on input change
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Remove error state on input
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                }
            });
        });
        
        // Add validation on form submit
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
}

/**
 * Validate a single field
 */
function validateField(field) {
    let isValid = true;
    const value = field.value.trim();
    
    // Check required fields
    if (field.hasAttribute('required') && !value) {
        isValid = false;
        showFieldError(field, 'Trường này là bắt buộc');
    }
    
    // Check email format
    if (field.type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            isValid = false;
            showFieldError(field, 'Định dạng email không hợp lệ');
        }
    }
    
    // Check phone format
    if (field.type === 'tel' && value) {
        const phoneRegex = /^[0-9+\-\s()]+$/;
        if (!phoneRegex.test(value)) {
            isValid = false;
            showFieldError(field, 'Định dạng số điện thoại không hợp lệ');
        }
    }
    
    // Check number fields
    if (field.type === 'number' && value) {
        const min = field.getAttribute('min');
        const max = field.getAttribute('max');
        const numValue = parseFloat(value);
        
        if (min && numValue < parseFloat(min)) {
            isValid = false;
            showFieldError(field, `Giá trị phải lớn hơn hoặc bằng ${min}`);
        }
        
        if (max && numValue > parseFloat(max)) {
            isValid = false;
            showFieldError(field, `Giá trị phải nhỏ hơn hoặc bằng ${max}`);
        }
    }
    
    // Check password confirmation
    if (field.name === 'password_confirmation') {
        const passwordField = field.form.querySelector('[name="password"]');
        if (passwordField && value !== passwordField.value) {
            isValid = false;
            showFieldError(field, 'Mật khẩu xác nhận không khớp');
        }
    }
    
    if (isValid) {
        hideFieldError(field);
    }
    
    return isValid;
}

/**
 * Validate entire form
 */
function validateForm(form) {
    const fields = form.querySelectorAll('input, select, textarea');
    let isFormValid = true;
    
    fields.forEach(field => {
        if (!validateField(field)) {
            isFormValid = false;
        }
    });
    
    return isFormValid;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.invalid-feedback');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorElement = document.createElement('div');
    errorElement.className = 'invalid-feedback';
    errorElement.textContent = message;
    field.parentNode.appendChild(errorElement);
}

/**
 * Hide field error
 */
function hideFieldError(field) {
    field.classList.remove('is-invalid');
    
    const errorElement = field.parentNode.querySelector('.invalid-feedback');
    if (errorElement) {
        errorElement.remove();
    }
}

/**
 * Initialize file upload handling
 */
function initializeFileUploads() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            handleFileUpload(this);
        });
    });
}

/**
 * Handle file upload
 */
function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;
    
    // Validate file type
    const allowedTypes = input.getAttribute('accept');
    if (allowedTypes) {
        const fileType = file.type;
        const allowedTypesArray = allowedTypes.split(',').map(type => type.trim());
        
        if (!allowedTypesArray.some(type => {
            if (type.startsWith('.')) {
                return file.name.toLowerCase().endsWith(type.toLowerCase());
            }
            return fileType.match(type.replace('*', '.*'));
        })) {
            showFieldError(input, 'Loại file không được hỗ trợ');
            input.value = '';
            return;
        }
    }
    
    // Validate file size (default 5MB)
    const maxSize = parseInt(input.dataset.maxSize) || 5 * 1024 * 1024;
    if (file.size > maxSize) {
        showFieldError(input, `Kích thước file không được vượt quá ${formatFileSize(maxSize)}`);
        input.value = '';
        return;
    }
    
    // Show file preview if it's an image
    if (file.type.startsWith('image/')) {
        showImagePreview(input, file);
    }
    
    hideFieldError(input);
}

/**
 * Show image preview
 */
function showImagePreview(input, file) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        // Find or create preview container
        let previewContainer = input.parentNode.querySelector('.image-preview');
        if (!previewContainer) {
            previewContainer = document.createElement('div');
            previewContainer.className = 'image-preview mt-2';
            input.parentNode.appendChild(previewContainer);
        }
        
        previewContainer.innerHTML = `
            <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeImagePreview(this)">
                <i class="fas fa-times"></i> Xóa
            </button>
        `;
    };
    
    reader.readAsDataURL(file);
}

/**
 * Remove image preview
 */
function removeImagePreview(button) {
    const previewContainer = button.parentNode;
    const input = previewContainer.parentNode.querySelector('input[type="file"]');
    
    if (input) {
        input.value = '';
    }
    
    previewContainer.remove();
}

/**
 * Format file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Initialize dynamic form fields
 */
function initializeDynamicFields() {
    // Handle cascading selects
    initializeCascadingSelects();
    
    // Handle conditional fields
    initializeConditionalFields();
    
    // Handle repeatable fields
    initializeRepeatableFields();
}

/**
 * Initialize cascading selects
 */
function initializeCascadingSelects() {
    const cascadingSelects = document.querySelectorAll('[data-cascade-target]');
    
    cascadingSelects.forEach(select => {
        select.addEventListener('change', function() {
            const targetSelector = this.dataset.cascadeTarget;
            const targetSelect = document.querySelector(targetSelector);
            
            if (targetSelect) {
                updateCascadingSelect(this, targetSelect);
            }
        });
    });
}

/**
 * Update cascading select options
 */
function updateCascadingSelect(sourceSelect, targetSelect) {
    const selectedValue = sourceSelect.value;
    
    // Clear target select
    targetSelect.innerHTML = '<option value="">Chọn...</option>';
    
    if (!selectedValue) return;
    
    // Here you would typically make an API call to get the options
    // For demo purposes, we'll just show a loading state
    targetSelect.innerHTML = '<option value="">Đang tải...</option>';
    
    // Simulate API call
    setTimeout(() => {
        // This would be replaced with actual API data
        const demoOptions = [
            { value: '1', text: 'Tùy chọn 1' },
            { value: '2', text: 'Tùy chọn 2' },
            { value: '3', text: 'Tùy chọn 3' }
        ];
        
        targetSelect.innerHTML = '<option value="">Chọn...</option>';
        demoOptions.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = option.value;
            optionElement.textContent = option.text;
            targetSelect.appendChild(optionElement);
        });
    }, 500);
}

/**
 * Initialize conditional fields
 */
function initializeConditionalFields() {
    const conditionalTriggers = document.querySelectorAll('[data-conditional-target]');
    
    conditionalTriggers.forEach(trigger => {
        trigger.addEventListener('change', function() {
            handleConditionalField(this);
        });
        
        // Initialize on page load
        handleConditionalField(trigger);
    });
}

/**
 * Handle conditional field visibility
 */
function handleConditionalField(trigger) {
    const targetSelector = trigger.dataset.conditionalTarget;
    const showValue = trigger.dataset.conditionalValue;
    const targetElement = document.querySelector(targetSelector);
    
    if (!targetElement) return;
    
    const currentValue = trigger.type === 'checkbox' ? trigger.checked : trigger.value;
    const shouldShow = showValue ? currentValue == showValue : currentValue;
    
    if (shouldShow) {
        targetElement.style.display = 'block';
        // Enable required validation if needed
        const requiredFields = targetElement.querySelectorAll('[data-required-when-visible]');
        requiredFields.forEach(field => {
            field.setAttribute('required', 'required');
        });
    } else {
        targetElement.style.display = 'none';
        // Disable required validation
        const requiredFields = targetElement.querySelectorAll('[data-required-when-visible]');
        requiredFields.forEach(field => {
            field.removeAttribute('required');
            hideFieldError(field);
        });
    }
}

/**
 * Initialize repeatable fields
 */
function initializeRepeatableFields() {
    const repeatableContainers = document.querySelectorAll('[data-repeatable]');
    
    repeatableContainers.forEach(container => {
        const addButton = container.querySelector('[data-repeatable-add]');
        if (addButton) {
            addButton.addEventListener('click', function() {
                addRepeatableField(container);
            });
        }
    });
    
    // Handle remove buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-repeatable-remove]')) {
            removeRepeatableField(e.target);
        }
    });
}

/**
 * Add repeatable field
 */
function addRepeatableField(container) {
    const template = container.querySelector('[data-repeatable-template]');
    if (!template) return;
    
    const clone = template.cloneNode(true);
    clone.removeAttribute('data-repeatable-template');
    clone.style.display = 'block';
    
    // Update field names and IDs
    const fields = clone.querySelectorAll('[name]');
    const index = container.querySelectorAll('[data-repeatable-item]').length;
    
    fields.forEach(field => {
        const name = field.getAttribute('name');
        const id = field.getAttribute('id');
        
        if (name) {
            field.setAttribute('name', name.replace(/\[\d*\]/, `[${index}]`));
        }
        
        if (id) {
            field.setAttribute('id', id.replace(/\d+$/, index));
        }
        
        // Clear values
        if (field.type !== 'hidden') {
            field.value = '';
        }
    });
    
    // Add to container
    clone.setAttribute('data-repeatable-item', '');
    container.appendChild(clone);
}

/**
 * Remove repeatable field
 */
function removeRepeatableField(button) {
    const item = button.closest('[data-repeatable-item]');
    if (item) {
        item.remove();
    }
}

/**
 * Initialize form submission handling
 */
function initializeFormSubmissions() {
    // Handle AJAX form submissions
    const ajaxForms = document.querySelectorAll('[data-ajax-form]');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            handleAjaxFormSubmission(this);
        });
    });
}

/**
 * Handle AJAX form submission
 */
function handleAjaxFormSubmission(form) {
    if (!validateForm(form)) {
        return;
    }
    
    const submitButton = form.querySelector('[type="submit"]');
    const originalText = submitButton.textContent;
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
    
    const formData = new FormData(form);
    const url = form.action || window.location.href;
    const method = form.method || 'POST';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof showAlert === 'function') {
                showAlert('success', data.message || 'Thao tác thành công!');
            }
            
            // Reset form if specified
            if (data.reset_form) {
                form.reset();
            }
            
            // Redirect if specified
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            }
            
            // Close modal if form is in modal
            const modal = form.closest('.modal');
            if (modal) {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            }
        } else {
            if (typeof showAlert === 'function') {
                showAlert('error', data.message || 'Có lỗi xảy ra!');
            }
            
            // Show field errors
            if (data.errors) {
                Object.keys(data.errors).forEach(fieldName => {
                    const field = form.querySelector(`[name="${fieldName}"]`);
                    if (field) {
                        showFieldError(field, data.errors[fieldName][0]);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        if (typeof showAlert === 'function') {
            showAlert('error', 'Có lỗi xảy ra khi gửi form!');
        }
    })
    .finally(() => {
        // Restore button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
}

/**
 * Reset form with validation cleanup
 */
function resetFormWithValidation(form) {
    form.reset();
    
    // Clear all validation states
    const fields = form.querySelectorAll('.is-invalid');
    fields.forEach(field => {
        hideFieldError(field);
    });
    
    // Clear image previews
    const previews = form.querySelectorAll('.image-preview');
    previews.forEach(preview => {
        preview.remove();
    });
}

/**
 * Serialize form data to JSON
 */
function serializeFormToJSON(form) {
    const formData = new FormData(form);
    const json = {};
    
    for (let [key, value] of formData.entries()) {
        if (json[key]) {
            if (!Array.isArray(json[key])) {
                json[key] = [json[key]];
            }
            json[key].push(value);
        } else {
            json[key] = value;
        }
    }
    
    return json;
}

// Make functions globally available
window.removeImagePreview = removeImagePreview;
window.validateForm = validateForm;
window.validateField = validateField;
window.resetFormWithValidation = resetFormWithValidation;
window.serializeFormToJSON = serializeFormToJSON;
window.handleAjaxFormSubmission = handleAjaxFormSubmission;