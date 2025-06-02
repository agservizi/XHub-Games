<?php
$pageTitle = 'Pagina Non Trovata';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full text-center">
        <div class="xbox-card rounded-lg p-8">
            <!-- Xbox Controller Icon -->
            <div class="mb-6">
                <div class="text-8xl mb-4">🎮</div>
                <div class="text-6xl font-bold text-xbox-green mb-4">404</div>
            </div>
            
            <!-- Error Message -->
            <h1 class="text-2xl font-bold text-white mb-4">
                Oops! Pagina Non Trovata
            </h1>
            <p class="text-gray-400 mb-8">
                La pagina che stai cercando non esiste o è stata spostata. 
                Torna al catalogo per continuare a esplorare i tuoi giochi Xbox.
            </p>
            
            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="/" class="w-full xbox-button text-white px-6 py-3 rounded-lg font-medium block">
                    🏠 Torna al Catalogo
                </a>
                <a href="/nuovo-gioco" class="w-full bg-blue-600/20 hover:bg-blue-600/30 text-blue-400 border border-blue-600/30 px-6 py-3 rounded-lg font-medium block transition-colors">
                    ➕ Aggiungi Gioco
                </a>
            </div>
        </div>
        
        <!-- Fun Gaming Quote -->
        <div class="mt-8 text-center">
            <p class="text-gray-500 text-sm italic">
                "Un vero gamer non si arrende mai... nemmeno davanti a un 404!"
            </p>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.xbox-card {
    animation: float 3s ease-in-out infinite;
}
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/layout.php';
?>
