@extends('layouts.admin')

@section('title', 'Activity Detail')
@section('subtitle', 'View activity log entry')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.activity-log.index') }}">Activity Log</a></li>
<li class="breadcrumb-item active" aria-current="page">Detail</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Activity Details</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th class="w-25">ID</th>
                            <td>{{ $activity->id }}</td>
                        </tr>
                        <tr>
                            <th>Log Name</th>
                            <td><span class="badge bg-light-secondary">{{ $activity->log_name }}</span></td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $activity->description }}</td>
                        </tr>
                        <tr>
                            <th>Event</th>
                            <td>
                                @if($activity->event)
                                <span class="badge bg-light-primary">{{ $activity->event }}</span>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Caused By</th>
                            <td>
                                @if($activity->causer)
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <img src="{{ $activity->causer->avatar_url }}" alt="Avatar">
                                    </div>
                                    {{ $activity->causer->name }} ({{ $activity->causer->email }})
                                </div>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Subject</th>
                            <td>
                                @if($activity->subject_type)
                                {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <td>{{ $activity->created_at->format('F d, Y H:i:s') }}</td>
                        </tr>
                        @if($activity->batch_uuid)
                        <tr>
                            <th>Batch UUID</th>
                            <td><code>{{ $activity->batch_uuid }}</code></td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Properties</h4>
            </div>
            <div class="card-body">
                @if($activity->properties && $activity->properties->count() > 0)
                <pre class="bg-light p-3 rounded" style="max-height: 400px; overflow-y: auto;"><code>{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</code></pre>
                @else
                <p class="text-muted">No additional properties recorded.</p>
                @endif
            </div>
        </div>

        <a href="{{ route('admin.activity-log.index') }}" class="btn btn-secondary w-100">
            <i class="bi bi-arrow-left"></i> Back to Activity Log
        </a>
    </div>
</div>
@endsection