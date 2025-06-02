<!DOCTYPE html>
<html lang="it" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Xbox Games Catalog</title>
      <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'xbox-green': '#107C10',
                        'xbox-green-light': '#13A10E',
                        'xbox-green-dark': '#0E7A0D',
                        'xbox-dark': '#181A1B',
                        'xbox-dark-light': '#1F1F1F',
                        'xbox-gray': '#2D2D2D',
                        'xbox-light': '#F5F5F5'
                    },
                    fontFamily: {
                        'gaming': ['Segoe UI', 'Roboto', 'system-ui', 'sans-serif']
                    },
                    boxShadow: {
                        'xbox-glow': '0 0 20px rgba(16, 124, 16, 0.5)',
                        'xbox-glow-strong': '0 0 30px rgba(16, 124, 16, 0.8)'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Xbox Gaming Styles -->
    <link rel="stylesheet" href="/public/assets/css/xbox-gaming.css">
    
    <!-- Custom Styles -->
    <style>
        .xbox-glow {
            box-shadow: 0 0 20px rgba(16, 124, 16, 0.3);
            transition: all 0.3s ease;
        }
        .xbox-glow:hover {
            box-shadow: 0 0 30px rgba(16, 124, 16, 0.6);
            transform: translateY(-2px);
        }
        .xbox-card {
            background: linear-gradient(135deg, #1F1F1F 0%, #2D2D2D 100%);
            border: 1px solid rgba(16, 124, 16, 0.3);
        }
        .xbox-button {
            background: linear-gradient(135deg, #107C10 0%, #13A10E 100%);
            transition: all 0.3s ease;
        }
        .xbox-button:hover {
            background: linear-gradient(135deg, #13A10E 0%, #0E7A0D 100%);
            box-shadow: 0 0 20px rgba(16, 124, 16, 0.6);
        }
        .rating-stars {
            color: #FFD700;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1F1F1F;
        }
        ::-webkit-scrollbar-thumb {
            background: #107C10;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #13A10E;
        }
    </style>
</head>
<body class="h-full bg-xbox-dark text-xbox-light font-gaming antialiased">
    <!-- Navigation -->
    <nav class="bg-xbox-dark-light border-b border-xbox-green/30 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-3 hover:opacity-80 transition-opacity">
                        <div class="w-10 h-10 bg-xbox-green rounded-lg flex items-center justify-center xbox-glow">
                            <!-- Xbox Original Icon SVG -->
                            <svg class="w-7 h-7" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="24" cy="24" r="22" fill="#107C10" stroke="#fff" stroke-width="2"/>
                                <path d="M24 10C18.5 13.5 13.5 20.5 13.5 28C13.5 32.5 17.5 36.5 24 36.5C30.5 36.5 34.5 32.5 34.5 28C34.5 20.5 29.5 13.5 24 10Z" fill="#fff"/>
                                <path d="M24 10C27.5 13.5 32.5 20.5 32.5 28C32.5 32.5 28.5 36.5 24 36.5C19.5 36.5 15.5 32.5 15.5 28C15.5 20.5 20.5 13.5 24 10Z" fill="#107C10"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-xbox-green">Xbox Games</h1>
                            <p class="text-xs text-gray-400">Catalog</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/" class="text-gray-300 hover:text-xbox-green px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        🏠 Home
                    </a>
                    <a href="/create" class="xbox-button text-white px-4 py-2 rounded-lg text-sm font-medium">
                        ➕ Aggiungi Gioco
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-400 hover:text-xbox-green" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-xbox-dark-light border-t border-xbox-green/30">
                <a href="/" class="text-gray-300 hover:text-xbox-green block px-3 py-2 rounded-md text-base font-medium">
                    🏠 Home
                </a>
                <a href="/create" class="text-gray-300 hover:text-xbox-green block px-3 py-2 rounded-md text-base font-medium">
                    ➕ Aggiungi Gioco
                </a>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-600 border border-green-500 text-white px-4 py-3 rounded-lg relative xbox-glow" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['success']) ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Chiudi</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-600 border border-red-500 text-white px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Chiudi</span>
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="min-h-screen">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-xbox-dark-light border-t border-xbox-green/30 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-400 text-sm">
                    © 2025 Xbox Games Catalog. Creato con ❤️ per i gamer Xbox.
                </p>
                <p class="text-gray-500 text-xs mt-1">
                    Powered by PHP & TailwindCSS
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 300);
            });
        }, 5000);

        // Add smooth scroll behavior
        document.documentElement.style.scrollBehavior = 'smooth';
    </script>
</body>
</html>
