@extends('layouts.admin')

@section('title', 'Permissions Management')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search permissions..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="module" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Modules</option>
            @foreach($modules as $module)
            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Filter</button>
    </form>
    <a href="{{ route('admin.permissions.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">+ Add Permission</a>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Permission</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Module</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Roles</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($permissions as $permission)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div>
                        <p class="font-medium text-gray-900">{{ $permission->name }}</p>
                        <p class="text-xs text-gray-500">{{ $permission->description }}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 font-mono">{{ $permission->slug }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800">{{ $permission->module }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $permission->roles_count }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-sm text-blue-600 hover:text-blue-800">Edit</a>
                        <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">No permissions found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $permissions->withQueryString()->links() }}
</div>
@endsection