@extends('layouts.admin')

@section('title', 'Activity Log')
@section('subtitle', 'System audit trail')

@section('breadcrumb')
<li class="breadcrumb-item active" aria-current="page">Activity Log</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Activity Log</h4>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" action="{{ route('admin.activity-log.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="log_name" class="form-select">
                    <option value="">All Logs</option>
                    <option value="rbac" {{ request('log_name') == 'rbac' ? 'selected' : '' }}>RBAC</option>
                    <option value="auth" {{ request('log_name') == 'auth' ? 'selected' : '' }}>Auth</option>
                    <option value="user" {{ request('log_name') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">
                    <i class="bi bi-search"></i> Filter
                </button>
            </div>
            @if(request('search') || request('log_name'))
            <div class="col-md-2">
                <a href="{{ route('admin.activity-log.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
            @endif
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Log</th>
                        <th>Description</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <img src="{{ $activity->causer?->avatar_url ?? 'https://ui-avatars.com/api/?name=System&size=32' }}" alt="Avatar">
                                </div>
                                <span>{{ $activity->causer?->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td><span class="badge bg-light-secondary">{{ $activity->log_name }}</span></td>
                        <td>{{ $activity->description }}</td>
                        <td>
                            @if($activity->subject_type)
                            <span class="badge bg-light-info">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td><small>{{ $activity->created_at->format('M d, Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.activity-log.show', $activity) }}" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No activity found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $activities->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection