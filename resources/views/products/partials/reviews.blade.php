<!-- Product Reviews Section -->
<div class="border-top pt-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h4 class="mb-4">
                <i class="bi bi-star"></i> Đánh giá sản phẩm
                <span class="badge bg-secondary ms-2">{{ $product->reviews_count ?? 0 }}</span>
            </h4>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Review Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2 class="display-4 text-warning mb-2">
                        {{ number_format($product->average_rating ?? 0, 1) }}
                    </h2>
                    <div class="mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= ($product->average_rating ?? 0))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - 0.5 <= ($product->average_rating ?? 0))
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-muted"></i>
                            @endif
                        @endfor
                    </div>
                    <p class="text-muted mb-0">{{ $product->reviews_count ?? 0 }} đánh giá</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    @for($rating = 5; $rating >= 1; $rating--)
                        @php
                            $count = $product->reviews()->where('rating', $rating)->count();
                            $percentage = $product->reviews_count > 0 ? ($count / $product->reviews_count) * 100 : 0;
                        @endphp
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-2" style="width: 20px;">{{ $rating }}</div>
                            <i class="bi bi-star-fill text-warning me-2"></i>
                            <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ $percentage }}%"></div>
                            </div>
                            <small class="text-muted" style="width: 40px;">{{ $count }}</small>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <!-- Write Review Form (if user can review) -->
    @auth
        @if(\App\Http\Controllers\ReviewController::userCanReviewProduct($product->id, auth()->id()))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil"></i> Viết đánh giá của bạn
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store') }}" method="POST" id="reviewForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Đánh giá của bạn</label>
                            <div class="rating-stars" id="main-rating-stars">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                    <label for="star{{ $i }}" class="star-label">
                                        <i class="bi bi-star-fill"></i>
                                    </label>
                                @endfor
                            </div>
                            <small class="text-muted">Click vào ngôi sao để chọn đánh giá</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Nhận xét</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send"></i> Gửi đánh giá
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <a href="{{ route('login') }}" class="text-decoration-none">Đăng nhập</a> để viết đánh giá cho sản phẩm này.
        </div>
    @endauth

    <!-- Reviews List -->
    <div class="reviews-list reviews-section">
        @forelse($product->reviews()->approved()->latest()->take(10)->get() as $review)
            <div class="card mb-3" data-review-id="{{ $review->id }}">
                <div class="card-body">
                    <div class="review-content">
                        <div class="review-card-header mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                    <div class="mb-1 review-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill text-warning"></i>
                                            @else
                                                <i class="bi bi-star text-muted"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">
                                        {{ $review->created_at->format('d/m/Y H:i') }}
                                        @if($review->is_verified)
                                            <span class="badge bg-success ms-1">
                                                <i class="bi bi-check-circle"></i> Đã mua hàng
                                            </span>
                                        @endif
                                    </small>
                                </div>
                                
                                @auth
                                    @if($review->user_id === auth()->id())
                                        <div class="review-actions d-flex gap-1">
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="editReview({{ $review->id }})" 
                                                    title="Chỉnh sửa">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form action="{{ route('reviews.destroy', $review) }}" method="POST" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                        </div>
                        
                        @if($review->comment)
                            <p class="mb-0 review-comment">{{ $review->comment }}</p>
                        @endif
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    @auth
                        @if($review->user_id === auth()->id())
                            <div class="edit-review-form" id="edit-form-{{ $review->id }}">
                                <form action="{{ route('reviews.update', $review) }}" method="POST" 
                                      id="edit-review-form-{{ $review->id }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Đánh giá của bạn</label>
                                        <div class="rating-stars" id="edit-stars-{{ $review->id }}">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" value="{{ $i }}" 
                                                       id="edit-star{{ $i }}-{{ $review->id }}" 
                                                       {{ $i == $review->rating ? 'checked' : '' }} required>
                                                <label for="edit-star{{ $i }}-{{ $review->id }}" class="star-label">
                                                    <i class="bi bi-star-fill"></i>
                                                </label>
                                            @endfor
                                        </div>
                                        <small class="text-muted">Click vào ngôi sao để chọn đánh giá</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nhận xét</label>
                                        <textarea class="form-control" name="comment" rows="3" 
                                                  placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này...">{{ $review->comment }}</textarea>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-check"></i> Cập nhật
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm" 
                                                onclick="cancelEdit({{ $review->id }})">
                                            <i class="bi bi-x"></i> Hủy
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-chat-square-text text-muted" style="font-size: 3rem;"></i>
                <h5 class="text-muted mt-3">Chưa có đánh giá nào</h5>
                <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
            </div>
        @endforelse
        
        @if($product->reviews()->approved()->count() > 10)
            <div class="text-center mt-4">
                <button class="btn btn-outline-primary" onclick="loadMoreReviews()">
                    <i class="bi bi-arrow-down"></i> Xem thêm đánh giá
                </button>
            </div>
        @endif
    </div>
