@extends('layouts.admin')

@section('title', 'Create Role')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required placeholder="e.g. content-manager" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                        <input type="number" name="level" id="level" value="{{ old('level', 0) }}" min="0" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-400 mt-1">Higher level = more authority</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <label class="flex items-center gap-2 mt-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm">Active</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Permissions</label>
                    @foreach($permissions as $module => $modulePermissions)
                    <div class="mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">{{ $module }}</p>
                        <div class="grid grid-cols-2 gap-1">
                            @foreach($modulePermissions as $permission)
                            <label class="flex items-center gap-2 p-1.5 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm">{{ $permission->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Role</button>
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection