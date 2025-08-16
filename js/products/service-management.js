/**
 * Service Management JavaScript
 * Handles all functionality related to service products
 */

/**
 * Toggle service detail row
 */
function toggleServiceDetail(serviceId, rowElement) {
    const detailRowId = `detail-row-service-${serviceId}`;
    const detailRow = document.getElementById(detailRowId);
    
    if (!detailRow) {
        console.warn(`Detail row not found for service ${serviceId}`);
        return;
    }
    
    // Close currently open detail if different product
    if (window.currentDetailRow && window.currentDetailRow !== detailRow) {
        window.currentDetailRow.style.display = 'none';
        document.querySelector(`[onclick*="toggleServiceDetail(${window.currentProductId}"]`)?.classList.remove('selected');
    }
    
    // Toggle current detail row
    if (detailRow.style.display === 'none' || !detailRow.style.display) {
        detailRow.style.display = 'table-row';
        rowElement.classList.add('selected');
        window.currentDetailRow = detailRow;
        window.currentProductId = serviceId;
        
        // Load detail content if not already loaded
        loadServiceDetail(serviceId);
    } else {
        detailRow.style.display = 'none';
        rowElement.classList.remove('selected');
        window.currentDetailRow = null;
        window.currentProductId = null;
    }
}

/**
 * Load service detail content
 */
function loadServiceDetail(serviceId) {
    console.log(`Loading details for service ${serviceId}`);
    
    // Simulate API call
    setTimeout(() => {
        console.log(`Details loaded for service ${serviceId}`);
    }, 500);
}

/**
 * Show delete service confirmation
 */
function showDeleteServiceConfirmation(serviceId, serviceCode, serviceName) {
    // Update modal content
    document.getElementById('deleteMedicineCode').textContent = serviceCode;
    document.getElementById('deleteMedicineName').textContent = serviceName;
    
    // Store service ID for deletion
    window.currentDeleteId = `service-${serviceId}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

/**
 * Open edit service modal
 */
function openEditServiceModal(serviceId) {
    console.log(`Opening edit modal for service ${serviceId}`);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Chức năng chỉnh sửa dịch vụ ${serviceId} sẽ được triển khai`);
    } else {
        alert(`Chức năng chỉnh sửa dịch vụ ${serviceId} sẽ được triển khai`);
    }
}

/**
 * View service detail in modal (if needed)
 */
