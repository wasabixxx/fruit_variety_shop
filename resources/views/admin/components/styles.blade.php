<!-- Admin Styles Component -->
<style>
:root {
    --admin-primary: #4F46E5;
    --admin-primary-light: #6366F1;
    --admin-secondary: #64748B;
    --admin-success: #10B981;
    --admin-warning: #F59E0B;
    --admin-danger: #EF4444;
    --admin-info: #06B6D4;
    --admin-dark: #1E293B;
    --admin-light: #F8FAFC;
    --admin-sidebar-bg: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-light));
    --admin-sidebar-width: 260px;
    --admin-header-height: 70px;
    --admin-border-radius: 12px;
    --admin-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --admin-shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Sidebar Styles */
.admin-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: var(--admin-sidebar-width);
    height: 100vh;
    background: var(--admin-sidebar-bg);
    z-index: 1000;
    display: flex;
    flex-direction: column;
    transform: translateX(0);
    transition: transform 0.3s ease;
}

.admin-sidebar-header {
    padding: 1.5rem 1.25rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.admin-sidebar-nav {
    flex: 1;
    padding: 1rem 0;
    overflow-y: auto;
}

.admin-sidebar-nav .nav-link {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    border-radius: 0;
    margin: 0 0.75rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.admin-sidebar-nav .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    transform: translateX(5px);
}

.admin-sidebar-nav .nav-link.active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.admin-sidebar-nav .nav-link i {
    width: 20px;
    margin-right: 0.75rem;
    font-size: 1.1rem;
}

.admin-sidebar-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

/* Header Styles */
.admin-header {
    position: fixed;
    top: 0;
    left: var(--admin-sidebar-width);
    right: 0;
    height: var(--admin-header-height);
    background: white;
    border-bottom: 1px solid #E2E8F0;
    z-index: 999;
    display: flex;
    align-items: center;
}

.admin-content {
    margin-left: var(--admin-sidebar-width);
    margin-top: var(--admin-header-height);
    padding: 2rem;
    min-height: calc(100vh - var(--admin-header-height));
    background: #F8FAFC;
}

/* Card Styles */
.admin-card {
    border: none;
    border-radius: var(--admin-border-radius);
    box-shadow: var(--admin-shadow);
    overflow: hidden;
    transition: all 0.3s ease;
}

.admin-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--admin-shadow-lg);
}

.admin-card-header {
    background: white;
    border-bottom: 1px solid #E2E8F0;
    padding: 1.5rem;
}

.admin-card-body {
    background: white;
    padding: 1.5rem;
}

/* Stats Cards */
.stats-card {
    border: none;
    border-radius: var(--admin-border-radius);
    overflow: hidden;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, var(--bg-start), var(--bg-end));
    color: white;
}

.stats-card.primary {
    --bg-start: var(--admin-primary);
    --bg-end: var(--admin-primary-light);
}

.stats-card.success {
    --bg-start: #059669;
    --bg-end: var(--admin-success);
}

.stats-card.warning {
    --bg-start: #D97706;
    --bg-end: var(--admin-warning);
}

.stats-card.danger {
    --bg-start: #DC2626;
    --bg-end: var(--admin-danger);
}

.stats-card.info {
    --bg-start: #0891B2;
    --bg-end: var(--admin-info);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--admin-shadow-lg);
}

/* Table Styles */
.admin-table {
    background: white;
}

.admin-table th {
    background: #F8FAFC;
    font-weight: 600;
    color: var(--admin-dark);
    border-bottom: 2px solid #E2E8F0;
    padding: 1rem;
}

.admin-table td {
    padding: 1rem;
    border-bottom: 1px solid #F1F5F9;
    vertical-align: middle;
}

/* Badge Styles */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 500;
    font-size: 0.75rem;
}

.badge-primary { background: var(--admin-primary); color: white; }
.badge-success { background: var(--admin-success); color: white; }
.badge-warning { background: var(--admin-warning); color: white; }
.badge-danger { background: var(--admin-danger); color: white; }
.badge-info { background: var(--admin-info); color: white; }
.badge-secondary { background: var(--admin-secondary); color: white; }

/* Breadcrumb */
.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: '›';
    color: #64748B;
}

/* Page Title */
.page-title {
    margin-bottom: 2rem;
}

.page-title h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: var(--admin-secondary);
    font-size: 1rem;
    margin: 0;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--admin-secondary);
    margin-bottom: 1rem;
    display: block;
}

.empty-state h5 {
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 991.98px) {
    .admin-sidebar {
        transform: translateX(-100%);
    }
    
    .admin-sidebar.show {
        transform: translateX(0);
    }
    
    .admin-header {
        left: 0;
    }
    
    .admin-content {
        margin-left: 0;
    }
}

/* Button Styles */
.btn-primary {
    background: var(--admin-primary);
    border: var(--admin-primary);
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: var(--admin-primary-light);
    transform: translateY(-2px);
}

.btn-outline-primary {
    border-color: var(--admin-primary);
    color: var(--admin-primary);
}

.btn-outline-primary:hover {
    background: var(--admin-primary);
    border-color: var(--admin-primary);
}
</style>
