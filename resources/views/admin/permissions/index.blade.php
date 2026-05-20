@extends('layouts.admin')

@section('title', 'Permissions')
@section('subtitle', 'Manage system permissions')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Permissions</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Permission List</h4>
        @permission('permissions.create')
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Permission
        </a>
        @endpermission
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('admin.permissions.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name or slug..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="module" class="form-select">
                    <option value="">All Modules</option>
                    @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            @if(request('search') || request('module'))
            <div class="col-md-2">
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
            @endif
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Module</th>
                        <th>Roles</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                    <tr>
                        <td class="fw-bold">{{ $permission->name }}</td>
                        <td><code>{{ $permission->slug }}</code></td>
                        <td><span class="badge bg-light-info">{{ $permission->module }}</span></td>
                        <td>{{ $permission->roles_count }}</td>
                        <td><small class="text-muted">{{ Str::limit($permission->description, 40) }}</small></td>
                        <td>
                            <div class="btn-group" role="group">
                                @permission('permissions.edit')
                                <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endpermission
                                @permission('permissions.delete')
                                <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endpermission
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No permissions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $permissions->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection