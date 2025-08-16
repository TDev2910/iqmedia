/**
 * Medicine Management JavaScript
 * Handles all functionality related to medicine products
 */

// Global variables
let currentDetailRow = null;
let currentProductId = null;

/**
 * Initialize medicine management functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeMedicineManagement();
});

/**
 * Initialize all medicine management features
 */
function initializeMedicineManagement() {
    // Initialize search functionality
    initializeSearch();
    
    // Initialize filter functionality
    initializeFilters();
    
    // Initialize modal functionality
    initializeModals();
    
    // Initialize checkbox functionality
    initializeCheckboxes();
    
    // Initialize tooltips
    initializeTooltips();
    
    console.log('Medicine management initialized');
}

/**
 * Toggle product detail row
 */
function toggleProductDetail(productId, rowElement) {
    const detailRowId = `detail-row-${productId}`;
    const detailRow = document.getElementById(detailRowId);
    
    if (!detailRow) {
        console.warn(`Detail row not found for product ${productId}`);
        return;
    }
    
    // Close currently open detail if different product
    if (currentDetailRow && currentDetailRow !== detailRow) {
        currentDetailRow.style.display = 'none';
        document.querySelector(`[onclick*="toggleProductDetail(${currentProductId}"]`)?.classList.remove('selected');
    }
    
    // Toggle current detail row
    if (detailRow.style.display === 'none' || !detailRow.style.display) {
        detailRow.style.display = 'table-row';
        rowElement.classList.add('selected');
        currentDetailRow = detailRow;
        currentProductId = productId;
        
        // Load detail content if not already loaded
        loadProductDetail(productId);
    } else {
        detailRow.style.display = 'none';
        rowElement.classList.remove('selected');
        currentDetailRow = null;
        currentProductId = null;
    }
}

/**
 * Load product detail content
 */
function loadProductDetail(productId) {
    // This would typically load data from server
    // For demo purposes, we'll just show a loading state
    console.log(`Loading details for product ${productId}`);
    
    // You can add loading spinner here
    // showDetailLoading(productId);
    
    // Simulate API call
    setTimeout(() => {
        // hideDetailLoading(productId);
        console.log(`Details loaded for product ${productId}`);
    }, 500);
}

/**
 * Search products functionality
 */
function searchProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.product-row');
    
    rows.forEach(row => {
        const productCode = row.querySelector('.product-code')?.textContent.toLowerCase() || '';
        const productName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
        
        const matches = productCode.includes(searchTerm) || productName.includes(searchTerm);
        
        row.style.display = matches ? 'table-row' : 'none';
        
        // Also hide detail row when searching
        const productId = row.dataset.productId;
        if (productId) {
            const detailRow = document.getElementById(`detail-row-${productId}`);
            if (detailRow) {
                detailRow.style.display = 'none';
            }
        }
    });
    
    // Reset current detail row
    currentDetailRow = null;
    currentProductId = null;
    
    // Remove selected class from all rows
    document.querySelectorAll('.product-row.selected').forEach(row => {
        row.classList.remove('selected');
    });
}

/**
 * Filter products functionality
 */
