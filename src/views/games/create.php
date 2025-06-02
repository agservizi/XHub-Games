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

// DEBUG: Mostra se la view viene caricata e lo stato delle variabili
if (isset($_GET['debug']) && $_GET['debug'] === '1') {
    echo '<pre style="background:#222;color:#0f0;padding:1em;">DEBUG create.php\n';
    echo 'formData: ' . print_r($formData, true) . "\n";
    echo 'errors: ' . print_r($errors, true) . "\n";
    echo '</pre>';
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

    <!-- Autocomplete Xbox Game Search -->
    <div class="mb-8">
        <label for="game-autocomplete" class="block text-sm font-medium text-xbox-green mb-2">Cerca gioco da database Xbox</label>
        <input type="text" id="game-autocomplete" class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent" placeholder="Cerca un gioco Xbox (es. Halo, Forza...)" autocomplete="off">
        <ul id="autocomplete-list" class="bg-xbox-dark border border-xbox-green/30 rounded-md mt-1 max-h-56 overflow-y-auto hidden"></ul>
    </div>

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
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['release_year']) ? 'border-red-500' : '' ?>"
                           placeholder="es. 2023">
                    <?= displayFieldError('release_year', $errors) ?>
                </div>

                <!-- Genre -->
                <div>
                    <label for="genre" class="block text-sm font-medium text-gray-300 mb-2">
                        Genere *
                    </label>
                    <input type="text" id="genre" name="genre" required
                           value="<?= htmlspecialchars(getFieldValue('genre', $formData)) ?>"
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
                           value="<?= htmlspecialchars(getFieldValue('developer', $formData)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['developer']) ? 'border-red-500' : '' ?>"
                           placeholder="es. 343 Industries">
                    <?= displayFieldError('developer', $errors) ?>
                </div>

                <!-- Publisher -->
                <div>
                    <label for="publisher" class="block text-sm font-medium text-gray-300 mb-2">
                        Publisher *
                    </label>
                    <input type="text" id="publisher" name="publisher" required
                           value="<?= htmlspecialchars(getFieldValue('publisher', $formData)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['publisher']) ? 'border-red-500' : '' ?>"
                           placeholder="es. Microsoft Studios">
                    <?= displayFieldError('publisher', $errors) ?>
                </div>

                <!-- Language -->
                <div>
                    <label for="language" class="block text-sm font-medium text-gray-300 mb-2">
                        Lingua
                    </label>
                    <select id="language" name="language"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="Italiano" <?= getFieldValue('language', $formData, 'Italiano') === 'Italiano' ? 'selected' : '' ?>>Italiano</option>
                        <option value="Inglese" <?= getFieldValue('language', $formData) === 'Inglese' ? 'selected' : '' ?>>Inglese</option>
                        <option value="Multilingua" <?= getFieldValue('language', $formData) === 'Multilingua' ? 'selected' : '' ?>>Multilingua</option>
                        <option value="Inglese con sottotitoli" <?= getFieldValue('language', $formData) === 'Inglese con sottotitoli' ? 'selected' : '' ?>>Inglese con sottotitoli</option>
                    </select>
                </div>

                <!-- Support Type -->
                <div>
                    <label for="support_type" class="block text-sm font-medium text-gray-300 mb-2">
                        Tipo di Supporto
                    </label>
                    <select id="support_type" name="support_type"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green <?= isset($errors['support_type']) ? 'border-red-500' : '' ?>">
                        <option value="Digitale" <?= getFieldValue('support_type', $formData) === 'Digitale' ? 'selected' : '' ?>>📱 Digitale</option>
                        <option value="Fisico" <?= getFieldValue('support_type', $formData) === 'Fisico' ? 'selected' : '' ?>>💿 Fisico</option>
                        <option value="Entrambi" <?= getFieldValue('support_type', $formData) === 'Entrambi' ? 'selected' : '' ?>>🎯 Entrambi</option>
                    </select>
                    <?= displayFieldError('support_type', $errors) ?>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                        Stato di Gioco
                    </label>
                    <select id="status" name="status"
                            class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green <?= isset($errors['status']) ? 'border-red-500' : '' ?>">
                        <option value="Da iniziare" <?= getFieldValue('status', $formData) === 'Da iniziare' ? 'selected' : '' ?>>📋 Da iniziare</option>
                        <option value="In corso" <?= getFieldValue('status', $formData) === 'In corso' ? 'selected' : '' ?>>⏳ In corso</option>
                        <option value="Completato" <?= getFieldValue('status', $formData) === 'Completato' ? 'selected' : '' ?>>✅ Completato</option>
                    </select>
                    <?= displayFieldError('status', $errors) ?>
                </div>

                <!-- Personal Rating -->
                <div>
                    <label for="personal_rating" class="block text-sm font-medium text-gray-300 mb-2">
                        Voto Personale (0-10)
                    </label>
                    <input type="number" id="personal_rating" name="personal_rating"
                           min="0" max="10" step="0.1"
                           value="<?= htmlspecialchars(getFieldValue('personal_rating', $formData)) ?>"
                           class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent <?= isset($errors['personal_rating']) ? 'border-red-500' : '' ?>"
                           placeholder="es. 8.5">
                    <?= displayFieldError('personal_rating', $errors) ?>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                        Note Personali
                    </label>
                    <textarea id="notes" name="notes" rows="4"
                              class="w-full px-4 py-3 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent resize-none"
                              placeholder="Le tue impressioni, commenti o note sul gioco..."><?= htmlspecialchars(getFieldValue('notes', $formData)) ?></textarea>
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

const input = document.getElementById('game-autocomplete');
const list = document.getElementById('autocomplete-list');

input.addEventListener('input', function() {
    const q = this.value.trim();
    if (q.length < 2) {
        list.innerHTML = '';
        list.classList.add('hidden');
        return;
    }
    fetch(`/api.php?action=suggest_game&title=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
            if (!Array.isArray(data) || !data.length) {
                list.innerHTML = '<li class="px-4 py-2 text-gray-400">Nessun risultato</li>';
                list.classList.remove('hidden');
                return;
            }
            list.innerHTML = data.map(game => `<li class='px-4 py-2 hover:bg-xbox-green/20 cursor-pointer' data-title="${game.title.replace(/&/g, '&amp;')}" data-year="${game.release_year||''}" data-genre="${game.genre||''}" data-developer="${game.developer||''}" data-publisher="${game.publisher||''}" data-cover="${game.cover_url||''}">${game.title} <span class='text-xs text-gray-400'>${game.release_year||''}</span></li>`).join('');
            list.classList.remove('hidden');
        })
        .catch(() => {
            list.innerHTML = '<li class="px-4 py-2 text-red-400">Errore connessione API</li>';
            list.classList.remove('hidden');
        });
});

list.addEventListener('click', function(e) {
    if (e.target && e.target.matches('li[data-title]')) {
        // Compila i campi del form solo se esistono
        const setVal = (id, val) => { const el = document.getElementById(id); if (el && val) el.value = val; };
        setVal('title', e.target.dataset.title);
        setVal('release_year', e.target.dataset.year);
        setVal('genre', e.target.dataset.genre);
        setVal('developer', e.target.dataset.developer);
        setVal('publisher', e.target.dataset.publisher);
        setVal('cover_url', e.target.dataset.cover);
        list.innerHTML = '';
        list.classList.add('hidden');
        input.value = '';
    }
});

document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !list.contains(e.target)) {
        list.classList.add('hidden');
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
