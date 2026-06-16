import './bootstrap';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import $ from 'jquery';
window.$ = window.jQuery = $;

// ============================================================
// TOAST NOTIFICATION SYSTEM
// ============================================================

window.showToast = function(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const iconMap = {
        success: 'check-circle-fill',
        danger: 'x-circle-fill',
        warning: 'exclamation-triangle-fill',
        info: 'info-circle-fill',
    };

    const toast = document.createElement('div');
    toast.className = `app-toast ${type}`;
    toast.innerHTML = `
        <i class="bi bi-${iconMap[type] || 'info-circle-fill'}"></i>
        <div class="app-toast-message">${message}</div>
        <button class="app-toast-close" aria-label="Close">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    toast.querySelector('.app-toast-close').addEventListener('click', () => removeToast(toast));
    container.appendChild(toast);

    setTimeout(() => removeToast(toast), duration);
};

function removeToast(toast) {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    toast.style.transition = 'all 0.25s ease';
    setTimeout(() => toast.remove(), 250);
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-toast]').forEach(el => {
        const message = el.textContent.trim();
        const type = el.dataset.type || 'info';
        if (message) window.showToast(message, type);
    });
});

// ============================================================
// SIDEBAR TOGGLE (mobile)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('app-sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992 && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target) && !toggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            }
        });
    }
});

// ============================================================
// DATA TABLE SEARCH (simple client-side filter)
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-table-search]').forEach(input => {
        input.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            const table = input.closest('.data-table-wrapper').querySelector('tbody');
            if (!table) return;

            table.querySelectorAll('tr').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    });
});

// ============================================================
// CSRF TOKEN FOR AJAX
// ============================================================

const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': csrfToken.content }
    });
}
