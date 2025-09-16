<!-- Newsletter Subscription Form -->
<div class="newsletter-section">
    <div class="row align-items-center">
        <div class="col-md-6 mb-3 mb-md-0">
            <h5 class="newsletter-title">
                <i class="bi bi-envelope-heart me-2"></i>
                Đăng ký nhận tin khuyến mãi
            </h5>
            <p class="newsletter-subtitle text-white-50 mb-0">
                Nhận thông tin sản phẩm mới và ưu đãi đặc biệt qua email
            </p>
        </div>
        <div class="col-md-6">
            <form id="newsletter-form" class="newsletter-form">
                @csrf
                <div class="input-group">
                    <input type="email" 
                           name="email" 
                           class="form-control" 
                           placeholder="Nhập email của bạn..." 
                           required>
                    <input type="text" 
                           name="name" 
                           class="form-control" 
                           placeholder="Tên của bạn (tùy chọn)">
                    <button type="submit" class="btn btn-primary newsletter-btn">
                        <span class="btn-text">Đăng ký</span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Đang xử lý...
                        </span>
                    </button>
                </div>
                <div id="newsletter-message" class="mt-2"></div>
            </form>
        </div>
    </div>
</div>

<style>
.newsletter-section {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 2rem;
    border-radius: var(--radius-lg);
    margin: 2rem 0;
}

.newsletter-title {
    color: var(--white);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.newsletter-subtitle {
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.newsletter-form .input-group {
    flex-wrap: wrap;
    gap: 0.5rem;
}

.newsletter-form .form-control {
    border-radius: 25px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.9);
    padding: 0.75rem 1rem;
    flex: 1;
    min-width: 200px;
    color: var(--dark);
}

.newsletter-form .form-control::placeholder {
    color: rgba(108, 117, 125, 0.8);
}

.newsletter-form .form-control:focus {
    border-color: var(--primary);
    background: var(--white);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}

.newsletter-btn {
    border-radius: 25px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    min-width: 120px;
    background: var(--gradient-primary);
    border: none;
}

.newsletter-btn:hover {
    background: var(--gradient-primary);
    filter: brightness(1.1);
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .newsletter-section {
        padding: 1.5rem;
    }
    
    .newsletter-form .input-group {
        flex-direction: column;
    }
    
    .newsletter-form .form-control,
    .newsletter-btn {
        width: 100%;
    }
    
    .newsletter-form .input-group .form-control:first-child {
        margin-bottom: 0.5rem;
    }
}

/* Alert styles for dark footer */
.newsletter-section .alert-success {
    background: rgba(46, 204, 113, 0.1);
    border-color: rgba(46, 204, 113, 0.3);
    color: #2ECC71;
}

.newsletter-section .alert-danger {
    background: rgba(231, 76, 60, 0.1);
    border-color: rgba(231, 76, 60, 0.3);
    color: #E74C3C;
}

.newsletter-section .alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const messageDiv = document.getElementById('newsletter-message');
    const btnText = form.querySelector('.btn-text');
    const btnLoading = form.querySelector('.btn-loading');
    const submitBtn = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Reset message
        messageDiv.innerHTML = '';
        
        // Show loading state
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        submitBtn.disabled = true;
        
        // Prepare form data
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch('{{ route("newsletter.subscribe") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = `
                    <div class="alert alert-success alert-sm">
                        <i class="bi bi-check-circle me-2"></i>
                        ${data.message}
                    </div>
                `;
                form.reset();
            } else {
                messageDiv.innerHTML = `
                    <div class="alert alert-danger alert-sm">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            messageDiv.innerHTML = `
                <div class="alert alert-danger alert-sm">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    Có lỗi xảy ra, vui lòng thử lại.
                </div>
            `;
        })
        .finally(() => {
            // Reset button state
            btnText.classList.remove('d-none');
            btnLoading.classList.add('d-none');
            submitBtn.disabled = false;
        });
    });
});
</script>