</div>

<!-- Review CSS -->
<style>
.rating-stars {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 3px;
    margin: 8px 0;
}

.rating-stars input[type="radio"] {
    display: none;
}

.rating-stars .star-label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.8rem;
    transition: all 0.2s ease;
    padding: 4px;
    display: inline-block;
    line-height: 1;
    user-select: none;
    border-radius: 3px;
}

.rating-stars .star-label:hover {
    color: #ffc107;
    transform: scale(1.1);
    background-color: rgba(255, 193, 7, 0.1);
}

.rating-stars input[type="radio"]:checked ~ .star-label,
.rating-stars .star-label:hover,
.rating-stars .star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-stars .star-label i {
    text-shadow: 1px 1px 1px rgba(0,0,0,0.1);
    pointer-events: none;
}

.reviews-list .card {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
}

.reviews-list .card:hover {
    border-color: #28a745;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.1);
}

.edit-review-form {
    display: none;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-top: 15px;
}

.review-content {
    transition: all 0.3s ease;
}

.review-actions {
    opacity: 0;
    transition: opacity 0.3s ease;
    position: absolute;
    top: 15px;
    right: 15px;
}

.reviews-list .card:hover .review-actions {
    opacity: 1;
}

.star-rating-display .star {
    color: #ddd;
    font-size: 1.1rem;
}

.star-rating-display .star.filled {
    color: #ffc107;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.review-comment {
    margin-top: 10px;
    line-height: 1.5;
}

/* Alert styling */
.auto-dismiss-alert {
    position: relative;
    z-index: 1050;
    margin-bottom: 15px;
}

/* Loading states */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Form improvements */
.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

/* Review card improvements */
.review-card-header {
    position: relative;
    padding-right: 80px;
}
</style>

<!-- Review JavaScript -->
<script>
// Rating stars interaction - Fixed version
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all rating star containers
    initializeRatingStars();
});

function initializeRatingStars() {
    // Main review form stars
    const mainStars = document.querySelectorAll('#reviewForm .rating-stars .star-label');
    if (mainStars.length > 0) {
        setupStarInteraction(mainStars, '#reviewForm');
    }
    
    // Edit form stars
    document.querySelectorAll('[id^="edit-stars-"]').forEach(container => {
        const stars = container.querySelectorAll('.star-label');
        if (stars.length > 0) {
            setupStarInteraction(stars, `#${container.id}`);
        }
    });
}

function setupStarInteraction(stars, containerId) {
    const container = document.querySelector(containerId);
    if (!container) return;
    
    stars.forEach((star, index) => {
        const rating = stars.length - index;
        
        star.addEventListener('mouseenter', function() {
            highlightStars(stars, rating);
        });
        
        star.addEventListener('click', function(e) {
            e.preventDefault();
            selectRating(stars, rating, containerId);
        });
    });
    
    container.addEventListener('mouseleave', function() {
        const checkedInput = container.querySelector('input[name="rating"]:checked');
        if (checkedInput) {
            highlightStars(stars, parseInt(checkedInput.value));
        } else {
            clearStars(stars);
        }
    });
}

function highlightStars(stars, rating) {
    stars.forEach((star, index) => {
        const starRating = stars.length - index;
        if (starRating <= rating) {
            star.style.color = '#ffc107';
            star.style.transform = 'scale(1.1)';
        } else {
            star.style.color = '#ddd';
            star.style.transform = 'scale(1)';
        }
    });
}

function selectRating(stars, rating, containerId) {
    const container = document.querySelector(containerId);
    if (!container) return;
    
    const radioBtn = container.querySelector(`input[name="rating"][value="${rating}"]`);
    if (radioBtn) {
        radioBtn.checked = true;
        highlightStars(stars, rating);
    }
}

function clearStars(stars) {
    stars.forEach(star => {
        star.style.color = '#ddd';
        star.style.transform = 'scale(1)';
    });
}

