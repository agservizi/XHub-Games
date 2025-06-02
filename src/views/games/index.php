<?php
$pageTitle = 'Catalogo Giochi';

// Include MessageHelper
require_once __DIR__ . '/../../utils/MessageHelper.php';

ob_start();
?>

<!-- Hero Section with Stats -->
<div class="bg-gradient-to-r from-xbox-dark to-xbox-gray border-b border-xbox-green/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-white mb-4">
                🎮 <span class="text-xbox-green">Xbox Games</span> Catalog
            </h1>
            <p class="text-gray-300 text-lg">
                La tua collezione personale di giochi Xbox Series X|S
            </p>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="xbox-card p-4 rounded-lg text-center xbox-glow">
                <div class="text-2xl font-bold text-xbox-green"><?= $stats['total_games'] ?></div>
                <div class="text-sm text-gray-400">Giochi Totali</div>
            </div>
            <div class="xbox-card p-4 rounded-lg text-center xbox-glow">
                <div class="text-2xl font-bold text-green-400"><?= $stats['completed_games'] ?></div>
                <div class="text-sm text-gray-400">Completati</div>
            </div>
            <div class="xbox-card p-4 rounded-lg text-center xbox-glow">
                <div class="text-2xl font-bold text-yellow-400"><?= $stats['in_progress_games'] ?></div>
                <div class="text-sm text-gray-400">In Corso</div>
            </div>
            <div class="xbox-card p-4 rounded-lg text-center xbox-glow">
                <div class="text-2xl font-bold text-orange-400"><?= number_format((float)($stats['average_rating'] ?? 0), 1) ?></div>
                <div class="text-sm text-gray-400">Voto Medio</div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Flash Messages -->
    <?= MessageHelper::displayAll() ?>

    <!-- Filters Section -->
    <div class="mb-8">
        <div class="xbox-card p-6 rounded-lg">
            <h2 class="text-xl font-semibold text-xbox-green mb-4">🔍 Filtri di Ricerca</h2>
            
            <form method="GET" class="space-y-4 md:space-y-0 md:grid md:grid-cols-4 md:gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Ricerca</label>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" 
                           placeholder="Titolo, sviluppatore..."
                           class="w-full px-3 py-2 bg-xbox-dark border border-xbox-green/30 rounded-md text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-xbox-green focus:border-transparent">
                </div>

                <!-- Genre Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Genere</label>
                    <select name="genre" class="w-full px-3 py-2 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="">Tutti i generi</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= htmlspecialchars($genre) ?>" 
                                    <?= (($_GET['genre'] ?? '') === $genre) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Stato</label>
                    <select name="status" class="w-full px-3 py-2 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="">Tutti gli stati</option>
                        <option value="Da iniziare" <?= (($_GET['status'] ?? '') === 'Da iniziare') ? 'selected' : '' ?>>📋 Da iniziare</option>
                        <option value="In corso" <?= (($_GET['status'] ?? '') === 'In corso') ? 'selected' : '' ?>>⏳ In corso</option>
                        <option value="Completato" <?= (($_GET['status'] ?? '') === 'Completato') ? 'selected' : '' ?>>✅ Completato</option>
                    </select>
                </div>

                <!-- Support Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Supporto</label>
                    <select name="support_type" class="w-full px-3 py-2 bg-xbox-dark border border-xbox-green/30 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-xbox-green">
                        <option value="">Tutti i supporti</option>
                        <option value="Fisico" <?= (($_GET['support_type'] ?? '') === 'Fisico') ? 'selected' : '' ?>>💿 Fisico</option>
                        <option value="Digitale" <?= (($_GET['support_type'] ?? '') === 'Digitale') ? 'selected' : '' ?>>📱 Digitale</option>
                        <option value="Entrambi" <?= (($_GET['support_type'] ?? '') === 'Entrambi') ? 'selected' : '' ?>>🎯 Entrambi</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-center">
                    <button type="submit" class="xbox-button text-white px-6 py-2 rounded-lg font-medium">
                        🔍 Filtra Giochi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export/Import Section -->
    <div class="flex justify-end gap-4 mb-4">
        <a href="/export-csv" class="xbox-button px-4 py-2 rounded text-white">Esporta CSV</a>
        <form method="POST" action="/import-csv" enctype="multipart/form-data" class="inline-block">
            <input type="file" name="csv_file" accept=".csv" class="text-sm text-gray-300">
            <button type="submit" class="xbox-button px-4 py-2 rounded text-white">Importa da CSV</button>
        </form>
    </div>

    <!-- Advanced Stats Section (Mockup) -->
    <div class="xbox-card p-6 rounded-lg mb-8">
        <h2 class="text-xl font-semibold text-xbox-green mb-4">📊 Statistiche Avanzate</h2>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <div class="text-3xl font-bold text-xbox-green mb-2">150</div>
                <div class="text-sm text-gray-400">Giochi Totali nel Catalogo</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-green-400 mb-2">75</div>
                <div class="text-sm text-gray-400">Giochi Completati</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-yellow-400 mb-2">50</div>
                <div class="text-sm text-gray-400">Giochi in Corso</div>
            </div>
            <div>
                <div class="text-3xl font-bold text-orange-400 mb-2">4.5</div>
                <div class="text-sm text-gray-400">Voto Medio Globale</div>
            </div>
        </div>
    </div>

    <!-- Games Grid -->
    <?php if (empty($games)): ?>
        <div class="text-center py-12">
            <div class="xbox-card p-8 rounded-lg">
                <div class="text-6xl mb-4">🎮</div>
                <h3 class="text-2xl font-semibold text-gray-300 mb-2">Nessun gioco trovato</h3>
                <p class="text-gray-400 mb-6">Non ci sono giochi che corrispondono ai filtri selezionati.</p>
                <a href="/nuovo-gioco" class="xbox-button text-white px-6 py-3 rounded-lg font-medium inline-flex items-center">
                    ➕ Aggiungi il tuo primo gioco
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($games as $game): ?>
                <div class="xbox-card rounded-lg overflow-hidden xbox-glow group">
                    <!-- Cover Image -->
                    <div class="aspect-w-3 aspect-h-4 bg-xbox-gray">
                        <?php if (!empty($game['cover_url'])): ?>
                            <img src="<?= htmlspecialchars($game['cover_url']) ?>" 
                                 alt="<?= htmlspecialchars($game['title']) ?>"
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                 onerror="this.src='https://via.placeholder.com/300x400/1F1F1F/107C10?text=No+Image'">
                        <?php else: ?>
                            <div class="w-full h-48 bg-xbox-gray flex items-center justify-center">
                                <span class="text-4xl">🎮</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Game Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg text-white mb-2 truncate">
                            <?= htmlspecialchars($game['title']) ?>
                        </h3>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Anno:</span>
                                <span class="text-white"><?= $game['release_year'] ?></span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-400">Genere:</span>
                                <span class="text-xbox-green font-medium"><?= htmlspecialchars($game['genre']) ?></span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-400">Stato:</span>
                                <span class="<?php 
                                    switch($game['status']) {
                                        case 'Completato': echo 'text-green-400'; break;
                                        case 'In corso': echo 'text-yellow-400'; break;
                                        default: echo 'text-orange-400';
                                    }
                                ?>">
                                    <?php 
                                        switch($game['status']) {
                                            case 'Completato': echo '✅ ' . $game['status']; break;
                                            case 'In corso': echo '⏳ ' . $game['status']; break;
                                            default: echo '📋 ' . $game['status'];
                                        }
                                    ?>
                                </span>
                            </div>
                            
                            <?php if (!empty($game['personal_rating'])): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-400">Voto:</span>
                                    <div class="flex items-center">
                                        <span class="text-yellow-400 font-bold"><?= $game['personal_rating'] ?></span>
                                        <span class="text-yellow-400 ml-1">⭐</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <a href="/game/<?= $game['id'] ?>" 
                               class="flex-1 bg-xbox-green/20 hover:bg-xbox-green/30 text-xbox-green px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                                👁️ Dettagli
                            </a>
                            <a href="/edit/<?= $game['id'] ?>" 
                               class="flex-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 px-3 py-2 rounded text-sm font-medium text-center transition-colors">
                                ✏️ Modifica
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
