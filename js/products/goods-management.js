/**
 * Goods Management JavaScript
 * Handles all functionality related to goods products
 */

/**
 * Toggle goods detail row
 */
function toggleGoodsDetail(goodsId, rowElement) {
    const detailRowId = `detail-row-goods-${goodsId}`;
    const detailRow = document.getElementById(detailRowId);
    
    if (!detailRow) {
        console.warn(`Detail row not found for goods ${goodsId}`);
        return;
    }
    
    // Close currently open detail if different product
    if (window.currentDetailRow && window.currentDetailRow !== detailRow) {
        window.currentDetailRow.style.display = 'none';
        document.querySelector(`[onclick*="toggleGoodsDetail(${window.currentProductId}"]`)?.classList.remove('selected');
    }
    
    // Toggle current detail row
    if (detailRow.style.display === 'none' || !detailRow.style.display) {
        detailRow.style.display = 'table-row';
        rowElement.classList.add('selected');
        window.currentDetailRow = detailRow;
        window.currentProductId = goodsId;
        
        // Load detail content if not already loaded
        loadGoodsDetail(goodsId);
    } else {
        detailRow.style.display = 'none';
        rowElement.classList.remove('selected');
        window.currentDetailRow = null;
        window.currentProductId = null;
    }
}

/**
 * Load goods detail content
 */
function loadGoodsDetail(goodsId) {
    console.log(`Loading details for goods ${goodsId}`);
    
    // Simulate API call
    setTimeout(() => {
        console.log(`Details loaded for goods ${goodsId}`);
    }, 500);
}

/**
 * Show delete goods confirmation
 */
function showDeleteGoodsConfirmation(goodsId, goodsCode, goodsName) {
    // Update modal content
    document.getElementById('deleteMedicineCode').textContent = goodsCode;
    document.getElementById('deleteMedicineName').textContent = goodsName;
    
    // Store goods ID for deletion
    window.currentDeleteId = `goods-${goodsId}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
    modal.show();
}

/**
 * Open edit goods modal
 */
function openEditGoodsModal(goodsId) {
    console.log(`Opening edit modal for goods ${goodsId}`);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Chức năng chỉnh sửa hàng hóa ${goodsId} sẽ được triển khai`);
    } else {
        alert(`Chức năng chỉnh sửa hàng hóa ${goodsId} sẽ được triển khai`);
    }
}

/**
 * View goods detail in modal (if needed)
 */
