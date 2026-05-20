@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm p-6 border">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Users</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['users'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Roles</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['roles'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Permissions</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['permissions'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm p-6 border">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Activity Logs</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['activities'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-sm border">
    <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Recent Activity</h2>
    </div>
    <div class="divide-y">
        @forelse($recentActivities as $activity)
        <div class="px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">
                        by {{ $activity->causer?->name ?? 'System' }}
                        <span class="inline-block px-2 py-0.5 bg-gray-100 rounded text-xs ml-2">{{ $activity->log_name }}</span>
                    </p>
                </div>
            </div>
            <span class="text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}</span>
        </div>
        @empty
        <div class="px-6 py-8 text-center text-gray-500">
            No activity recorded yet.
        </div>
        @endforelse
    </div>
</div>
@endsection