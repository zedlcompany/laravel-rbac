@extends('layouts.admin')

@section('title', 'Roles Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search roles..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Search</button>
    </form>
    <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">+ Add Role</a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Level</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permissions</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($roles as $role)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-medium text-gray-900">{{ $role->name }}</p>
                        <p class="text-xs text-gray-500">{{ $role->description }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $role->slug }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $role->level }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $role->users_count }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $role->permissions_count }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2 py-0.5 text-xs rounded-full {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $role->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
                        @if($role->slug !== config('rbac.super_admin_role'))
                        <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No roles found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $roles->withQueryString()->links() }}
</div>
@endsection