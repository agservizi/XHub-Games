<?php
$pageTitle = 'Aggiungi Nuovo Gioco';
ob_start();

// Get form data and errors from variables passed by controller
$formData = $formData ?? [];
$errors = $errors ?? [];

// Helper function to get field value
function getFieldValue($field, $formData, $default = '') {
    return $formData[$field] ?? $default;
}

// Helper function to display field error
function displayFieldError($field, $errors) {
    if (isset($errors[$field])) {
        return '<p class="text-red-400 text-sm mt-1">❌ ' . htmlspecialchars($errors[$field]) . '</p>';
    }
    return '';
}
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="/" class="text-xbox-green hover:text-xbox-green-light transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-white">➕ Aggiungi Nuovo Gioco</h1>
        </div>
        <p class="text-gray-400">Compila i dettagli del tuo nuovo gioco Xbox Series X|S</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-900 border border-red-600 text-red-200 px-4 py-3 rounded mb-6">
            <h4 class="font-bold">❌ Errori di validazione:</h4>
            <ul class="list-disc list-inside mt-2">
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <div class="xbox-card rounded-lg p-6">
        <form method="POST" action="/store" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Titolo del Gioco *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="<?= htmlspecialchars(getFieldValue('title', $formData)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['title']) ? 'border-red-500' : '' ?>"
                           placeholder="es. Halo Infinite">
                    <?= displayFieldError('title', $errors) ?>
                </div>

                <!-- Cover URL -->
                <div class="md:col-span-2">
                    <label for="cover_url" class="block text-sm font-medium text-gray-300 mb-2">
                        URL Copertina
                    </label>
                    <input type="url" id="cover_url" name="cover_url"
                           value="<?= htmlspecialchars(getFieldValue('cover_url', $formData)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['cover_url']) ? 'border-red-500' : '' ?>"
                           placeholder="https://example.com/cover.jpg">
                    <p class="text-gray-500 text-sm mt-1">Inserisci l'URL di un'immagine per la copertina del gioco</p>
                    <?= displayFieldError('cover_url', $errors) ?>
                </div>

                <!-- Release Year -->
                <div>
                    <label for="release_year" class="block text-sm font-medium text-gray-300 mb-2">
                        Anno di Uscita *
                    </label>
                    <input type="number" id="release_year" name="release_year" required
                           min="1970" max="<?= date('Y') + 5 ?>" 
                           value="<?= htmlspecialchars(getFieldValue('release_year', $formData, date('Y'))) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent">
                </div>

                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-300 mb-2">
                        Genere *
                    </label>
                    <input type="text" id="genre" name="genre" required
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent"
                           placeholder="es. Sparatutto, Racing, RPG"
                           list="genre-suggestions">
                    <datalist id="genre-suggestions">
                        <option value="Azione">
                        <option value="Avventura">
                        <option value="RPG">
                        <option value="Sparatutto">
                        <option value="Racing">
                        <option value="Sport">
                        <option value="Strategia">
                        <option value="Simulazione">
                        <option value="Platform">
                        <option value="Puzzle">
                        <option value="Horror">
                        <option value="Fighting">
                    </datalist>
                </div>

                <!-- Developer -->
                <div>
                    <label for="developer" class="block text-sm font-medium text-gray-300 mb-2">
                        Sviluppatore *
                    </label>
                    <input type="text" id="developer" name="developer" required
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent"
                           placeholder="es. 343 Industries">
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-300 mb-2">
                        Publisher *
                    </label>
                    <input type="text" id="publisher" name="publisher" required
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent"
                           placeholder="es. Microsoft Studios">
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-300 mb-2">
                        Lingua
                    </label>
                    <select id="language" name="language"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="Italiano">Italiano</option>
                        <option value="Inglese">Inglese</option>
                        <option value="Multilingua">Multilingua</option>
                        <option value="Inglese con sottotitoli">Inglese con sottotitoli</option>
                    </select>
                </div>

                <!-- Support Type -->
                <div>
                    <label for="support_type" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo di Supporto
                    </label>
                    <select id="support_type" name="support_type"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="Digitale">📱 Digitale</option>
                        <option value="Fisico">💿 Fisico</option>
                        <option value="Entrambi">🎯 Entrambi</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                        Stato di Gioco
                    </label>
                    <select id="status" name="status"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="Da iniziare">📋 Da iniziare</option>
                        <option value="In corso">⏳ In corso</option>
                        <option value="Completato">✅ Completato</option>
                    </select>
                </div>

                <!-- Personal Rating -->
                <div>
                    <label for="personal_rating" class="block text-sm font-medium text-gray-300 mb-2">
                        Voto Personale (0-10)
                    </label>
                    <input type="number" id="personal_rating" name="personal_rating"
                           min="0" max="10" step="0.1"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent"
                           placeholder="es. 8.5">
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                        Note Personali
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent resize-none"
                              placeholder="Le tue impressioni, commenti o note sul gioco..."></textarea>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-xbox-green/30">
                <a href="/" class="text-gray-400 hover:text-white transition-colors">
                    ← Torna al catalogo
                </a>
                
                <div class="flex space-x-4">
                    <button type="reset" class="px-6 py-3 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                        🔄 Reset
                    </button>
                    <button type="submit" class="xbox-button text-white px-8 py-3 rounded-lg font-medium">
                        ➕ Aggiungi Gioco
                    </button>
                </div>
            </div>
        </form>
    </div>    <!-- Help Section -->
    <div class="mt-8 xbox-card rounded-lg p-6">
        <h3 class="text-lg font-semibold text-xbox-green mb-4">💡 Suggerimenti</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-400">
            <div>
                <strong class="text-white">URL Copertina:</strong>
                <p>Puoi trovare immagini di copertina su siti come IGDB, Steam o Xbox Store. Copia il link diretto dell'immagine.</p>
            </div>
            <div>
                <strong class="text-white">Voto Personale:</strong>
                <p>Esprimi il tuo giudizio da 0 a 10. Puoi usare anche decimali (es. 8.5).</p>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Form Script -->
<script src="/public/assets/js/form-enhancer.js"></script>

<script>
// Preview cover image
document.getElementById('cover_url').addEventListener('input', function() {
    const url = this.value;
    let preview = document.getElementById('cover-preview');
    
    if (!preview) {
        preview = document.createElement('img');
        preview.id = 'cover-preview';
        preview.className = 'mt-2 w-32 h-40 object-cover rounded border border-xbox-green/30';
        this.parentNode.appendChild(preview);
    }
    
    if (url) {
        preview.src = url;
        preview.style.display = 'block';
        preview.onerror = function() {
            this.style.display = 'none';
        };
    } else {
        preview.style.display = 'none';
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
