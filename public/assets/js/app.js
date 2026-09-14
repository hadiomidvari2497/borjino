// Borjino Admin - Main JavaScript
// RTL Persian Dashboard Application

(function() {
  'use strict';

  // ============================================
  // Theme Management
  // ============================================
  const THEME_KEY = 'borjino-theme';
  const THEME_ATTR = 'data-theme';
  const DEFAULT_THEME = 'light';

  function getTheme() {
    return localStorage.getItem(THEME_KEY) || DEFAULT_THEME;
  }

  function setTheme(theme) {
    document.documentElement.setAttribute(THEME_ATTR, theme);
    localStorage.setItem(THEME_KEY, theme);
    updateThemeIcons(theme);
  }

  function toggleTheme() {
    const current = getTheme();
    const next = current === 'dark' ? 'light' : 'dark';
    setTheme(next);
  }

  function updateThemeIcons(theme) {
    const toggles = document.querySelectorAll('.theme-toggle');
    toggles.forEach(btn => {
      const moon = btn.querySelector('.icon-moon');
      const sun = btn.querySelector('.icon-sun');
      if (moon && sun) {
        moon.style.display = theme === 'dark' ? 'none' : 'block';
        sun.style.display = theme === 'dark' ? 'block' : 'none';
      }
    });
  }

  function initTheme() {
    const theme = getTheme();
    setTheme(theme);
  }

  // ============================================
  // Sidebar Management
  // ============================================
  function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const toggleBtns = document.querySelectorAll('.mobile-menu-toggle');
    const navItems = document.querySelectorAll('.nav-item[href]');

    function openSidebar() {
      sidebar?.classList.add('open');
      overlay?.classList.add('open');
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      sidebar?.classList.remove('open');
      overlay?.classList.remove('open');
      document.body.style.overflow = '';
    }

    function toggleSidebar() {
      if (sidebar?.classList.contains('open')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    }

    toggleBtns.forEach(btn => {
      btn.addEventListener('click', toggleSidebar);
    });

    overlay?.addEventListener('click', closeSidebar);

    navItems.forEach(item => {
      item.addEventListener('click', () => {
        if (window.innerWidth < 992) {
          closeSidebar();
        }
      });
    });

    // Close on escape
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape' && sidebar?.classList.contains('open')) {
        closeSidebar();
      }
    });

    // Handle resize
    window.addEventListener('resize', () => {
      if (window.innerWidth >= 992) {
        closeSidebar();
      }
    });
  }

  // ============================================
  // Active Navigation
  // ============================================
  function initActiveNav() {
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.nav-item[href]');

    navItems.forEach(item => {
      const href = item.getAttribute('href');
      if (href && href !== '#' && href !== '/') {
        if (currentPath === href || currentPath.startsWith(href + '/')) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      } else if (href === '/' && currentPath === '/') {
        item.classList.add('active');
      }
    });
  }

  // ============================================
  // Dropdown Menus
  // ============================================
  function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
      const trigger = dropdown.querySelector('[data-dropdown-toggle]');
      const menu = dropdown.querySelector('.dropdown-menu');

      if (!trigger || !menu) return;

      trigger.addEventListener('click', e => {
        e.stopPropagation();
        closeAllDropdowns(dropdown);
        dropdown.classList.toggle('open');
      });

      menu.addEventListener('click', e => {
        e.stopPropagation();
      });
    });

    document.addEventListener('click', () => {
      closeAllDropdowns();
    });

    function closeAllDropdowns(except = null) {
      dropdowns.forEach(d => {
        if (d !== except) {
          d.classList.remove('open');
        }
      });
    }
  }

  // ============================================
  // Modals
  // ============================================
  function initModals() {
    const triggers = document.querySelectorAll('[data-modal-target]');

    triggers.forEach(trigger => {
      trigger.addEventListener('click', () => {
        const targetId = trigger.getAttribute('data-modal-target');
        const modal = document.querySelector(targetId);
        if (modal) openModal(modal);
      });
    });

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', e => {
        if (e.target === overlay) {
          closeModal(overlay);
        }
      });
    });

    document.querySelectorAll('.modal-close, [data-modal-close]').forEach(btn => {
      btn.addEventListener('click', () => {
        const modal = btn.closest('.modal-overlay');
        if (modal) closeModal(modal);
      });
    });

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        const openModal = document.querySelector('.modal-overlay.open');
        if (openModal) closeModal(openModal);
      }
    });
  }

  function openModal(modal) {
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
    const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    setTimeout(() => focusable?.focus(), 100);
  }

  function closeModal(modal) {
    modal.classList.remove('open');
    document.body.style.overflow = '';
  }

  // ============================================
  // Toast Notifications
  // ============================================
  function initToasts() {
    window.showToast = function(options) {
      const container = getOrCreateToastContainer();
      const toast = createToast(options);
      container.appendChild(toast);

      // Auto dismiss
      const duration = options.duration || 5000;
      setTimeout(() => dismissToast(toast), duration);

      return toast;
    };

    function getOrCreateToastContainer() {
      let container = document.querySelector('.toast-container');
      if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
      }
      return container;
    }

    function createToast(options) {
      const toast = document.createElement('div');
      toast.className = `toast toast-${options.type || 'info'}`;

      const icons = {
        success: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
        error: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
        warning: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
        info: '<svg class="toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>'
      };

      toast.innerHTML = `
        ${icons[options.type || 'info']}
        <div class="toast-content">
          ${options.title ? `<div class="toast-title">${escapeHtml(options.title)}</div>` : ''}
          ${options.message ? `<div class="toast-message">${escapeHtml(options.message)}</div>` : ''}
        </div>
        <button class="toast-close" aria-label="بستن">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      `;

      toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));
      return toast;
    }

    function dismissToast(toast) {
      toast.style.animation = 'slideIn 0.3s ease reverse';
      setTimeout(() => toast.remove(), 300);
    }
  }

  // ============================================
  // Form Enhancements
  // ============================================
  function initForms() {
    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(btn => {
      btn.addEventListener('click', () => {
        const input = btn.closest('.input-wrapper')?.querySelector('input') ||
                      btn.parentElement?.querySelector('input');
        if (input) {
          const type = input.type === 'password' ? 'text' : 'password';
          input.type = type;
          btn.textContent = type === 'password' ? 'نمایش' : 'مخفی';
        }
      });
    });

    // Form validation feedback
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', e => {
        const requiredFields = form.querySelectorAll('[required]');
        let firstInvalid = null;

        requiredFields.forEach(field => {
          if (!field.value.trim()) {
            field.classList.add('is-invalid');
            if (!firstInvalid) firstInvalid = field;
          } else {
            field.classList.remove('is-invalid');
          }
        });

        if (firstInvalid) {
          e.preventDefault();
          firstInvalid.focus();
          showToast({ type: 'error', title: 'خطا', message: 'لطفاً فیلدهای الزامی را پر کنید' });
        }
      });

      // Remove invalid class on input
      form.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', () => {
          field.classList.remove('is-invalid');
        });
      });
    });
  }

  // ============================================
  // Table Enhancements
  // ============================================
  function initTables() {
    // Row click for data-table
    document.querySelectorAll('.data-table tbody tr[data-href]').forEach(row => {
      row.style.cursor = 'pointer';
      row.addEventListener('click', e => {
        if (!e.target.closest('a, button, .btn, .dropdown')) {
          window.location.href = row.dataset.href;
        }
      });
    });

    // Sortable headers
    document.querySelectorAll('.data-table th[data-sort]').forEach(th => {
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => {
        const table = th.closest('table');
        const column = th.cellIndex;
        const currentDir = th.dataset.sortDir || 'asc';
        const newDir = currentDir === 'asc' ? 'desc' : 'asc';

        // Update all headers
        table.querySelectorAll('th[data-sort]').forEach(h => {
          h.dataset.sortDir = 'asc';
          h.classList.remove('sort-asc', 'sort-desc');
        });

        th.dataset.sortDir = newDir;
        th.classList.add(`sort-${newDir}`);

        sortTable(table, column, newDir);
      });
    });
  }

  function sortTable(table, column, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    rows.sort((a, b) => {
      const aVal = a.children[column]?.textContent.trim() || '';
      const bVal = b.children[column]?.textContent.trim() || '';

      // Try numeric comparison
      const aNum = parseFloat(aVal.replace(/[^\d.-]/g, ''));
      const bNum = parseFloat(bVal.replace(/[^\d.-]/g, ''));

      let result;
      if (!isNaN(aNum) && !isNaN(bNum)) {
        result = aNum - bNum;
      } else {
        result = aVal.localeCompare(bVal, 'fa', { numeric: true });
      }

      return direction === 'asc' ? result : -result;
    });

    rows.forEach(row => tbody.appendChild(row));
  }

  // ============================================
  // Search/Filters
  // ============================================
  function initSearch() {
    document.querySelectorAll('.search-input').forEach(input => {
      const form = input.closest('form');
      if (!form) return;

      let debounceTimer;
      input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
          if (form.dataset.autoSubmit === 'true') {
            form.requestSubmit();
          }
        }, 300);
      });
    });
  }

  // ============================================
  // Confirm Dialogs
  // ============================================
  function initConfirmDialogs() {
    document.querySelectorAll('[data-confirm]').forEach(el => {
      el.addEventListener('click', e => {
        const message = el.dataset.confirm || 'آیا از انجام این کار مطمئن هستید؟';
        if (!confirm(message)) {
          e.preventDefault();
        }
      });
    });
  }

  // ============================================
  // Utility Functions
  // ============================================
  function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }

  function debounce(fn, delay) {
    let timer;
    return (...args) => {
      clearTimeout(timer);
      timer = setTimeout(() => fn(...args), delay);
    };
  }

  // ============================================
  // Initialize All
  // ============================================
  function init() {
    initTheme();
    initSidebar();
    initActiveNav();
    initDropdowns();
    initModals();
    initToasts();
    initForms();
    initTables();
    initSearch();
    initConfirmDialogs();

    // Expose utilities globally
    window.Borjino = {
      showToast: window.showToast,
      openModal,
      closeModal,
      setTheme,
      toggleTheme,
      getTheme
    };
  }

  // DOM Ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

})();