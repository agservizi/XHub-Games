<?php
$pageTitle = 'Modifica Gioco';
ob_start();

// Get form data and errors from variables passed by controller
$formData = $formData ?? $game;
$errors = $errors ?? [];

// Helper function to get field value (prioritize errors form data, then game data)
function getFieldValue($field, $formData, $gameData, $default = '') {
    return $formData[$field] ?? $gameData[$field] ?? $default;
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
    <!-- Header -->    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="/" class="text-xbox-green hover:text-xbox-green-light transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-white">✏️ Modifica Gioco</h1>
        </div>
        <p class="text-gray-400">Aggiorna i dettagli di: <span class="text-xbox-green font-semibold"><?= htmlspecialchars($game['title']) ?></span></p>
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
        <form method="POST" action="/update/<?= $game['id'] ?>" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-300 mb-2">
                        Titolo del Gioco *
                    </label>
                    <input type="text" id="title" name="title" required
                           value="<?= htmlspecialchars(getFieldValue('title', $formData, $game)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['title']) ? 'border-red-500' : '' ?>">
                    <?= displayFieldError('title', $errors) ?>
                </div>

                <!-- Cover URL -->
                <div class="md:col-span-2">
                    <label for="cover_url" class="block text-sm font-medium text-gray-300 mb-2">
                        URL Copertina
                    </label>
                    <input type="url" id="cover_url" name="cover_url"
                           value="<?= htmlspecialchars(getFieldValue('cover_url', $formData, $game)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['cover_url']) ? 'border-red-500' : '' ?>"
                           placeholder="https://example.com/cover.jpg">
                    <p class="text-gray-500 text-sm mt-1">Inserisci l'URL di un'immagine per la copertina del gioco</p>
                    <?= displayFieldError('cover_url', $errors) ?>
                    <?php if (!empty(getFieldValue('cover_url', $formData, $game))): ?>
                        <img id="cover-preview" src="<?= htmlspecialchars(getFieldValue('cover_url', $formData, $game)) ?>" 
                             class="mt-2 w-32 h-40 object-cover rounded border border-xbox-green/30"
                             onerror="this.style.display='none'">
                    <?php endif; ?>
                </div>

                <!-- Release Year -->                <div>
                    <label for="release_year" class="block text-sm font-medium text-gray-300 mb-2">
                        Anno di Uscita *
                    </label>
                    <input type="number" id="release_year" name="release_year" required
                           min="1970" max="<?= date('Y') + 5 ?>" 
                           value="<?= getFieldValue('release_year', $formData, $game) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['release_year']) ? 'border-red-500' : '' ?>">
                    <?= displayFieldError('release_year', $errors) ?>
                </div>

                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-300 mb-2">
                        Genere *
                    </label>
                    <input type="text" id="genre" name="genre" required
                           value="<?= htmlspecialchars(getFieldValue('genre', $formData, $game)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['genre']) ? 'border-red-500' : '' ?>"
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
                    <?= displayFieldError('genre', $errors) ?>
                </div>

                <!-- Developer -->
                <div>
                    <label for="developer" class="block text-sm font-medium text-gray-300 mb-2">
                        Sviluppatore *
                    </label>
                    <input type="text" id="developer" name="developer" required
                           value="<?= htmlspecialchars(getFieldValue('developer', $formData, $game)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['developer']) ? 'border-red-500' : '' ?>">
                    <?= displayFieldError('developer', $errors) ?>
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-300 mb-2">
                        Publisher *
                    </label>
                    <input type="text" id="publisher" name="publisher" required
                           value="<?= htmlspecialchars(getFieldValue('publisher', $formData, $game)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['publisher']) ? 'border-red-500' : '' ?>">
                    <?= displayFieldError('publisher', $errors) ?>
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-300 mb-2">
                        Lingua
                    </label>                    <select id="language" name="language"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green <?= isset($errors['language']) ? 'border-red-500' : '' ?>">
                        <option value="Italiano" <?= getFieldValue('language', $formData, $game) === 'Italiano' ? 'selected' : '' ?>>Italiano</option>
                        <option value="Inglese" <?= getFieldValue('language', $formData, $game) === 'Inglese' ? 'selected' : '' ?>>Inglese</option>
                        <option value="Multilingua" <?= getFieldValue('language', $formData, $game) === 'Multilingua' ? 'selected' : '' ?>>Multilingua</option>
                        <option value="Inglese con sottotitoli" <?= getFieldValue('language', $formData, $game) === 'Inglese con sottotitoli' ? 'selected' : '' ?>>Inglese con sottotitoli</option>
                    </select>
                    <?= displayFieldError('language', $errors) ?>
                </div>

                <!-- Support Type -->
                <div>
                    <label for="support_type" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo di Supporto
                    </label>                    <select id="support_type" name="support_type"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green <?= isset($errors['support_type']) ? 'border-red-500' : '' ?>">
                        <option value="Digitale" <?= getFieldValue('support_type', $formData, $game) === 'Digitale' ? 'selected' : '' ?>>📱 Digitale</option>
                        <option value="Fisico" <?= getFieldValue('support_type', $formData, $game) === 'Fisico' ? 'selected' : '' ?>>💿 Fisico</option>
                        <option value="Entrambi" <?= getFieldValue('support_type', $formData, $game) === 'Entrambi' ? 'selected' : '' ?>>🎯 Entrambi</option>
                    </select>
                    <?= displayFieldError('support_type', $errors) ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                        Stato di Gioco
                    </label>                    <select id="status" name="status"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green <?= isset($errors['status']) ? 'border-red-500' : '' ?>">
                        <option value="Da iniziare" <?= getFieldValue('status', $formData, $game) === 'Da iniziare' ? 'selected' : '' ?>>📋 Da iniziare</option>
                        <option value="In corso" <?= getFieldValue('status', $formData, $game) === 'In corso' ? 'selected' : '' ?>>🎮 In corso</option>
                        <option value="Completato" <?= getFieldValue('status', $formData, $game) === 'Completato' ? 'selected' : '' ?>>✅ Completato</option>
                    </select>
                    <?= displayFieldError('status', $errors) ?>
                </div>

                <!-- Personal Rating -->
                <div>
                    <label for="personal_rating" class="block text-sm font-medium text-gray-300 mb-2">
                        Voto Personale (0-10)
                    </label>                    <input type="number" id="personal_rating" name="personal_rating"
                           min="0" max="10" step="0.1"
                           value="<?= getFieldValue('personal_rating', $formData, $game) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['personal_rating']) ? 'border-red-500' : '' ?>">
                    <?= displayFieldError('personal_rating', $errors) ?>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                        Note Personali
                    </label>                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent resize-none <?= isset($errors['notes']) ? 'border-red-500' : '' ?>"><?= htmlspecialchars(getFieldValue('notes', $formData, $game)) ?></textarea>
                    <?= displayFieldError('notes', $errors) ?>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-xbox-green/30">
                <div class="flex space-x-4">
                    <a href="/" class="text-gray-400 hover:text-white transition-colors">
                        ← Torna al catalogo
                    </a>
                    <a href="/game/<?= $game['id'] ?>" class="text-gray-400 hover:text-xbox-green transition-colors">
                        👁️ Visualizza dettagli
                    </a>
                </div>
                
                <div class="flex space-x-4">
                    <!-- Delete Button -->
                    <button type="button" onclick="confirmDelete()" 
                            class="px-6 py-3 bg-red-600/20 hover:bg-red-600/30 text-red-400 border border-red-600/30 rounded-lg transition-colors">
                        🗑️ Elimina
                    </button>
                    
                    <button type="submit" class="xbox-button text-white px-8 py-3 rounded-lg font-medium">
                        💾 Salva Modifiche
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="xbox-card rounded-lg p-6 m-4 max-w-md">
            <h3 class="text-xl font-semibold text-white mb-4">🗑️ Conferma Eliminazione</h3>
            <p class="text-gray-300 mb-6">
                Sei sicuro di voler eliminare "<span class="text-xbox-green"><?= htmlspecialchars($game['title']) ?></span>"? 
                Questa azione non può essere annullata.
            </p>
            <div class="flex space-x-4">
                <button onclick="cancelDelete()" 
                        class="flex-1 px-4 py-2 border border-gray-600 text-gray-300 rounded-lg hover:bg-gray-700 transition-colors">
                    Annulla
                </button>
                <form method="POST" action="/delete/<?= $game['id'] ?>" class="flex-1">
                    <button type="submit" 
                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

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

// Delete confirmation
function confirmDelete() {
    document.getElementById('delete-modal').classList.remove('hidden');
    document.getElementById('delete-modal').classList.add('flex');
}

function cancelDelete() {
    document.getElementById('delete-modal').classList.add('hidden');
    document.getElementById('delete-modal').classList.remove('flex');
}

// Close modal on outside click
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        cancelDelete();
    }
});
</script>

<!-- Include form enhancement script -->
<script src="/public/assets/js/form-enhancer.js"></script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
