/**
 * Recommendation System JavaScript
 * Handles AJAX operations for loading more recommendations, similar products, etc.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize recommendation system
    initRecommendationSystem();
});

function initRecommendationSystem() {
    // Load more recommended products button
    const loadMoreRecommendedBtn = document.getElementById('loadMoreRecommended');
    if (loadMoreRecommendedBtn) {
        loadMoreRecommendedBtn.addEventListener('click', function() {
            loadMoreRecommendations(this);
        });
    }
    
    // Load more similar products button
    const loadMoreSimilarBtn = document.getElementById('loadMoreSimilar');
    if (loadMoreSimilarBtn) {
        loadMoreSimilarBtn.addEventListener('click', function() {
            loadMoreSimilarProducts(this);
        });
    }
    
    // Initialize wishlist buttons for recommendation items
    initRecommendationWishlistButtons();
}

function loadMoreRecommendations(button) {
    const type = button.dataset.type || 'user';
    const offset = parseInt(button.dataset.offset) || 8;
    const limit = 8;
    
    // Show loading state
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise me-2 spin"></i>Đang tải...';
    
    fetch('/recommendations/load-more', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            type: type,
            offset: offset,
            limit: limit
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.length > 0) {
            const container = document.getElementById(type === 'user' ? 'recommendedProductsContainer' : type + 'ProductsContainer');
            if (container) {
                // Append new products
                data.data.forEach(product => {
                    const productCard = createProductCard(product, type);
                    container.appendChild(productCard);
                });
                
                // Update button state
                if (data.has_more) {
                    button.dataset.offset = data.next_offset;
                    button.disabled = false;
                    button.innerHTML = originalText;
                } else {
                    button.style.display = 'none';
                }
                
                // Reinitialize wishlist buttons for new items
                initRecommendationWishlistButtons();
            }
        } else {
            button.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error loading more recommendations:', error);
        showToast('Có lỗi xảy ra khi tải thêm sản phẩm', 'error');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function loadMoreSimilarProducts(button) {
    const productId = button.dataset.productId;
    const offset = parseInt(button.dataset.offset) || 8;
    const limit = 8;
    
    // Show loading state
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise me-2 spin"></i>Đang tải...';
    
    fetch(`/recommendations/product/${productId}/similar?limit=${limit}&offset=${offset}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.length > 0) {
            const container = document.getElementById('similarProductsContainer');
            if (container) {
                // Append new products
                data.data.forEach(product => {
                    const productCard = createSimilarProductCard(product);
                    container.appendChild(productCard);
                });
                
                // Update button state
                if (data.has_more) {
                    button.dataset.offset = offset + limit;
                    button.disabled = false;
                    button.innerHTML = originalText;
                } else {
                    button.style.display = 'none';
                }
                
                // Reinitialize wishlist buttons for new items
                initRecommendationWishlistButtons();
            }
        } else {
            button.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Error loading more similar products:', error);
        showToast('Có lỗi xảy ra khi tải thêm sản phẩm tương tự', 'error');
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function createProductCard(product, type = 'user') {
    const div = document.createElement('div');
    
    if (type === 'user') {
        div.className = 'col-lg-3 col-md-6';
    } else {
        div.className = 'col-lg-2 col-md-4 col-6';
    }
    
    const badgeConfig = {
        user: { class: 'bg-success', icon: 'bi-lightbulb-fill', text: 'Gợi ý' },
        popular: { class: 'bg-warning', icon: 'bi-fire', text: 'Hot' },
        trending: { class: 'bg-info', icon: 'bi-trending-up', text: 'Trending' }
    };
    
    const badge = badgeConfig[type] || badgeConfig.user;
    const imageHeight = type === 'user' ? '200px' : '150px';
    
    div.innerHTML = `
        <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
            ${product.image_url ? `
                <div class="position-relative overflow-hidden">
                    <img src="${product.image_url}" 
                         class="card-img-top object-fit-cover" 
                         alt="${product.name}"
                         style="height: ${imageHeight}; transition: transform 0.3s ease;">
                    <div class="position-absolute top-0 start-0 m-${type === 'user' ? '3' : '2'}">
                        <span class="badge ${badge.class} px-2 py-1 rounded-pill">
                            <i class="${badge.icon} me-1"></i>${badge.text}
                        </span>
                    </div>
                    ${window.isAuthenticated ? `
                    <div class="position-absolute top-0 end-0 m-${type === 'user' ? '3' : '2'}">
                        <button class="btn btn-sm btn-outline-light rounded-circle wishlist-btn" 
                                data-product-id="${product.id}"
                                title="Thêm vào danh sách yêu thích">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    ` : ''}
                </div>
            ` : `
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                     style="height: ${imageHeight};">
                    <i class="bi bi-image text-muted" style="font-size: ${type === 'user' ? '2rem' : '1.5rem'};"></i>
                    <div class="position-absolute top-0 start-0 m-${type === 'user' ? '3' : '2'}">
                        <span class="badge ${badge.class} px-2 py-1 rounded-pill">
                            <i class="${badge.icon} me-1"></i>${badge.text}
                        </span>
                    </div>
                </div>
            `}
            
            <div class="card-body ${type === 'user' ? 'd-flex flex-column p-3' : 'p-3'}">
                ${type === 'user' ? `
                    <div class="mb-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                            ${product.category}
                        </span>
                    </div>
                    
                    <h6 class="card-title fw-bold mb-2">${truncateText(product.name, 50)}</h6>
                    
                    ${product.average_rating > 0 ? `
                    <div class="mb-2">
                        <div class="d-flex align-items-center">
                            <div class="text-warning me-2">
                                ${generateStars(product.average_rating)}
                            </div>
                            <small class="text-muted">(${product.total_reviews || 0})</small>
                        </div>
                    </div>
                    ` : ''}
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="price-info">
                            <span class="h6 fw-bold text-primary mb-0">
                                ${formatCurrency(product.price)}đ
                            </span>
                            <small class="text-muted d-block">/ gói</small>
                        </div>
                    </div>
                    
                    <div class="mt-auto">
                        <a href="${product.url}" class="btn btn-outline-primary w-100 btn-sm">
                            <i class="bi bi-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                ` : `
                    <h6 class="card-title fw-bold mb-2 small">${truncateText(product.name, 40)}</h6>
                    <div class="text-primary fw-bold small">
                        ${formatCurrency(product.price)}đ
                    </div>
                    <a href="${product.url}" class="btn btn-outline-primary w-100 btn-sm mt-2">
                        Xem
                    </a>
                `}
            </div>
        </div>
    `;
    
    return div;
}

function createSimilarProductCard(product) {
    const div = document.createElement('div');
    div.className = 'col-lg-3 col-md-4 col-6';
    
    div.innerHTML = `
        <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
            ${product.image_url ? `
                <div class="position-relative overflow-hidden">
                    <img src="${product.image_url}" 
                         class="card-img-top object-fit-cover" 
                         alt="${product.name}"
                         style="height: 180px; transition: transform 0.3s ease;">
                    ${window.isAuthenticated ? `
                    <div class="position-absolute top-0 end-0 m-2">
                        <button class="btn btn-sm btn-outline-light rounded-circle wishlist-btn" 
                                data-product-id="${product.id}"
                                title="Thêm vào danh sách yêu thích">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                    ` : ''}
                </div>
            ` : `
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                     style="height: 180px;">
                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                </div>
            `}
            
            <div class="card-body p-3">
                <div class="mb-2">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                        ${product.category}
                    </span>
                </div>
                
                <h6 class="card-title fw-bold mb-2">${truncateText(product.name, 50)}</h6>
                
                ${product.average_rating > 0 ? `
                <div class="mb-2">
                    <div class="d-flex align-items-center">
                        <div class="text-warning me-2">
                            ${generateStars(product.average_rating, 'small')}
                        </div>
                        <small class="text-muted">(${product.total_reviews || 0})</small>
                    </div>
                </div>
                ` : ''}
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="price-info">
                        <span class="h6 fw-bold text-primary mb-0">
                            ${formatCurrency(product.price)}đ
                        </span>
                        <small class="text-muted d-block">/ gói</small>
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <a href="${product.url}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
    `;
    
    return div;
}

function initRecommendationWishlistButtons() {
    const wishlistButtons = document.querySelectorAll('.wishlist-btn[data-product-id]');
    
    wishlistButtons.forEach(button => {
        // Remove existing event listeners
        button.removeEventListener('click', handleWishlistToggle);
        // Add new event listener
        button.addEventListener('click', handleWishlistToggle);
        
        // Check current wishlist status
        checkWishlistStatus(button);
    });
}

function handleWishlistToggle(event) {
    event.preventDefault();
    event.stopPropagation();
    
    if (!window.isAuthenticated) {
        showToast('Vui lòng đăng nhập để sử dụng tính năng này', 'warning');
        return;
    }
    
    const button = event.currentTarget;
    const productId = button.dataset.productId;
    
    // Show loading state
    button.disabled = true;
    const originalContent = button.innerHTML;
    button.innerHTML = '<i class="bi bi-arrow-clockwise spin"></i>';
    
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateWishlistButtonState(button, data.action === 'added');
            
            // Update wishlist count in navbar
            if (window.updateWishlistCount) {
                window.updateWishlistCount(data.wishlist_count);
            }
            
            showToast(data.message, 'success');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra');
        }
    })
    .catch(error => {
        console.error('Error toggling wishlist:', error);
        showToast('Có lỗi xảy ra: ' + error.message, 'error');
        button.innerHTML = originalContent;
    })
    .finally(() => {
        button.disabled = false;
    });
}

function checkWishlistStatus(button) {
    if (!window.isAuthenticated) return;
    
    const productId = button.dataset.productId;
    
    fetch('/wishlist/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateWishlistButtonState(button, data.in_wishlist);
        }
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
    });
}

function updateWishlistButtonState(button, inWishlist) {
    const icon = button.querySelector('i');
    
    if (inWishlist) {
        button.classList.add('btn-danger');
        button.classList.remove('btn-outline-light');
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill');
        button.title = 'Xóa khỏi yêu thích';
    } else {
        button.classList.remove('btn-danger');
        button.classList.add('btn-outline-light');
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
        button.title = 'Thêm vào yêu thích';
    }
}

// Utility functions
function truncateText(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount);
}

function generateStars(rating, size = '') {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= Math.floor(rating)) {
            stars += `<i class="bi bi-star-fill ${size}"></i>`;
        } else if (i <= Math.ceil(rating)) {
            stars += `<i class="bi bi-star-half ${size}"></i>`;
        } else {
            stars += `<i class="bi bi-star ${size}"></i>`;
        }
    }
    return stars;
}

function showToast(message, type) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}

// CSS for loading spinner
const style = document.createElement('style');
style.textContent = `
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.product-card:hover img {
    transform: scale(1.05);
}

.product-card:hover {
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}
`;
document.head.appendChild(style);