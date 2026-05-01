<!DOCTYPE html>
<html lang="id" class="<?= session()->get('theme') ?? 'light' ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>My Note</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <style>
        /* ========== DARK MODE OVERRIDE ========== */
        .dark {
            background-color: #111827;
            color: #f9fafb;
        }
        .dark body {
            background-color: #111827;
        }
        .dark .bg-white {
            background-color: #1f2937 !important;
        }
        .dark .bg-gray-100 {
            background-color: #111827 !important;
        }
        .dark .border-gray-300 {
            border-color: #374151 !important;
        }
        .dark .text-gray-700 {
            color: #e5e7eb !important;
        }
        .dark .text-gray-600 {
            color: #d1d5db !important;
        }
        .dark .text-gray-500 {
            color: #9ca3af !important;
        }
        /* Form input */
        .dark input, .dark textarea, .dark select {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        .dark input:focus, .dark textarea:focus, .dark select:focus {
            border-color: #60a5fa !important;
            outline: none;
        }
        /* Tombol warna dasar */
        .dark .bg-blue-600 { background-color: #2563eb !important; }
        .dark .bg-green-600 { background-color: #16a34a !important; }
        .dark .bg-yellow-500 { background-color: #d97706 !important; }
        .dark .bg-red-600 { background-color: #dc2626 !important; }
        .dark .bg-gray-500 { background-color: #6b7280 !important; }
        .dark .bg-blue-500 { background-color: #3b82f6 !important; }
        .dark .hover\:bg-blue-700:hover { background-color: #1d4ed8 !important; }
        .dark .hover\:bg-green-700:hover { background-color: #15803d !important; }
        .dark .hover\:bg-yellow-600:hover { background-color: #b45309 !important; }
        .dark .hover\:bg-red-700:hover { background-color: #b91c1c !important; }
        /* Alert */
        .dark .bg-green-100 { background-color: #065f46 !important; color: #d1fae5 !important; }
        .dark .bg-red-100 { background-color: #7f1a1a !important; color: #fecaca !important; }
        /* Link dan badge */
        .dark a.text-blue-600 { color: #60a5fa !important; }
        .dark a.text-yellow-600 { color: #fbbf24 !important; }
        .dark a.text-red-600 { color: #f87171 !important; }
        /* Transisi halus */
        * { transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease; }

        /* ========== TOMBOL DARK MODE (MANUAL) ========== */
        #theme-toggle .sun-icon {
            display: block;
        }
        #theme-toggle .moon-icon {
            display: none;
        }
        .dark #theme-toggle .sun-icon {
            display: none;
        }
        .dark #theme-toggle .moon-icon {
            display: block;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="<?= base_url('/dashboard') ?>" class="text-white text-xl font-bold">📝 My Note</a>
            <div class="flex items-center space-x-4">
                <?php if(session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('/notes') ?>" class="text-white hover:text-gray-200">Catatan</a>
                    <a href="<?= base_url('/plans') ?>" class="text-white hover:text-gray-200">Rencana</a>
                    <a href="<?= base_url('/logout') ?>" class="text-white hover:text-gray-200">Logout</a>
                <?php endif; ?>
                <!-- Tombol Dark Mode -->
                <button id="theme-toggle" class="text-white focus:outline-none">
                    <!-- Ikon matahari (terang) -->
                    <svg class="sun-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <!-- Ikon bulan (gelap) -->
                    <svg class="moon-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <?php if(session()->getFlashdata('success')): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 dark:bg-green-800 dark:border-green-600 dark:text-green-200 px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 dark:bg-red-800 dark:border-red-600 dark:text-red-200 px-4 py-3 rounded mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="text-center py-4 text-gray-500 text-sm">
        © <?= date('Y') ?> Dilengkapi Idle Activity - Logout Tanpa Aktivitas 
    </footer>

    <script>
        // Dark mode toggle
        (function() {
            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            let currentTheme = localStorage.getItem('theme') || '<?= session()->get('theme') ?? 'light' ?>';
            html.classList.add(currentTheme);
            if (currentTheme === 'dark') html.classList.remove('light');
            else html.classList.remove('dark');

            function setTheme(theme) {
                html.classList.remove('light', 'dark');
                html.classList.add(theme);
                localStorage.setItem('theme', theme);
                fetch('<?= base_url('set-theme') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'theme=' + theme
                }).catch(err => console.log('Gagal simpan tema ke server', err));
            }
            if (themeToggle) {
                themeToggle.addEventListener('click', () => {
                    const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }
        })();
    </script>

    <?php if(session()->get('isLoggedIn')): ?>
    <!-- Idle Logout tanpa konfirmasi (langsung logout setelah 10 detik tidak aktif) -->
    <script>
        (function() {
            let idleTimer;
            const idleTimeout = 10000; // 10 detik dalam milidetik

            function resetIdleTimer() {
                clearTimeout(idleTimer);
                idleTimer = setTimeout(function() {
                    window.location.href = '<?= base_url('logout') ?>';
                }, idleTimeout);
            }

            window.addEventListener('load', resetIdleTimer);
            document.addEventListener('mousemove', resetIdleTimer);
            document.addEventListener('keypress', resetIdleTimer);
            document.addEventListener('scroll', resetIdleTimer);
            document.addEventListener('click', resetIdleTimer);
        })();
    </script>

    <!-- Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= base_url('service-worker.js') ?>')
            .then(reg => console.log('Service Worker registered', reg))
            .catch(err => console.log('SW registration failed', err));
        }
    </script>
    <?php endif; ?>
</body>
</html>