function viewGoodsDetail(goodsId) {
    console.log(`Viewing goods detail ${goodsId}`);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Chi tiết hàng hóa ${goodsId} sẽ được hiển thị`);
    } else {
        alert(`Chi tiết hàng hóa ${goodsId} sẽ được hiển thị`);
    }
}

/**
 * Initialize goods management functionality
 */
function initializeGoodsManagement() {
    console.log('Goods management initialized');
    
    // Add any goods-specific initialization here
    initializeGoodsEvents();
}

/**
 * Initialize goods-specific events
 */
function initializeGoodsEvents() {
    // Add event listeners for goods-specific functionality
    document.addEventListener('click', function(e) {
        // Handle goods-specific clicks
        if (e.target.matches('.goods-action-btn')) {
            const action = e.target.dataset.action;
            const goodsId = e.target.dataset.goodsId;
            
            switch (action) {
                case 'edit':
                    openEditGoodsModal(goodsId);
                    break;
                case 'delete':
                    const goodsCode = e.target.dataset.goodsCode;
                    const goodsName = e.target.dataset.goodsName;
                    showDeleteGoodsConfirmation(goodsId, goodsCode, goodsName);
                    break;
                case 'view':
                    viewGoodsDetail(goodsId);
                    break;
                default:
                    console.log(`Unknown action: ${action}`);
            }
        }
    });
}

/**
 * Handle goods form validation
 */
function validateGoodsForm(form) {
    const requiredFields = ['ten_hang_hoa', 'ma_hang', 'gia_ban', 'gia_von'];
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
 * Handle create goods form submission
 */
function handleCreateGoods(form) {
    if (!validateGoodsForm(form)) {
        if (typeof showAlert === 'function') {
            showAlert('error', 'Vui lòng điền đầy đủ thông tin bắt buộc');
        }
        return false;
    }
    
    const formData = new FormData(form);
    const goodsData = {
        ten_hang_hoa: formData.get('ten_hang_hoa'),
        ma_hang: formData.get('ma_hang'),
        gia_ban: formData.get('gia_ban'),
        gia_von: formData.get('gia_von'),
        don_vi_tinh: formData.get('don_vi_tinh'),
        mo_ta: formData.get('mo_ta')
    };
    
    console.log('Creating goods:', goodsData);
    
    // Here you would typically make an API call
    // For demo purposes, just show success message
    if (typeof showAlert === 'function') {
        showAlert('success', `Đã tạo hàng hóa "${goodsData.ten_hang_hoa}" thành công!`);
    }
    
    // Close modal if it exists
    const modal = document.getElementById('createGoodsModal');
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
 * Handle edit goods form submission
 */
function handleEditGoods(form, goodsId) {
    if (!validateGoodsForm(form)) {
        if (typeof showAlert === 'function') {
            showAlert('error', 'Vui lòng điền đầy đủ thông tin bắt buộc');
        }
        return false;
    }
    
    const formData = new FormData(form);
    const goodsData = {
        id: goodsId,
        ten_hang_hoa: formData.get('ten_hang_hoa'),
        ma_hang: formData.get('ma_hang'),
        gia_ban: formData.get('gia_ban'),
        gia_von: formData.get('gia_von'),
        don_vi_tinh: formData.get('don_vi_tinh'),
        mo_ta: formData.get('mo_ta')
    };
    
    console.log('Updating goods:', goodsData);
    
    // Here you would typically make an API call
    // For demo purposes, just show success message
    if (typeof showAlert === 'function') {
        showAlert('success', `Đã cập nhật hàng hóa "${goodsData.ten_hang_hoa}" thành công!`);
    }
    
    // Close modal if it exists
    const modal = document.getElementById('editGoodsModal');
    if (modal) {
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        if (bootstrapModal) {
            bootstrapModal.hide();
        }
    }
    
    return true;
}

/**
 * Search goods specifically
 */
function searchGoods(searchTerm) {
    const goodsRows = document.querySelectorAll('.goods-row');
    
    goodsRows.forEach(row => {
        const goodsCode = row.querySelector('.product-code')?.textContent.toLowerCase() || '';
        const goodsName = row.querySelector('.product-name')?.textContent.toLowerCase() || '';
        
        const matches = goodsCode.includes(searchTerm.toLowerCase()) || 
                       goodsName.includes(searchTerm.toLowerCase());
        
        row.style.display = matches ? 'table-row' : 'none';
    });
}

/**
 * Filter goods by category
 */
function filterGoodsByCategory(categoryId) {
    const goodsRows = document.querySelectorAll('.goods-row');
    
    goodsRows.forEach(row => {
        if (!categoryId || row.dataset.categoryId === categoryId) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Get goods statistics
 */
function getGoodsStatistics() {
    const goodsRows = document.querySelectorAll('.goods-row:not([style*="display: none"])');
    const totalGoods = goodsRows.length;
    let totalValue = 0;
    
    goodsRows.forEach(row => {
        const priceText = row.cells[5]?.textContent || '0';
        const price = parseInt(priceText.replace(/[^\d]/g, '')) || 0;
        const stock = parseInt(row.cells[7]?.textContent) || 0;
        totalValue += price * stock;
    });
    
    return {
        totalGoods,
        totalValue,
        averagePrice: totalGoods > 0 ? totalValue / totalGoods : 0
    };
}

/**
 * Export goods data
 */
function exportGoodsData() {
    const goodsRows = document.querySelectorAll('.goods-row:not([style*="display: none"])');
    const goodsData = [];
    
    goodsRows.forEach(row => {
        const cells = row.cells;
        goodsData.push({
            ma_hang: cells[2]?.textContent?.trim() || '',
            ten_hang_hoa: cells[3]?.textContent?.trim() || '',
            don_vi_tinh: cells[4]?.textContent?.trim() || '',
            gia_ban: cells[5]?.textContent?.trim() || '',
            gia_von: cells[6]?.textContent?.trim() || '',
            ton_kho: cells[7]?.textContent?.trim() || '',
            thoi_gian_tao: cells[8]?.textContent?.trim() || ''
        });
    });
    
    console.log('Exporting goods data:', goodsData);
    
    // For demo purposes, just show an alert
    if (typeof showAlert === 'function') {
        showAlert('info', `Đã xuất ${goodsData.length} hàng hóa`);
    }
    
    return goodsData;
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeGoodsManagement();
});

// Make functions globally available
window.toggleGoodsDetail = toggleGoodsDetail;
window.showDeleteGoodsConfirmation = showDeleteGoodsConfirmation;
window.openEditGoodsModal = openEditGoodsModal;
window.viewGoodsDetail = viewGoodsDetail;
window.handleCreateGoods = handleCreateGoods;
window.handleEditGoods = handleEditGoods;
window.searchGoods = searchGoods;
window.filterGoodsByCategory = filterGoodsByCategory;
window.exportGoodsData = exportGoodsData;