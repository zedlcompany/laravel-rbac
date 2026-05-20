@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Overview of your RBAC system')

@section('content')
<div class="row">
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon purple mb-2">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Users</h6>
                        <h6 class="font-extrabold mb-0">{{ $stats['users'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon blue mb-2">
                            <i class="bi bi-shield-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Roles</h6>
                        <h6 class="font-extrabold mb-0">{{ $stats['roles'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon green mb-2">
                            <i class="bi bi-key-fill"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Permissions</h6>
                        <h6 class="font-extrabold mb-0">{{ $stats['permissions'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-md-6">
        <div class="card">
            <div class="card-body px-4 py-4-5">
                <div class="row">
                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                        <div class="stats-icon red mb-2">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                        <h6 class="text-muted font-semibold">Activities</h6>
                        <h6 class="font-extrabold mb-0">{{ $stats['activities'] }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Recent Activity</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-lg">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Subject</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $activity)
                            <tr>
                                <td class="col-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-md">
                                            <img src="{{ $activity->causer?->avatar_url ?? 'https://ui-avatars.com/api/?name=System' }}" alt="Avatar">
                                        </div>
                                        <p class="font-bold ms-3 mb-0">{{ $activity->causer?->name ?? 'System' }}</p>
                                    </div>
                                </td>
                                <td class="col-auto">
                                    <p class="mb-0">{{ $activity->description }}</p>
                                </td>
                                <td class="col-auto">
                                    <span class="badge bg-light-primary">{{ class_basename($activity->subject_type ?? 'N/A') }}</span>
                                </td>
                                <td class="col-auto">
                                    <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No recent activity</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection