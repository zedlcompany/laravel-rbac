@extends('layouts.admin')

@section('title', 'Roles')
@section('subtitle', 'Manage system roles')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Roles</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-title">Role List</h4>
        @permission('roles.create')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Add Role
        </a>
        @endpermission
    </div>
    <div class="card-body">
        <!-- Search -->
        <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name or slug..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
            @if(request('search'))
            <div class="col-md-2">
                <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
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
                        <th>Level</th>
                        <th>Users</th>
                        <th>Permissions</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                    <tr>
                        <td class="fw-bold">{{ $role->name }}</td>
                        <td><code>{{ $role->slug }}</code></td>
                        <td>
                            <span class="badge bg-light-primary">{{ $role->level }}</span>
                        </td>
                        <td>{{ $role->users_count }}</td>
                        <td>{{ $role->permissions_count }}</td>
                        <td>
                            @if($role->is_active)
                            <span class="badge bg-success">Active</span>
                            @else
                            <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                @permission('roles.edit')
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endpermission
                                @permission('roles.delete')
                                @if($role->slug !== config('rbac.super_admin_role'))
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                                @endpermission
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No roles found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $roles->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection