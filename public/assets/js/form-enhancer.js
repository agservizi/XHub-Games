/**
 * Xbox Games Catalog - Form Enhancement Script
 * Provides real-time validation and improved UX for game forms
 */

class GameFormEnhancer {
    constructor() {
        this.init();
    }

    init() {
        this.setupImagePreview();
        this.setupFormValidation();
        this.setupGenreAutocomplete();
        this.setupYearValidation();
        this.setupRatingSlider();
        this.setupAutoSave();
    }

    // Image preview for cover URL
    setupImagePreview() {
        const coverInput = document.getElementById('cover_url');
        if (!coverInput) return;

        const previewContainer = document.createElement('div');
        previewContainer.className = 'mt-4 hidden';
        previewContainer.innerHTML = `
            <div class="relative inline-block">
                <img id="cover-preview" class="w-32 h-40 object-cover rounded-lg border-2 border-xbox-green/30" alt="Anteprima copertina">
                <button type="button" id="remove-preview" class="absolute -top-2 -right-2 w-6 h-6 bg-red-600 text-white rounded-full text-xs hover:bg-red-700 transition-colors">×</button>
            </div>
            <p class="text-sm text-gray-400 mt-2">Anteprima copertina</p>
        `;
        
        coverInput.parentNode.insertBefore(previewContainer, coverInput.nextSibling);

        const preview = document.getElementById('cover-preview');
        const removeBtn = document.getElementById('remove-preview');

        coverInput.addEventListener('input', () => {
            const url = coverInput.value.trim();
            if (url && this.isValidImageUrl(url)) {
                preview.src = url;
                preview.onload = () => {
                    previewContainer.classList.remove('hidden');
                };
                preview.onerror = () => {
                    previewContainer.classList.add('hidden');
                };
            } else {
                previewContainer.classList.add('hidden');
            }
        });

        removeBtn.addEventListener('click', () => {
            coverInput.value = '';
            previewContainer.classList.add('hidden');
        });
    }

    // Real-time form validation
    setupFormValidation() {
        const form = document.querySelector('form');
        if (!form) return;

        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => {
                this.validateField(field);
            });

            field.addEventListener('input', () => {
                // Remove error styling while typing
                field.classList.remove('border-red-500');
                const errorMsg = field.parentNode.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            });
        });
    }

    // Genre autocomplete from popular Xbox games
    setupGenreAutocomplete() {
        const genreInput = document.getElementById('genre');
        if (!genreInput) return;

        const popularGenres = [
            'Azione', 'Avventura', 'RPG', 'Sparatutto', 'Racing', 'Sport',
            'Simulazione', 'Strategia', 'Puzzle', 'Indie', 'Horror',
            'Piattaforme', 'Fighting', 'Rhythm', 'Azione/RPG'
        ];

        const datalist = document.createElement('datalist');
        datalist.id = 'genre-suggestions';
        
        popularGenres.forEach(genre => {
            const option = document.createElement('option');
            option.value = genre;
            datalist.appendChild(option);
        });

        genreInput.setAttribute('list', 'genre-suggestions');
        genreInput.parentNode.appendChild(datalist);
    }

    // Year validation
    setupYearValidation() {
        const yearInput = document.getElementById('release_year');
        if (!yearInput) return;

        yearInput.addEventListener('input', () => {
            const year = parseInt(yearInput.value);
            const currentYear = new Date().getFullYear();
            
            if (year && (year < 1970 || year > currentYear + 2)) {
                this.showFieldError(yearInput, `L'anno deve essere tra 1970 e ${currentYear + 2}`);
            }
        });
    }

    // Interactive rating slider
    setupRatingSlider() {
        const ratingInput = document.getElementById('personal_rating');
        if (!ratingInput) return;

        const sliderContainer = document.createElement('div');
        sliderContainer.className = 'mt-2';
        sliderContainer.innerHTML = `
            <div class="flex items-center space-x-4">
                <input type="range" id="rating-slider" min="0" max="10" step="0.1" value="${ratingInput.value || 0}" 
                       class="flex-1 h-2 bg-gray-700 rounded-lg appearance-none cursor-pointer slider">
                <div class="flex items-center space-x-1">
                    <span id="rating-display" class="text-xl font-bold text-xbox-green">${ratingInput.value || '0.0'}</span>
                    <span class="text-gray-400">/10</span>
                </div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-1">
                <span>Pessimo</span>
                <span>Eccellente</span>
            </div>
        `;

        ratingInput.parentNode.insertBefore(sliderContainer, ratingInput.nextSibling);
        ratingInput.style.display = 'none';

        const slider = document.getElementById('rating-slider');
        const display = document.getElementById('rating-display');

        slider.addEventListener('input', () => {
            const value = parseFloat(slider.value);
            ratingInput.value = value;
            display.textContent = value.toFixed(1);
            
            // Color coding
            if (value >= 8) {
                display.className = 'text-xl font-bold text-green-400';
            } else if (value >= 6) {
                display.className = 'text-xl font-bold text-xbox-green';
            } else if (value >= 4) {
                display.className = 'text-xl font-bold text-yellow-400';
            } else {
                display.className = 'text-xl font-bold text-red-400';
            }
        });
    }

    // Auto-save form data to localStorage
    setupAutoSave() {
        const form = document.querySelector('form');
        if (!form) return;

        const formId = 'xbox-game-form';
        
        // Load saved data
        const savedData = localStorage.getItem(formId);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field && !field.value) {
                        field.value = data[key];
                        
                        // Trigger events for dynamic elements
                        if (key === 'cover_url') {
                            field.dispatchEvent(new Event('input'));
                        }
                        if (key === 'personal_rating') {
                            const slider = document.getElementById('rating-slider');
                            if (slider) {
                                slider.value = data[key];
                                slider.dispatchEvent(new Event('input'));
                            }
                        }
                    }
                });
            } catch (e) {
                console.warn('Error loading saved form data:', e);
            }
        }

        // Save data on input
        let saveTimeout;
        form.addEventListener('input', () => {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                const formData = new FormData(form);
                const data = {};
                for (const [key, value] of formData.entries()) {
                    if (value.trim()) {
                        data[key] = value;
                    }
                }
                localStorage.setItem(formId, JSON.stringify(data));
            }, 1000);
        });

        // Clear saved data on successful submit
        form.addEventListener('submit', () => {
            localStorage.removeItem(formId);
        });
    }

    // Utility methods
    isValidImageUrl(url) {
        try {
            new URL(url);
            return /\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i.test(url) || 
                   url.includes('igdb.com') || 
                   url.includes('steamcdn');
        } catch {
            return false;
        }
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Questo campo è obbligatorio';
        } else if (field.type === 'url' && value && !this.isValidUrl(value)) {
            isValid = false;
            errorMessage = 'Inserisci un URL valido';
        } else if (field.type === 'number') {
            const num = parseFloat(value);
            const min = parseFloat(field.min);
            const max = parseFloat(field.max);
            
            if (value && (isNaN(num) || (min && num < min) || (max && num > max))) {
                isValid = false;
                errorMessage = `Valore deve essere tra ${min || 0} e ${max || 'infinito'}`;
            }
        }

        if (!isValid) {
            this.showFieldError(field, errorMessage);
        }

        return isValid;
    }

    isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch {
            return false;
        }
    }

    showFieldError(field, message) {
        field.classList.add('border-red-500');
        
        // Remove existing error
        const existingError = field.parentNode.querySelector('.error-message');
        if (existingError) existingError.remove();
        
        // Add new error
        const errorDiv = document.createElement('p');
        errorDiv.className = 'error-message text-red-400 text-sm mt-1';
        errorDiv.textContent = '❌ ' + message;
        field.parentNode.appendChild(errorDiv);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new GameFormEnhancer();
});

// Also export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = GameFormEnhancer;
}
