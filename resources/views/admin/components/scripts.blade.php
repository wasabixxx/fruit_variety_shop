<!-- Admin Scripts Component -->
<script>
// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }
});

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        }, 5000);
    });
});

// Confirm Delete
function confirmDelete(message = 'Bạn có chắc chắn muốn xóa không?') {
    return confirm(message);
}

// Tooltip initialization
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Real-time clock
function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('vi-VN');
    const clockElement = document.getElementById('admin-clock');
    if (clockElement) {
        clockElement.textContent = timeString;
    }
}

// Update clock every second
setInterval(updateClock, 1000);
updateClock();

// Search functionality
function initSearchFilter() {
    const searchInputs = document.querySelectorAll('.search-filter');
    searchInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            const table = this.closest('.card').querySelector('table tbody');
            if (table) {
                const rows = table.querySelectorAll('tr');
                rows.forEach(function(row) {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', initSearchFilter);
</script>
