<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-gray-900">{{ config('app.name', 'Laravel RBAC') }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @role('super-admin|admin')
                        <a href="{{ route('admin.dashboard') }}" class="text-sm text-blue-600 hover:text-blue-800">Admin Panel</a>
                        @endrole
                        <span class="text-sm text-gray-600">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm border p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Welcome, {{ auth()->user()->name }}!</h2>
                <p class="text-gray-600 mb-6">You are logged in successfully.</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <h3 class="font-semibold text-blue-900">Your Roles</h3>
                        <div class="mt-2 flex flex-wrap gap-1">
                            @forelse(auth()->user()->roles as $role)
                            <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">{{ $role->name }}</span>
                            @empty
                            <span class="text-sm text-gray-500">No roles assigned</span>
                            @endforelse
                        </div>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg border border-green-100">
                        <h3 class="font-semibold text-green-900">Your Permissions</h3>
                        <p class="mt-2 text-sm text-green-700">{{ auth()->user()->getAllPermissions()->count() }} permissions</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-100">
                        <h3 class="font-semibold text-purple-900">Account Type</h3>
                        <p class="mt-2 text-sm text-purple-700">{{ auth()->user()->isSocialUser() ? ucfirst(auth()->user()->provider) : 'Email' }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>