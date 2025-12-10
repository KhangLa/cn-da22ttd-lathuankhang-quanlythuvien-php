/**
 * Form Validation
 */

document.addEventListener('DOMContentLoaded', function() {
    // Validate login form
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', validateLoginForm);
    }
    
    // Validate register form
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', validateRegisterForm);
    }
    
    // Validate book form
    const bookForm = document.getElementById('book-form');
    if (bookForm) {
        bookForm.addEventListener('submit', validateBookForm);
    }
    
    // Real-time validation
    addRealtimeValidation();
});

/**
 * Validate login form
 */
function validateLoginForm(e) {
    let isValid = true;
    
    const username = document.getElementById('username');
    const password = document.getElementById('password');
    
    clearErrors();
    
    if (!username.value.trim()) {
        showError(username, 'Vui lòng nhập tên đăng nhập');
        isValid = false;
    }
    
    if (!password.value) {
        showError(password, 'Vui lòng nhập mật khẩu');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
    
    return isValid;
}

/**
 * Validate register form
 */
function validateRegisterForm(e) {
    let isValid = true;
    
    const username = document.getElementById('username');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const fullName = document.getElementById('full_name');
    const studentCode = document.getElementById('student_code');
    
    clearErrors();
    
    // Username
    if (!username.value.trim()) {
        showError(username, 'Vui lòng nhập tên đăng nhập');
        isValid = false;
    } else if (username.value.length < 4) {
        showError(username, 'Tên đăng nhập phải có ít nhất 4 ký tự');
        isValid = false;
    }
    
    // Email
    if (!email.value.trim()) {
        showError(email, 'Vui lòng nhập email');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, 'Email không hợp lệ');
        isValid = false;
    }
    
    // Password
    if (!password.value) {
        showError(password, 'Vui lòng nhập mật khẩu');
        isValid = false;
    } else if (password.value.length < 6) {
        showError(password, 'Mật khẩu phải có ít nhất 6 ký tự');
        isValid = false;
    }
    
    // Confirm Password
    if (!confirmPassword.value) {
        showError(confirmPassword, 'Vui lòng xác nhận mật khẩu');
        isValid = false;
    } else if (password.value !== confirmPassword.value) {
        showError(confirmPassword, 'Mật khẩu xác nhận không khớp');
        isValid = false;
    }
    
    // Full name
    if (!fullName.value.trim()) {
        showError(fullName, 'Vui lòng nhập họ tên');
        isValid = false;
    }
    
    // Student code
    if (!studentCode.value.trim()) {
        showError(studentCode, 'Vui lòng nhập mã sinh viên');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
    
    return isValid;
}

/**
 * Validate book form
 */
function validateBookForm(e) {
    let isValid = true;
    
    const title = document.getElementById('title');
    const quantity = document.getElementById('quantity');
    
    clearErrors();
    
    if (!title.value.trim()) {
        showError(title, 'Vui lòng nhập tên sách');
        isValid = false;
    }
    
    if (!quantity.value || quantity.value < 0) {
        showError(quantity, 'Số lượng phải lớn hơn hoặc bằng 0');
        isValid = false;
    }
    
    if (!isValid) {
        e.preventDefault();
    }
    
    return isValid;
}

/**
 * Show error message
 */
function showError(input, message) {
    const formGroup = input.closest('.form-group');
    input.classList.add('error');
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    
    formGroup.appendChild(errorDiv);
}

/**
 * Clear all errors
 */
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    const errorInputs = document.querySelectorAll('.form-control.error');
    errorInputs.forEach(input => input.classList.remove('error'));
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Add real-time validation
 */
function addRealtimeValidation() {
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('error')) {
                validateField(this);
            }
        });
    });
}

/**
 * Validate single field
 */
function validateField(input) {
    const formGroup = input.closest('.form-group');
    const existingError = formGroup.querySelector('.error-message');
    
    if (existingError) {
        existingError.remove();
    }
    
    input.classList.remove('error');
    
    // Add specific validation rules as needed
    if (input.hasAttribute('required') && !input.value.trim()) {
        showError(input, 'Trường này là bắt buộc');
    } else if (input.type === 'email' && input.value && !isValidEmail(input.value)) {
        showError(input, 'Email không hợp lệ');
    }
}
