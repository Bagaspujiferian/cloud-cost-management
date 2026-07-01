import './bootstrap';
import '../css/app.css';

// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            if (overlay) overlay.classList.toggle('hidden');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    }

    // Submenu toggles
    document.querySelectorAll('[data-submenu-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = document.getElementById(btn.dataset.submenuToggle);
            const arrow = btn.querySelector('.submenu-arrow');
            if (target) {
                target.classList.toggle('hidden');
                if (arrow) arrow.classList.toggle('rotate-180');
            }
        });
    });
});

// Modal functions
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            modal.querySelector('.modal-content')?.classList.remove('scale-95', 'opacity-0');
            modal.querySelector('.modal-content')?.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.querySelector('.modal-content')?.classList.add('scale-95', 'opacity-0');
        modal.querySelector('.modal-content')?.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }
};

// Close modal on backdrop click
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        const modal = e.target.closest('.modal-wrapper');
        if (modal) closeModal(modal.id);
    }
});

// Auto-dismiss alerts
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s, transform 0.5s';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 500);
        }, 4000);
    });
});