function viewServiceDetail(serviceId) {
    console.log(`Viewing service detail ${serviceId}`);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Chi tiết dịch vụ ${serviceId} sẽ được hiển thị`);
    } else {
        alert(`Chi tiết dịch vụ ${serviceId} sẽ được hiển thị`);
    }
}

/**
 * Toggle service status
 */
function toggleServiceStatus(serviceId, currentStatus) {
    const newStatus = currentStatus === 'kich_hoat' ? 'tam_ngung' : 'kich_hoat';
    
    console.log(`Changing service ${serviceId} status from ${currentStatus} to ${newStatus}`);
    
    // Here you would typically make an API call
    // For demo purposes, just update the badge
    const serviceRow = document.querySelector(`[data-product-id="service-${serviceId}"]`);
    if (serviceRow) {
        const statusBadge = serviceRow.querySelector('.badge');
        if (statusBadge) {
            if (newStatus === 'kich_hoat') {
                statusBadge.className = 'badge bg-success';
                statusBadge.textContent = 'Kích hoạt';
            } else {
                statusBadge.className = 'badge bg-warning';
                statusBadge.textContent = 'Tạm ngưng';
            }
        }
    }
    
    if (typeof showAlert === 'function') {
        const statusText = newStatus === 'kich_hoat' ? 'kích hoạt' : 'tạm ngưng';
        showAlert('success', `Đã ${statusText} dịch vụ thành công!`);
    }
}

/**
 * Initialize service management functionality
 */
function initializeServiceManagement() {
    console.log('Service management initialized');
    
    // Add any service-specific initialization here
    initializeServiceEvents();
}

/**
 * Initialize service-specific events
 */
function initializeServiceEvents() {
    // Add event listeners for service-specific functionality
    document.addEventListener('click', function(e) {
        // Handle service-specific clicks
        if (e.target.matches('.service-action-btn')) {
            const action = e.target.dataset.action;
            const serviceId = e.target.dataset.serviceId;
            
            switch (action) {
                case 'edit':
                    openEditServiceModal(serviceId);
                    break;
                case 'delete':
                    const serviceCode = e.target.dataset.serviceCode;
                    const serviceName = e.target.dataset.serviceName;
                    showDeleteServiceConfirmation(serviceId, serviceCode, serviceName);
                    break;
                case 'view':
                    viewServiceDetail(serviceId);
                    break;
                case 'toggle-status':
                    const currentStatus = e.target.dataset.currentStatus;
                    toggleServiceStatus(serviceId, currentStatus);
                    break;
                default:
                    console.log(`Unknown action: ${action}`);
            }
        }
    });
}

/**
 * Handle service form validation
 */
function validateServiceForm(form) {
    const requiredFields = ['ten_dich_vu', 'ma_dich_vu', 'gia_ban', 'hinh_thuc'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const input = form.querySelector(`[name="${field}"]`);
        if (input && !input.value.trim()) {
            isValid = false;
            input.classList.add('is-invalid');
        } else if (input) {
            input.classList.remove('is-invalid');
        }
    });
    
    return isValid;
}

/**
 * Handle create service form submission
 */
function handleCreateService(form) {
    if (!validateServiceForm(form)) {
        if (typeof showAlert === 'function') {
            showAlert('error', 'Vui lòng điền đầy đủ thông tin bắt buộc');
        }
        return false;
    }
    
    const formData = new FormData(form);
    const serviceData = {
        ten_dich_vu: formData.get('ten_dich_vu'),
        ma_dich_vu: formData.get('ma_dich_vu'),
        gia_ban: formData.get('gia_ban'),
        hinh_thuc: formData.get('hinh_thuc'),
        thoi_gian_thuc_hien: formData.get('thoi_gian_thuc_hien'),
        mo_ta: formData.get('mo_ta'),
        ghi_chu: formData.get('ghi_chu')
    };
    
    console.log('Creating service:', serviceData);
    
    // Here you would typically make an API call
    // For demo purposes, just show success message
    if (typeof showAlert === 'function') {
        showAlert('success', `Đã tạo dịch vụ "${serviceData.ten_dich_vu}" thành công!`);
    }
    
    // Close modal if it exists
    const modal = document.getElementById('createServiceModal');
    if (modal) {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }
    
    // Reset form
    form.reset();
    
    return true;
}

/**
 * Handle edit service form submission
 */
function handleEditService(form, serviceId) {
    if (!validateServiceForm(form)) {
        if (typeof showAlert === 'function') {
            showAlert('error', 'Vui lòng điền đầy đủ thông tin bắt buộc');
        }
        return false;
    }
    
    const formData = new FormData(form);
    const serviceData = {
        id: serviceId,
        ten_dich_vu: formData.get('ten_dich_vu'),
        ma_dich_vu: formData.get('ma_dich_vu'),
        gia_ban: formData.get('gia_ban'),
        hinh_thuc: formData.get('hinh_thuc'),
        thoi_gian_thuc_hien: formData.get('thoi_gian_thuc_hien'),
        mo_ta: formData.get('mo_ta'),
        ghi_chu: formData.get('ghi_chu')
    };
    
    console.log('Updating service:', serviceData);
    
    // Here you would typically make an API call
    // For demo purposes, just show success message
    if (typeof showAlert === 'function') {
        showAlert('success', `Đã cập nhật dịch vụ "${serviceData.ten_dich_vu}" thành công!`);
    }
    
    // Close modal if it exists
    const modal = document.getElementById('editServiceModal');
    if (modal) {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }
    
    return true;
}

/**
 * Search services specifically
 */
function searchServices(searchTerm) {
    const serviceRows = document.querySelectorAll('.service-row');
    
    serviceRows.forEach(row => {
        const serviceCode = row.querySelector('.product-code')?.textContent.toLowerCase() || '';
        const serviceName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
        
        const matches = serviceCode.includes(searchTerm.toLowerCase()) || 
                       serviceName.includes(searchTerm.toLowerCase());
        
        row.style.display = matches ? 'table-row' : 'none';
    });
}

/**
 * Filter services by category
 */
function filterServicesByCategory(categoryId) {
    const serviceRows = document.querySelectorAll('.service-row');
    
    serviceRows.forEach(row => {
        if (!categoryId || row.dataset.categoryId === categoryId) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Filter services by status
 */
function filterServicesByStatus(status) {
    const serviceRows = document.querySelectorAll('.service-row');
    
    serviceRows.forEach(row => {
        const statusBadge = row.querySelector('.badge');
        const currentStatus = statusBadge?.textContent.trim();
        
        if (!status || 
            (status === 'kich_hoat' && currentStatus === 'Kích hoạt') ||
            (status === 'tam_ngung' && currentStatus === 'Tạm ngưng') ||
            (status === 'luu_tam' && currentStatus === 'Lưu tạm')) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Filter services by form type
 */
function filterServicesByForm(formType) {
    const serviceRows = document.querySelectorAll('.service-row');
    
    serviceRows.forEach(row => {
        const formText = row.cells[4]?.textContent.trim();
        
        if (!formType || 
            (formType === 'tai_nha_thuoc' && formText === 'Tại NT') ||
            (formType === 'tai_nha_khach' && formText === 'Tại nhà')) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Get service statistics
 */
function getServiceStatistics() {
    const serviceRows = document.querySelectorAll('.service-row:not([style*="display: none"])');
    const totalServices = serviceRows.length;
    let totalRevenue = 0;
    let activeServices = 0;
    let pausedServices = 0;
    
    serviceRows.forEach(row => {
        const priceText = row.cells[5]?.textContent || '0';
        const price = parseInt(priceText.replace(/[^\d]/g, '')) || 0;
        totalRevenue += price;
        
        const statusBadge = row.querySelector('.badge');
        const status = statusBadge?.textContent.trim();
        
        if (status === 'Kích hoạt') {
            activeServices++;
        } else if (status === 'Tạm ngưng') {
            pausedServices++;
        }
    });
    
    return {
        totalServices,
        activeServices,
        pausedServices,
        totalRevenue,
        averagePrice: totalServices > 0 ? totalRevenue / totalServices : 0
    };
}

/**
 * Export service data
 */
function exportServiceData() {
    const serviceRows = document.querySelectorAll('.service-row:not([style*="display: none"])');
    const serviceData = [];
    
    serviceRows.forEach(row => {
        const cells = row.cells;
        const statusBadge = row.querySelector('.badge');
        
        serviceData.push({
            ma_dich_vu: cells[2]?.textContent?.trim() || '',
            ten_dich_vu: cells[3]?.textContent?.trim() || '',
            hinh_thuc: cells[4]?.textContent?.trim() || '',
            gia_ban: cells[5]?.textContent?.trim() || '',
            trang_thai: statusBadge?.textContent?.trim() || '',
            thoi_gian_tao: cells[8]?.textContent?.trim() || ''
        });
    });
    
    console.log('Exporting service data:', serviceData);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Đã xuất ${serviceData.length} dịch vụ`);
    }
    
    return serviceData;
}

