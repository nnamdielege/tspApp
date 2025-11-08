<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TSP Path Optimizer</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
    <div class="relative flex flex-col min-h-screen justify-center items-center px-6 py-12 bg-gradient-to-br from-indigo-50 via-white to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

        {{-- Top Navigation (Login / Register) --}}
        @if (Route::has('login'))
            <div class="absolute top-6 right-6 space-x-4 text-sm">
                @auth
                    <a href="{{ url('/dashboard') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="font-semibold text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Log in</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="font-semibold text-gray-700 hover:text-indigo-600 dark:text-gray-300 dark:hover:text-indigo-400">Register</a>
                    @endif
                @endauth
            </div>
        @endif

        {{-- Hero Section --}}
        <div class="text-center max-w-3xl">
            <div class="mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L3 10.5m0 0L9.75 4M3 10.5h13.5M21 21v-2a4 4 0 00-4-4H7" />
                </svg>
            </div>

            <h1 class="text-4xl font-extrabold tracking-tight mb-4">
                Optimal Path Finder Based on the <span class="text-indigo-600 dark:text-indigo-400">Travelling Salesman Problem (TSP)</span>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed mb-8">
                Discover the most efficient route through multiple destinations using advanced optimization algorithms.
                <br>Sign up today to gain access to tools and datasets that help you compute and visualize optimal paths.
            </p>

            @guest
                <div class="space-x-4">
                    <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow-md hover:bg-indigo-700 transition">
                        Register for Access
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 border border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-800 transition">
                        Log In
                    </a>
                </div>
            @else
                <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition">
                    Go to Dashboard
                </a>
            @endguest
        </div>

        {{-- Footer --}}
        <footer class="absolute bottom-4 text-xs text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} TBS — Built for Optimal Travel
        </footer>
    </div>
</body>
</html>
