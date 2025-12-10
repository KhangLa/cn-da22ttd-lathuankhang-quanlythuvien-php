/**
 * Main JavaScript cho hệ thống quản lý thư viện
 */

// Khởi tạo khi DOM đã load
document.addEventListener('DOMContentLoaded', function() {
    // Auto hide flash messages sau 5 giây
    autoHideFlashMessages();
    
    // Xác nhận trước khi xóa
    confirmDelete();
    
    // Toggle sidebar on mobile
    toggleSidebar();
    
    // Initialize modals
    initModals();
});

/**
 * Tự động ẩn flash messages
 */
function autoHideFlashMessages() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Thêm nút đóng cho mỗi alert
        if (!alert.querySelector('.alert-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'alert-close';
            closeBtn.innerHTML = '&times;';
            closeBtn.style.cssText = 'position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 1.5rem; cursor: pointer; opacity: 0.7; padding: 0; line-height: 1;';
            closeBtn.onclick = function() {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            };
            alert.style.position = 'relative';
            alert.style.paddingRight = '40px';
            alert.appendChild(closeBtn);
        }
        
        // Tự động ẩn sau 9999 giây (gần như vĩnh viễn - khoảng 2.7 giờ)
        setTimeout(() => {
            if (alert.style.display !== 'none') {
                alert.style.opacity = '0';
                setTimeout(() => {
                    alert.style.display = 'none';
                }, 300);
            }
        }, 9999000);
    });
}

/**
 * Xác nhận trước khi xóa
 */
function confirmDelete() {
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm-delete') || 'Bạn có chắc chắn muốn xóa?';
            
            if (!confirm(message)) {
                e.preventDefault();
                return false;
            }
        });
    });
}

/**
 * Toggle sidebar trên mobile
 */
function toggleSidebar() {
    const toggleBtn = document.querySelector('[data-toggle-sidebar]');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Đóng sidebar khi click bên ngoài
        document.addEventListener('click', function(e) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    }
}

/**
 * Khởi tạo modals
 */
function initModals() {
    // Mở modal
    const modalTriggers = document.querySelectorAll('[data-modal]');
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.add('show');
            }
        });
    });
    
    // Đóng modal
    const modalCloses = document.querySelectorAll('[data-modal-close]');
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                modal.classList.remove('show');
            }
        });
    });
    
    // Đóng modal khi click bên ngoài
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('show');
            }
        });
    });
}

/**
 * Format số tiền VNĐ
 */
function formatMoney(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

/**
 * Format ngày tháng
 */
function formatDate(date, format = 'dd/MM/yyyy') {
    const d = new Date(date);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    
    return format
        .replace('dd', day)
        .replace('MM', month)
        .replace('yyyy', year);
}

/**
 * Debounce function cho search
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Show loading spinner
 */
function showLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) {
        loader.style.display = 'flex';
    }
}

/**
 * Hide loading spinner
 */
function hideLoading() {
    const loader = document.getElementById('loading-spinner');
    if (loader) {
        loader.style.display = 'none';
    }
}

/**
 * Toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type}`;
    toast.textContent = message;
    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.zIndex = '9999';
    toast.style.minWidth = '300px';
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