/**
 * Bulk update service status
 */
function bulkUpdateServiceStatus(serviceIds, newStatus) {
    console.log(`Bulk updating ${serviceIds.length} services to status: ${newStatus}`);
    
    serviceIds.forEach(serviceId => {
        const serviceRow = document.querySelector(`[data-product-id="service-${serviceId}"]`);
        if (serviceRow) {
            const statusBadge = serviceRow.querySelector('.badge');
            if (statusBadge) {
                switch (newStatus) {
                    case 'kich_hoat':
                        statusBadge.className = 'badge bg-success';
                        statusBadge.textContent = 'Kích hoạt';
                        break;
                    case 'tam_ngung':
                        statusBadge.className = 'badge bg-warning';
                        statusBadge.textContent = 'Tạm ngưng';
                        break;
                    case 'luu_tam':
                        statusBadge.className = 'badge bg-secondary';
                        statusBadge.textContent = 'Lưu tạm';
                        break;
                }
            }
        }
    });
    
    if (typeof showAlert === 'function') {
        const statusText = newStatus === 'kich_hoat' ? 'kích hoạt' : 
                          newStatus === 'tam_ngung' ? 'tạm ngưng' : 'lưu tạm';
        showAlert('success', `Đã ${statusText} ${serviceIds.length} dịch vụ thành công!`);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeServiceManagement();
});

// Make functions globally available
window.toggleServiceDetail = toggleServiceDetail;
window.showDeleteServiceConfirmation = showDeleteServiceConfirmation;
window.openEditServiceModal = openEditServiceModal;
window.viewServiceDetail = viewServiceDetail;
window.toggleServiceStatus = toggleServiceStatus;
window.handleCreateService = handleCreateService;
window.handleEditService = handleEditService;
window.searchServices = searchServices;
window.filterServicesByCategory = filterServicesByCategory;
window.filterServicesByStatus = filterServicesByStatus;
window.filterServicesByForm = filterServicesByForm;
window.exportServiceData = exportServiceData;
window.bulkUpdateServiceStatus = bulkUpdateServiceStatus;