function filterProducts() {
    const categoryId = document.querySelector('select[name="category_id"]').value;
    const manufacturerId = document.querySelector('select[name="manufacturer_id"]').value;
    const positionId = document.querySelector('select[name="position_id"]').value;
    const productType = document.querySelector('select[name="product_type"]').value;

    const rows = document.querySelectorAll('.product-row');
    
    rows.forEach(row => {
        let showRow = true;
        
        // Filter by category
        if (categoryId && row.dataset.categoryId !== categoryId) {
            showRow = false;
        }
        
        // Filter by manufacturer (not applicable to services)
        if (manufacturerId && !row.classList.contains('service-row') && row.dataset.manufacturerId !== manufacturerId) {
            showRow = false;
        }
        
        // Filter by position (not applicable to services)
        if (positionId && !row.classList.contains('service-row') && row.dataset.positionId !== positionId) {
            showRow = false;
        }
        
        // Filter by product type
        if (productType) {
            if (productType === 'medicine' && !row.classList.contains('medicine-row')) {
                showRow = false;
            } else if (productType === 'goods' && !row.classList.contains('goods-row')) {
                showRow = false;
            } else if (productType === 'service' && !row.classList.contains('service-row')) {
                showRow = false;
            }
        }
        
        // Show/hide row
        row.style.display = showRow ? 'table-row' : 'none';
        
        // Also hide detail row if main row is hidden
        const productId = row.dataset.productId;
        if (productId) {
            const detailRow = document.getElementById(`detail-row-${productId}`);
            if (detailRow) {
                detailRow.style.display = showRow ? 'none' : 'none'; // Always hide detail when filtering
            }
        }
    });
    
    // Reset current detail row
    currentDetailRow = null;
    currentProductId = null;
    
    // Remove selected class from all rows
    document.querySelectorAll('.product-row.selected').forEach(row => {
        row.classList.remove('selected');
    });
}

/**
 * Show delete confirmation modal
 */
function showDeleteConfirmation(medicineId, medicineCode, medicineName) {
    // Update modal content
    document.getElementById('deleteMedicineCode').textContent = medicineCode;
    document.getElementById('deleteMedicineName').textContent = medicineName;
    
    // Store medicine ID for deletion
    window.currentDeleteId = medicineId;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

/**
 * Confirm delete action
 */
function confirmDelete() {
    if (!window.currentDeleteId) {
        console.error('No product ID found for deletion');
        return;
    }
    
    // Here you would typically make an API call to delete the product
    console.log(`Deleting product with ID: ${window.currentDeleteId}`);
    
    // For demo purposes, just remove the row from the table
    const productRow = document.querySelector(`[data-product-id="${window.currentDeleteId}"]`);
    const detailRow = document.getElementById(`detail-row-${window.currentDeleteId}`);
    
    if (productRow) {
        productRow.remove();
    }
    
    if (detailRow) {
        detailRow.remove();
    }
    
    // Hide modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
    modal.hide();
    
    // Show success message
    showAlert('success', 'Đã xóa sản phẩm thành công!');
    
    // Reset current detail row
    currentDetailRow = null;
    currentProductId = null;
    window.currentDeleteId = null;
}

/**
 * Open edit medicine modal
 */
function openEditMedicineModal(medicineId) {
    // Here you would typically load medicine data and populate the form
    console.log(`Opening edit modal for medicine ${medicineId}`);
    
    // For demo purposes, just show an alert
    showAlert('info', `Chức năng chỉnh sửa thuốc ${medicineId} sẽ được triển khai`);
}

/**
 * Print label functionality
 */
function printLabel(productId) {
    console.log(`Printing label for product ${productId}`);
    
    // For demo purposes, just show an alert
    showAlert('info', `Chức năng in tem mã cho sản phẩm ${productId} sẽ được triển khai`);
}

/**
 * Open unit modal
 */
function openUnitModal(productId) {
    console.log(`Opening unit modal for product ${productId}`);
    
    // For demo purposes, just show an alert
    showAlert('info', `Chức năng thiết lập đơn vị tính cho sản phẩm ${productId} sẽ được triển khai`);
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        // Add debounce to search
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchProducts();
            }, 300);
        });
    }
}

/**
 * Initialize filter functionality
 */
function initializeFilters() {
    const filterSelects = document.querySelectorAll('select[name="category_id"], select[name="manufacturer_id"], select[name="position_id"], select[name="product_type"]');
    
    filterSelects.forEach(select => {
        select.addEventListener('change', filterProducts);
    });
}

/**
 * Initialize modal functionality
 */
function initializeModals() {
    // Initialize category creation modal
    const createCategoryForm = document.querySelector('#createCategoryModal form');
    if (createCategoryForm) {
        createCategoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleCreateCategory(this);
        });
    }
}

