<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .mobile-menu {
            display: none !important;
        }

        .mobile-menu-links {
            display: none !important;
            position: absolute;
            top: 4rem;
            left: 0;
            right: 0;
            flex-direction: column;
            background-color: white;
            border-top: 1px solid #e5e7eb;
            z-index: 50;
        }

        .dark .mobile-menu-links {
            background-color: #1f2937;
            border-top-color: #374151;
        }

        .mobile-menu-links a {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            display: block;
        }

        .dark .mobile-menu-links a {
            border-bottom-color: #374151;
        }

        @media (max-width: 768px) {
            .desktop-menu {
                display: none !important;
            }

            .mobile-menu {
                display: block !important;
            }

            .mobile-menu-open .mobile-menu-links {
                display: flex !important;
            }

            .mobile-menu-links {
                display: none !important;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Common Navigation Bar -->
        <nav class="bg-white dark:bg-gray-800 shadow" id="navbar">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                        <!-- Desktop Menu -->
                        <div class="desktop-menu ml-10 flex space-x-4">
                            <a href="{{ route('dashboard') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                                Dashboard
                            </a>
                            <a href="{{ route('optimal-path') }}"
                               class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                                Locate Optimal Path
                            </a>
                            <a href="{{ route('driver-logbook') }}" 
                                class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                                Driver Logbook
                            </a>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- User Dropdown Menu (Desktop) -->
                        <div class="desktop-menu relative">
                            <button id="userMenuBtn" class="px-3 py-2 rounded-md text-sm font-medium text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white flex items-center">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg z-50">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-t-lg">
                                    User Settings
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-b-lg">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Burger Menu Button (Mobile) -->
                        <button class="mobile-menu inline-flex items-center justify-center p-2 rounded-md text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white focus:outline-none" 
                                id="mobileMenuBtn" aria-label="Toggle menu">
                            <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu Links -->
                <div class="mobile-menu-links" id="mobileMenuLinks">
                    <a href="{{ route('dashboard') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                        Dashboard
                    </a>
                    <a href="{{ route('optimal-path') }}" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                        Locate Optimal Path
                    </a>
                    <a href="#" class="text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white">
                        User Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>
        <!-- End Navigation -->

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-gray-100 dark:bg-gray-900 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const navbar = document.getElementById('navbar');
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        mobileMenuBtn.addEventListener('click', function() {
            navbar.classList.toggle('mobile-menu-open');
        });

        // User Dropdown Toggle (Desktop)
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        // Close user dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = userDropdown.contains(event.target) || userMenuBtn.contains(event.target);
            if (!isClickInside) {
                userDropdown.classList.add('hidden');
            }
        });

        // Close menu when a link is clicked
        document.querySelectorAll('#mobileMenuLinks a').forEach(link => {
            link.addEventListener('click', function() {
                navbar.classList.remove('mobile-menu-open');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInside = navbar.contains(event.target);
            if (!isClickInside) {
                navbar.classList.remove('mobile-menu-open');
            }
        });
    </script>
</body>
</html>