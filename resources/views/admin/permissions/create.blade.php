@extends('layouts.admin')

@section('title', 'Create Permission')
@section('subtitle', 'Add a new permission to the system')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">New Permission</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Create Posts" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="e.g. posts.create" required>
                        @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Use format: module.action (e.g. posts.create, users.delete)</small>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="module" class="form-label">Module <span class="text-danger">*</span></label>
                        <input type="text" id="module" name="module" class="form-control @error('module') is-invalid @enderror" value="{{ old('module') }}" placeholder="e.g. posts" list="module-list" required>
                        <datalist id="module-list">
                            @foreach($modules as $module)
                            <option value="{{ $module }}">
                                @endforeach
                        </datalist>
                        @error('module')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" id="description" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" placeholder="Brief description of this permission">
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Create Permission
                </button>
                <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection