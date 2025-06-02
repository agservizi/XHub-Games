<?php
$pageTitle = htmlspecialchars($game['title']);
ob_start();
?>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center space-x-4 mb-4">
            <a href="/" class="text-xbox-green hover:text-xbox-green-light transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-white">🎮 Dettagli Gioco</h1>
        </div>
    </div>

    <!-- Game Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Cover and Actions -->
        <div class="lg:col-span-1">
            <div class="xbox-card rounded-lg p-6 sticky top-24">
                <!-- Cover Image -->
                <div class="mb-6">
                    <?php if (!empty($game['cover_url'])): ?>
                        <img src="<?= htmlspecialchars($game['cover_url']) ?>" 
                             alt="<?= htmlspecialchars($game['title']) ?>"
                             class="w-full h-auto rounded-lg xbox-glow"
                             onerror="this.src='https://via.placeholder.com/400x500/1F1F1F/107C10?text=No+Image'">
                    <?php else: ?>
                        <div class="w-full h-96 bg-xbox-gray rounded-lg flex items-center justify-center">
                            <span class="text-6xl">🎮</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Quick Info -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Stato:</span>
                        <span class="<?php 
                            switch($game['status']) {
                                case 'Completato': echo 'text-green-400'; break;
                                case 'In corso': echo 'text-yellow-400'; break;
                                default: echo 'text-orange-400';
                            }
                        ?> font-medium">
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
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Voto:</span>
                            <div class="flex items-center">
                                <span class="text-yellow-400 font-bold text-lg"><?= $game['personal_rating'] ?></span>
                                <span class="text-yellow-400 ml-1">⭐</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Supporto:</span>
                        <span class="text-white">
                            <?php 
                                switch($game['support_type']) {
                                    case 'Fisico': echo '💿 Fisico'; break;
                                    case 'Digitale': echo '📱 Digitale'; break;
                                    default: echo '🎯 Entrambi';
                                }
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <a href="/edit/<?= $game['id'] ?>" 
                       class="w-full xbox-button text-white px-4 py-3 rounded-lg font-medium text-center block">
                        ✏️ Modifica Gioco
                    </a>
                    
                    <button onclick="shareGame()" 
                            class="w-full bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 border border-blue-600/30 px-4 py-3 rounded-lg font-medium transition-colors">
                        🔗 Condividi
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="xbox-card rounded-lg p-6">
                <!-- Title and Year -->
                <div class="mb-6">
                    <h1 class="text-4xl font-bold text-white mb-2">
                        <?= htmlspecialchars($game['title']) ?>
                    </h1>
                    <p class="text-xbox-green text-xl font-semibold">
                        <?= $game['release_year'] ?> • <?= htmlspecialchars($game['genre']) ?>
                    </p>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">Sviluppatore</h3>
                            <p class="text-white text-lg"><?= htmlspecialchars($game['developer']) ?></p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">Publisher</h3>
                            <p class="text-white text-lg"><?= htmlspecialchars($game['publisher']) ?></p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">Lingua</h3>
                            <p class="text-white text-lg"><?= htmlspecialchars($game['language']) ?></p>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-gray-400 uppercase tracking-wider mb-2">Aggiunto il</h3>
                            <p class="text-white text-lg"><?= date('d/m/Y', strtotime($game['created_at'])) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <?php if (!empty($game['notes'])): ?>
                    <div class="border-t border-xbox-green/30 pt-6">
                        <h3 class="text-xl font-semibold text-xbox-green mb-4">📝 Note Personali</h3>
                        <div class="bg-xbox-dark/50 p-4 rounded-lg border border-xbox-green/20">
                            <p class="text-gray-300 leading-relaxed whitespace-pre-wrap"><?= htmlspecialchars($game['notes']) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Stats Section -->
                <div class="border-t border-xbox-green/30 pt-6 mt-6">
                    <h3 class="text-xl font-semibold text-xbox-green mb-4">📊 Statistiche</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-xbox-dark/50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-white"><?= $game['release_year'] ?></div>
                            <div class="text-sm text-gray-400">Anno</div>
                        </div>
                        
                        <?php if (!empty($game['personal_rating'])): ?>
                            <div class="bg-xbox-dark/50 p-4 rounded-lg text-center">
                                <div class="text-2xl font-bold text-yellow-400"><?= $game['personal_rating'] ?>/10</div>
                                <div class="text-sm text-gray-400">Voto</div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="bg-xbox-dark/50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-xbox-green">
                                <?php 
                                    $daysSince = floor((time() - strtotime($game['created_at'])) / (60 * 60 * 24));
                                    echo $daysSince;
                                ?>
                            </div>
                            <div class="text-sm text-gray-400">Giorni in catalogo</div>
                        </div>
                        
                        <div class="bg-xbox-dark/50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-400">
                                <?= date('d/m', strtotime($game['updated_at'])) ?>
                            </div>
                            <div class="text-sm text-gray-400">Ultimo aggiornamento</div>
                        </div>
                    </div>
                </div>

                <!-- Gaming Status Progress -->
                <div class="border-t border-xbox-green/30 pt-6 mt-6">
                    <h3 class="text-xl font-semibold text-xbox-green mb-4">🎯 Progresso Gaming</h3>
                    <div class="space-y-4">
                        <?php
                        $statusSteps = ['Da iniziare', 'In corso', 'Completato'];
                        $currentStepIndex = array_search($game['status'], $statusSteps);
                        ?>
                        
                        <div class="flex items-center space-x-4">
                            <?php foreach ($statusSteps as $index => $step): ?>
                                <div class="flex items-center <?= $index < count($statusSteps) - 1 ? 'flex-1' : '' ?>">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-2 <?= 
                                        $index <= $currentStepIndex 
                                            ? 'bg-xbox-green border-xbox-green text-white' 
                                            : 'border-gray-600 text-gray-400'
                                    ?>">
                                        <?= $index + 1 ?>
                                    </div>
                                    <span class="ml-2 text-sm <?= 
                                        $index <= $currentStepIndex ? 'text-white font-medium' : 'text-gray-400'
                                    ?>">
                                        <?= $step ?>
                                    </span>
                                    
                                    <?php if ($index < count($statusSteps) - 1): ?>
                                        <div class="flex-1 h-0.5 mx-4 <?= 
                                            $index < $currentStepIndex ? 'bg-xbox-green' : 'bg-gray-600'
                                        ?>"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-sm text-gray-400">
                            Stato attuale: <span class="text-xbox-green font-medium"><?= $game['status'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function shareGame() {
    if (navigator.share) {
        navigator.share({
            title: '<?= htmlspecialchars($game['title']) ?>',
            text: 'Guarda questo gioco nel mio catalogo Xbox: <?= htmlspecialchars($game['title']) ?> (<?= $game['release_year'] ?>)',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            // Show a temporary notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-xbox-green text-white px-4 py-2 rounded-lg z-50';
            notification.textContent = 'Link copiato negli appunti!';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    }
}

// Add smooth animations on scroll
window.addEventListener('scroll', () => {
    const elements = document.querySelectorAll('.xbox-card');
    elements.forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            el.style.transform = 'translateY(0)';
            el.style.opacity = '1';
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layout.php';
?>
