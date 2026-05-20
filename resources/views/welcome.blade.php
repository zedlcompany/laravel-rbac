<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel RBAC - Scalable Role-Based Access Control Template</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-900 text-white font-sans antialiased">
    <!-- Navigation -->
    <nav class="border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold">Laravel RBAC</span>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-gray-300 hover:text-white">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-sm font-medium rounded-lg transition-colors">Get Started</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-blue-600/10 to-transparent"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 relative">
            <div class="text-center max-w-3xl mx-auto">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-600/20 border border-blue-500/30 rounded-full text-blue-300 text-sm mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                    </svg>
                    Open Source on GitHub
                </div>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight mb-6">
                    Scalable <span class="text-blue-400">RBAC</span> for<br>Laravel Applications
                </h1>
                <p class="text-lg text-gray-400 mb-10 max-w-2xl mx-auto">
                    A production-ready Role-Based Access Control template with multi-role support, Socialite integration, activity logging, and a beautiful admin panel. Ready to use in minutes.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl transition-colors text-center">
                        Get Started Free
                    </a>
                    <a href="https://github.com/zedlcompany/laravel-rbac" target="_blank" class="w-full sm:w-auto px-8 py-3 border border-slate-600 hover:border-slate-500 text-gray-300 hover:text-white font-medium rounded-xl transition-colors text-center flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z" />
                        </svg>
                        View on GitHub
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold mb-4">Everything you need for access control</h2>
                <p class="text-gray-400 max-w-2xl mx-auto">Built with best practices and designed to scale with your application.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Multi-Role System</h3>
                    <p class="text-gray-400 text-sm">Users can have multiple roles with hierarchical levels. Super Admin bypasses all permission checks automatically.</p>
                </div>
                <!-- Feature 2 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-green-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Module-based Permissions</h3>
                    <p class="text-gray-400 text-sm">Permissions grouped by module (users.create, posts.edit). Assign to roles or directly to users.</p>
                </div>
                <!-- Feature 3 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Socialite Integration</h3>
                    <p class="text-gray-400 text-sm">Pre-configured Google & GitHub login. Add more providers with just a few lines of config.</p>
                </div>
                <!-- Feature 4 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-orange-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Activity Log & Audit Trail</h3>
                    <p class="text-gray-400 text-sm">Track every change with Spatie Activity Log. Full audit trail with filterable log viewer.</p>
                </div>
                <!-- Feature 5 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-cyan-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Admin Panel</h3>
                    <p class="text-gray-400 text-sm">Beautiful admin dashboard with user, role, and permission management. Built with Tailwind CSS + Alpine.js.</p>
                </div>
                <!-- Feature 6 -->
                <div class="bg-slate-800/50 border border-slate-700 rounded-xl p-6 hover:border-blue-500/50 transition-colors">
                    <div class="w-10 h-10 bg-pink-600/20 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Developer Friendly</h3>
                    <p class="text-gray-400 text-sm">Blade directives (@role, @permission), middleware, Artisan commands, and comprehensive config file.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start Section -->
    <section class="py-20 border-t border-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Up and running in 60 seconds</h2>
                <p class="text-gray-400">Clone, install, and start building.</p>
            </div>
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 font-mono text-sm">
                <div class="space-y-2">
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">git clone</span> https://github.com/zedlcompany/laravel-rbac.git</p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">cd</span> laravel-rbac</p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">composer install</span></p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">cp</span> .env.example .env</p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">php artisan</span> key:generate</p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">php artisan</span> rbac:setup</p>
                    <p><span class="text-gray-500">$</span> <span class="text-green-400">php artisan</span> serve</p>
                    <p class="text-gray-500 mt-4"># Login: admin@example.com / password</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack -->
    <section class="py-20 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Built with modern tools</h2>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-8 text-gray-400">
                <div class="flex items-center gap-2">
                    <span class="text-red-400 font-bold text-lg">L</span>
                    <span>Laravel 12</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-cyan-400 font-bold text-lg">T</span>
                    <span>Tailwind CSS</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-blue-400 font-bold text-lg">A</span>
                    <span>Alpine.js</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-yellow-400 font-bold text-lg">S</span>
                    <span>Sanctum</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-green-400 font-bold text-lg">S</span>
                    <span>Socialite</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-purple-400 font-bold text-lg">S</span>
                    <span>Spatie Activity Log</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-600 rounded flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-sm text-gray-400">&copy; {{ date('Y') }} Laravel RBAC Template. MIT License.</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="https://github.com/zedlcompany/laravel-rbac" target="_blank" class="text-sm text-gray-400 hover:text-white transition-colors">GitHub</a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Register</a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>