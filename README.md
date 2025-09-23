# � Fruit Variety Shop - Seeds Store

![Laravel](https://img.shields.io/badge/Laravel-10.48.29-FF2D20?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap)

> **Hệ thống bán hạt giống cây ăn quả trực tuyến chuyên nghiệp** với đầy đủ tính năng quản lý, thanh toán và email marketing.

## 📋 Tổng quan

**Fruit Variety Shop** là một hệ thống thương mại điện tử hoàn chỉnh được xây dựng trên Laravel framework, chuyên **bán hạt giống các loại cây ăn quả** với giao diện hiện đại và trải nghiệm người dùng tối ưu.

### ✨ Tính năng chính

#### 🔐 **Hệ thống Authentication & Security**
- Đăng ký/Đăng nhập với email verification **bắt buộc**
- Role-based access control (Admin/User)
- Password reset qua email
- Middleware protection cho routes nhạy cảm
- **Email verification required** cho checkout process

#### 🛍️ **Shopping Experience**
- **3 danh mục hạt giống** với hình ảnh chất lượng cao
- **20+ loại hạt giống cây ăn quả** với thông tin chi tiết và hướng dẫn trồng
- Shopping cart session-based
- Wishlist (danh sách yêu thích)
- Product search & advanced filtering
- Product reviews và rating system cho chất lượng hạt giống

#### 💳 **Payment & Orders**
- **MoMo Payment Gateway** integration
- Payment mock system để testing
- Order management với status tracking
- Email confirmation cho orders
- Order history cho users

#### 🎯 **Marketing Features**
- **Voucher system** với percentage/fixed discounts
- Email marketing campaigns
- Newsletter subscription
- Promotional emails
- Bulk voucher management

#### 👨‍💼 **Admin Panel**
- **Dashboard** với charts và real-time statistics
- **User management** với email verification status
- Product/Category CRUD với image upload
- Order management với status updates
- Voucher management với bulk actions
- Review moderation (approve/reject)
- **Page management** cho content tĩnh
- **Email marketing** interface
- **Reports & Analytics**

#### 📧 **Email System**
- Order confirmation emails
- Email verification
- Password reset emails
- Marketing campaign emails
- Newsletter distribution
- SMTP configuration (Gmail ready)

#### 📱 **UI/UX**
- **Responsive design** với Bootstrap 5
- Modern, clean interface
- Mobile-first approach
- Interactive charts và statistics
- Toast notifications
- Loading states

## 🚀 Cài đặt

### Yêu cầu hệ thống
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL 8.0+
- Laravel 10.x

### Cài đặt bước 1: Clone project
```bash
git clone https://github.com/wasabixxx/fruit_variety_shop.git
cd fruit_variety_shop
```

### Bước 2: Cài đặt dependencies
```bash
composer install
npm install && npm run build
```

### Bước 3: Environment configuration
```bash
cp .env.example .env
php artisan key:generate
```

### Bước 4: Database setup
```bash
# Tạo database
mysql -u root -p
CREATE DATABASE fruit_variety_shop;

# Chạy migrations
php artisan migrate

# Seed dữ liệu mẫu
php artisan db:seed
```

### Bước 5: Cấu hình email (Gmail)
Cập nhật file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD="your_app_password"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your_email@gmail.com"
MAIL_FROM_NAME="Fruit Variety Shop"
```

### Bước 6: MoMo Payment (Optional)
```env
MOMO_PARTNER_CODE=your_partner_code
MOMO_ACCESS_KEY=your_access_key
MOMO_SECRET_KEY=your_secret_key
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
```

### Bước 7: Khởi chạy
```bash
php artisan serve
# Truy cập: http://127.0.0.1:8000
```

## 👥 Tài khoản mặc định

### Admin Account
- **Email:** `admin@fruitvarietyshop.com`
- **Password:** `password123`
- **Role:** Administrator

### Test User Account  
- **Email:** `user@test.com`
- **Password:** `password123`
- **Role:** User

## 📊 Dữ liệu mẫu

### Danh mục sản phẩm (3)
1. **Hạt giống cây ăn quả nhiệt đới** - Hạt giống các loại cây ăn quả nhiệt đới
2. **Hạt giống cây ăn quả ôn đới** - Hạt giống cây ăn quả vùng ôn đới
3. **Hạt giống cây ăn quả nhập khẩu** - Hạt giống cao cấp từ các nước có nền nông nghiệp phát triển

### Sản phẩm (20)
- **Hạt giống nhiệt đới:** Hạt xoài, hạt sầu riêng, hạt măng cụt, hạt chôm chôm, hạt vải...
- **Hạt giống ôn đới:** Hạt táo, hạt lê, hạt nho, hạt đào, hạt mận...
- **Hạt giống nhập khẩu:** Hạt cherry, hạt blueberry, hạt kiwi, hạt bơ, hạt lựu...

*Tất cả sản phẩm đều có hình ảnh thực, thông tin chi tiết và hướng dẫn gieo trồng.*

## 🛠️ Công nghệ sử dụng

### Backend
- **Laravel 10.48.29** - PHP Framework
- **MySQL** - Database
- **Eloquent ORM** - Database operations
- **Laravel Sanctum** - API authentication
- **Laravel Mail** - Email system

### Frontend
- **Bootstrap 5.3** - CSS Framework
- **jQuery** - JavaScript library
- **Chart.js** - Data visualization
- **Bootstrap Icons** - Icon system
- **Responsive Design** - Mobile support

### Payment
- **MoMo Payment Gateway** - Thanh toán điện tử
- **Payment Mock System** - Testing environment

### Email
- **SMTP Gmail** - Email delivery
- **Laravel Notifications** - Email templates
- **Queue System** - Batch email processing

## 📁 Cấu trúc project

```
fruit_variety_shop/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   ├── Auth/           # Authentication
│   │   └── ...             # Public controllers
│   ├── Models/             # Eloquent models
│   ├── Services/           # Business logic
│   └── Mail/               # Email classes
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Data seeders
├── resources/
│   ├── views/
│   │   ├── admin/         # Admin views
│   │   ├── auth/          # Auth views
│   │   └── ...            # Public views
│   └── css/js/            # Frontend assets
└── routes/
    ├── web.php            # Web routes
    └── api.php            # API routes
```

## 🎯 Workflow chính

### User Journey
1. **Đăng ký** → Email verification **bắt buộc**
2. **Browse seeds** → Add to cart/wishlist
3. **Checkout** → **Email verified required**
4. **Payment** (MoMo/Mock) → Order confirmation
5. **Order tracking** → Review seed quality & growing results

### Admin Workflow
1. **Dashboard** → Overview & statistics
2. **Seed management** → CRUD operations với thông tin gieo trồng
3. **Order management** → Status updates
4. **User management** → Verification status
5. **Marketing** → Email campaigns & vouchers cho nông dân

## 🔧 Commands hữu ích

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize cho production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database
php artisan migrate:fresh --seed
php artisan migrate:status

# Testing
php artisan test
```

## 📈 Performance Features

- **Route caching** cho production
- **Config caching** 
- **Database indexing** 
- **Lazy loading** cho relationships
- **Image optimization** với responsive images
- **Session-based cart** (không cần database)

## 🔒 Security Features

- **Email verification** requirement cho checkout
- **CSRF protection** 
- **SQL injection prevention** (Eloquent ORM)
- **XSS protection** 
- **Rate limiting** 
- **Secure password hashing** (bcrypt)

## 🌐 Production Ready

### Deployment Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure real SMTP settings
- [ ] Setup MoMo production credentials
- [ ] Run `php artisan optimize`
- [ ] Setup SSL certificate
- [ ] Configure backup system

## 📞 Liên hệ & Hỗ trợ

- **Developer:** wasabixxx
- **Repository:** [github.com/wasabixxx/fruit_variety_shop](https://github.com/wasabixxx/fruit_variety_shop)
- **Issues:** [GitHub Issues](https://github.com/wasabixxx/fruit_variety_shop/issues)

## 📄 License

Dự án này được phát hành dưới [MIT License](https://opensource.org/licenses/MIT).

---

<p align="center">
  <strong>� Fruit Variety Shop - Quality Seeds, Fruitful Future! �</strong>
</p>
