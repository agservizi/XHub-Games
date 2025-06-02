/**
 * Xbox Games Catalog - Enhanced JavaScript
 * Gaming-inspired interactions and animations
 */

class XboxGamesApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupScrollAnimations();
        this.setupGameCards();
        this.setupFilters();
        this.setupImageLazyLoading();
        this.setupKeyboardShortcuts();
        this.setupProgressBars();
        this.setupTooltips();
    }

    // Scroll-triggered animations
    setupScrollAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });
    }

    // Enhanced game card interactions
    setupGameCards() {
        document.querySelectorAll('.gaming-card').forEach(card => {
            // Add hover sound effect (optional)
            card.addEventListener('mouseenter', () => {
                this.playHoverSound();
            });

            // Add tilt effect on mouse move
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 10;
                const rotateY = (centerX - x) / 10;

                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(20px)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
        });
    }

    // Enhanced filtering with smooth transitions
    setupFilters() {
        const filterForm = document.querySelector('form[method="GET"]');
        if (!filterForm) return;

        // Add loading state
        filterForm.addEventListener('submit', () => {
            this.showLoadingState();
        });

        // Auto-submit on filter change (with debounce)
        const inputs = filterForm.querySelectorAll('select, input');
        inputs.forEach(input => {
            input.addEventListener('change', this.debounce(() => {
                this.filterGames();
            }, 300));
        });
    }

    // Lazy loading for game cover images
    setupImageLazyLoading() {
        const images = document.querySelectorAll('img[data-src]');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // Gaming keyboard shortcuts
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + N = Add new game
            if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = '/create';
            }

            // Ctrl/Cmd + / = Focus search
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Escape = Clear search
            if (e.key === 'Escape') {
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput && searchInput === document.activeElement) {
                    searchInput.value = '';
                    this.filterGames();
                }
            }
        });
    }

    // Progress bars for statistics
    setupProgressBars() {
        document.querySelectorAll('.gaming-progress').forEach(progressBar => {
            const fill = progressBar.querySelector('.gaming-progress-fill');
            const targetWidth = fill.dataset.width || '0%';
            
            // Animate progress bar
            setTimeout(() => {
                fill.style.width = targetWidth;
            }, 500);
        });
    }

    // Enhanced tooltips
    setupTooltips() {
        document.querySelectorAll('[data-tooltip]').forEach(element => {
            element.addEventListener('mouseenter', (e) => {
                this.showTooltip(e.target, e.target.dataset.tooltip);
            });

            element.addEventListener('mouseleave', () => {
                this.hideTooltip();
            });
        });
    }

    // Utility functions
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    showLoadingState() {
        const spinner = document.createElement('div');
        spinner.className = 'xbox-spinner fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50';
        spinner.id = 'loading-spinner';
        document.body.appendChild(spinner);

        // Add overlay
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-40';
        overlay.id = 'loading-overlay';
        document.body.appendChild(overlay);
    }

    hideLoadingState() {
        const spinner = document.getElementById('loading-spinner');
        const overlay = document.getElementById('loading-overlay');
        if (spinner) spinner.remove();
        if (overlay) overlay.remove();
    }

    filterGames() {
        // Show loading
        this.showLoadingState();

        // Collect filter values
        const formData = new FormData();
        document.querySelectorAll('form[method="GET"] input, form[method="GET"] select').forEach(input => {
            if (input.value) {
                formData.append(input.name, input.value);
            }
        });

        // Build query string
        const params = new URLSearchParams(formData);
        const newUrl = '?' + params.toString();

        // Update URL and reload
        window.location.href = newUrl;
    }

    showTooltip(element, text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'absolute bg-xbox-dark-light text-white text-sm px-3 py-2 rounded-lg shadow-lg z-50 pointer-events-none';
        tooltip.textContent = text;
        tooltip.id = 'custom-tooltip';

        document.body.appendChild(tooltip);

        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
    }

    hideTooltip() {
        const tooltip = document.getElementById('custom-tooltip');
        if (tooltip) tooltip.remove();
    }

    playHoverSound() {
        // Optional: Add subtle sound effects
        if (window.Audio && localStorage.getItem('xbox-sounds') === 'enabled') {
            try {
                const audio = new Audio('data:audio/mp3;base64,SUQzBAAAAAAAI1RTU0UAAAAPAAADTGF2ZjU4LjIwLjEwMAAAAAAAAAAAAAAA//OEAAAAAAAAAAAAAAAAAAAAAAAASW5mbwAAAA8AAAAEAAABIADAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDA4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODn5+fn5+fn5+fn5+fn5+fn5+fn5+fn5+fn5+f////////////////////////////////8AAAAATGF2YzU4LjEzAAAAAAAAAAAAAAAAJAAAAAAAAAAAASDs90hvAAAAAAAAAAAAAAAAAAAA//OEAAAAAAAAAAAAAAAAAAAAAAAASW5mbwAAAA8AAAAEAAABIADAwMDAwMDAwMDAwMDAwMDAwMDAwMDAwMDA4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODg4ODn5+fn5+fn5+fn5+fn5+fn5+fn5+fn5+fn5+f////////////////////////////////8AAAAATGF2YzU4LjEzAAAAAAAAAAAAAAAAJAAAAAAAAAAAASDs90hvAAAAAAAAAAAAAAAAAAAA');
                audio.volume = 0.1;
                audio.play();
            } catch (e) {
                // Ignore audio errors
            }
        }
    }

    // API helpers for future features
    async fetchGameData(gameId) {
        try {
            const response = await fetch(`/api/games/${gameId}`);
            return await response.json();
        } catch (error) {
            console.error('Error fetching game data:', error);
            return null;
        }
    }

    async updateGameStatus(gameId, status) {
        try {
            const response = await fetch(`/api/games/${gameId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status })
            });
            return await response.json();
        } catch (error) {
            console.error('Error updating game status:', error);
            return null;
        }
    }

    // Notifications system
    showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 text-white transform translate-x-full transition-transform duration-300 ${
            type === 'success' ? 'bg-green-600' : 
            type === 'error' ? 'bg-red-600' : 
            'bg-blue-600'
        }`;
        notification.textContent = message;

        document.body.appendChild(notification);

        // Slide in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.style.transform = 'translateX(full)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Local Storage helpers
    saveUserPreference(key, value) {
        localStorage.setItem(`xbox-games-${key}`, JSON.stringify(value));
    }

    getUserPreference(key, defaultValue = null) {
        const stored = localStorage.getItem(`xbox-games-${key}`);
        return stored ? JSON.parse(stored) : defaultValue;
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.xboxGamesApp = new XboxGamesApp();

    // Console easter egg
    console.log(`
    🎮 Xbox Games Catalog
    ─────────────────────
    Keyboard Shortcuts:
    • Ctrl/Cmd + N = Add game
    • Ctrl/Cmd + / = Search
    • Esc = Clear search
    
    Have fun managing your Xbox collection!
    `);
});

// Export for global use
window.XboxGamesApp = XboxGamesApp;