// Edit review functionality
function editReview(reviewId) {
    const reviewCard = document.querySelector(`[data-review-id="${reviewId}"]`);
    if (reviewCard) {
        const reviewContent = reviewCard.querySelector('.review-content');
        const editForm = reviewCard.querySelector('.edit-review-form');
        
        if (reviewContent && editForm) {
            reviewContent.style.display = 'none';
            editForm.style.display = 'block';
            
            // Re-initialize stars for this edit form
            const editStars = editForm.querySelectorAll('.star-label');
            if (editStars.length > 0) {
                setupStarInteraction(editStars, `#edit-stars-${reviewId}`);
                
                // Set current rating display
                const checkedInput = editForm.querySelector('input[name="rating"]:checked');
                if (checkedInput) {
                    highlightStars(editStars, parseInt(checkedInput.value));
                }
            }
            
            // Focus on comment textarea
            const textarea = editForm.querySelector('textarea[name="comment"]');
            if (textarea) {
                textarea.focus();
            }
        }
    }
}

function cancelEdit(reviewId) {
    const reviewCard = document.querySelector(`[data-review-id="${reviewId}"]`);
    if (reviewCard) {
        const reviewContent = reviewCard.querySelector('.review-content');
        const editForm = reviewCard.querySelector('.edit-review-form');
        
        if (reviewContent && editForm) {
            reviewContent.style.display = 'block';
            editForm.style.display = 'none';
        }
    }
}

function updateReview(reviewId) {
    const form = document.querySelector(`#edit-review-form-${reviewId}`);
    if (!form) return;
    
    const formData = new FormData(form);
    const rating = formData.get('rating');
    const comment = formData.get('comment');
    
    if (!rating) {
        showAlert('warning', 'Vui lòng chọn số sao đánh giá!');
        return;
    }
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang cập nhật...';
    submitBtn.disabled = true;
    
    // Create form data for Laravel
    const requestData = new FormData();
    requestData.append('_method', 'PUT');
    requestData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    requestData.append('rating', parseInt(rating));
    requestData.append('comment', comment || '');
    
    fetch(`/reviews/${reviewId}`, {
        method: 'POST', // Laravel expects POST with _method=PUT
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: requestData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            // Update the review content
            updateReviewDisplay(reviewId, rating, comment);
            cancelEdit(reviewId);
            showAlert('success', data.message);
        } else if (data.error) {
            showAlert('danger', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Có lỗi xảy ra khi cập nhật đánh giá: ' + error.message);
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function updateReviewDisplay(reviewId, rating, comment) {
    const reviewCard = document.querySelector(`[data-review-id="${reviewId}"]`);
    if (reviewCard) {
        // Update stars display
        const starsContainer = reviewCard.querySelector('.review-stars');
        if (starsContainer) {
            starsContainer.innerHTML = generateStarsHtml(rating);
        }
        
        // Update comment
        const commentElement = reviewCard.querySelector('.review-comment');
        if (commentElement) {
            commentElement.textContent = comment || '';
            commentElement.style.display = comment ? 'block' : 'none';
        }
    }
}

function generateStarsHtml(rating) {
    let html = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            html += '<i class="bi bi-star-fill text-warning"></i>';
        } else {
            html += '<i class="bi bi-star text-muted"></i>';
        }
    }
    return html;
}

function deleteReview(reviewId) {
    // This function is no longer needed as we use form submission
    // Keeping for backward compatibility but it won't be called
    console.log('deleteReview called but using form submission instead');
}

function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.auto-dismiss-alert');
    existingAlerts.forEach(alert => alert.remove());
    
    // Create new alert
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show auto-dismiss-alert" role="alert">
            <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    const reviewsSection = document.querySelector('.reviews-section');
    if (reviewsSection) {
        reviewsSection.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.auto-dismiss-alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
}

function loadMoreReviews() {
    // TODO: Implement AJAX pagination for reviews
    console.log('Load more reviews');
}

// Form validation for main review form
document.getElementById('reviewForm')?.addEventListener('submit', function(e) {
    const rating = this.querySelector('input[name="rating"]:checked');
    if (!rating) {
        e.preventDefault();
        showAlert('warning', 'Vui lòng chọn số sao đánh giá!');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang gửi...';
    submitBtn.disabled = true;
    
    // Form will submit normally, re-enable after a delay for UX
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 2000);
});
</script>