/**
 * Initialize checkbox functionality
 */
function initializeCheckboxes() {
    // Master checkbox functionality
    const masterCheckbox = document.querySelector('thead input[type="checkbox"]');
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // Individual checkbox functionality
    document.addEventListener('change', function(e) {
        if (e.target.matches('tbody input[type="checkbox"]')) {
            updateMasterCheckbox();
        }
    });
}

/**
 * Update master checkbox state
 */
function updateMasterCheckbox() {
    const masterCheckbox = document.querySelector('thead input[type="checkbox"]');
    const checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    const checkedCount = document.querySelectorAll('tbody input[type="checkbox"]:checked').length;
    
    if (masterCheckbox) {
        if (checkedCount === 0) {
            masterCheckbox.indeterminate = false;
            masterCheckbox.checked = false;
        } else if (checkedCount === checkboxes.length) {
            masterCheckbox.indeterminate = false;
            masterCheckbox.checked = true;
        } else {
            masterCheckbox.indeterminate = true;
        }
    }
}

/**
 * Initialize tooltips
 */
function initializeTooltips() {
    // Initialize Bootstrap tooltips if available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
}

/**
 * Handle create category form submission
 */
function handleCreateCategory(form) {
    const formData = new FormData(form);
    const categoryName = formData.get('name');
    const parentId = formData.get('parent_id');
    
    console.log('Creating category:', { name: categoryName, parentId });
    
    // Here you would typically make an API call
    // For demo purposes, just show success message
    showAlert('success', `Đã tạo nhóm hàng "${categoryName}" thành công!`);
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('createCategoryModal'));
    modal.hide();
    
    // Reset form
    form.reset();
    
    // Add new option to category select
    const categorySelect = document.querySelector('select[name="category_id"]');
    if (categorySelect) {
        const newOption = document.createElement('option');
        newOption.value = Date.now(); // Use timestamp as fake ID
        newOption.textContent = categoryName;
        categorySelect.appendChild(newOption);
    }
}

/**
 * Show alert message
 */
function showAlert(type, message) {
    const alertContainer = type === 'success' ? 
        document.getElementById('successAlert') : 
        document.getElementById('errorAlert');
    
    const messageSpan = type === 'success' ? 
        document.getElementById('successMessage') : 
        document.getElementById('errorMessage');
    
    if (alertContainer && messageSpan) {
        messageSpan.textContent = message;
        alertContainer.style.display = 'block';
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 5000);
    }
}

/**
 * Hide alert message
 */
function hideAlert(type) {
    const alertContainer = type === 'success' ? 
        document.getElementById('successAlert') : 
        document.getElementById('errorAlert');
    
    if (alertContainer) {
        alertContainer.style.display = 'none';
    }
}

/**
 * Utility function to format currency
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Utility function to format date
 */
function formatDate(date) {
    return new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    }).format(new Date(date));
}

/**
 * Export selected products (placeholder)
 */
function exportProducts() {
    const checkedBoxes = document.querySelectorAll('tbody input[type="checkbox"]:checked');
    console.log(`Exporting ${checkedBoxes.length} products`);
    
    showAlert('info', `Chức năng xuất ${checkedBoxes.length} sản phẩm sẽ được triển khai`);
}

/**
 * Import products (placeholder)
 */
function importProducts() {
    console.log('Opening import dialog');
    
    showAlert('info', 'Chức năng import sản phẩm sẽ được triển khai');
}

// Make functions globally available
window.toggleProductDetail = toggleProductDetail;
window.searchProducts = searchProducts;
window.filterProducts = filterProducts;
window.showDeleteConfirmation = showDeleteConfirmation;
window.confirmDelete = confirmDelete;
window.openEditMedicineModal = openEditMedicineModal;
window.printLabel = printLabel;
window.openUnitModal = openUnitModal;
window.exportProducts = exportProducts;
window.importProducts = importProducts;