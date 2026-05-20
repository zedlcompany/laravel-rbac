@extends('layouts.admin')

@section('title', 'Create Permission')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form method="POST" action="{{ route('admin.permissions.store') }}">
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
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required placeholder="e.g. posts.create" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                        @error('slug')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="module" class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                    <input type="text" name="module" id="module" value="{{ old('module') }}" required placeholder="e.g. posts" list="modules-list" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('module') border-red-500 @enderror">
                    <datalist id="modules-list">
                        @foreach($modules as $module)
                        <option value="{{ $module }}">
                            @endforeach
                    </datalist>
                    @error('module')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="2" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Permission</button>
                <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection