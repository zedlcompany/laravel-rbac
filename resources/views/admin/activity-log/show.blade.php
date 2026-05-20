@extends('layouts.admin')

@section('title', 'Activity Detail')

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border p-6">
        <dl class="space-y-4">
            <div>
                <dt class="text-sm font-medium text-gray-500">Description</dt>
                <dd class="text-gray-900">{{ $activity->description }}</dd>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Log Name</dt>
                    <dd><span class="inline-block px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800">{{ $activity->log_name }}</span></dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Event</dt>
                    <dd class="text-gray-900">{{ $activity->event ?? '-' }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Caused By</dt>
                    <dd class="text-gray-900">{{ $activity->causer?->name ?? 'System' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date</dt>
                    <dd class="text-gray-900">{{ $activity->created_at->format('M d, Y H:i:s') }}</dd>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject Type</dt>
                    <dd class="text-gray-900">{{ $activity->subject_type ? class_basename($activity->subject_type) : '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject ID</dt>
                    <dd class="text-gray-900">{{ $activity->subject_id ?? '-' }}</dd>
                </div>
            </div>
            @if($activity->properties && $activity->properties->count() > 0)
            <div>
                <dt class="text-sm font-medium text-gray-500 mb-2">Properties</dt>
                <dd>
                    <pre class="bg-gray-50 border rounded-lg p-4 text-sm overflow-x-auto">{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</pre>
                </dd>
            </div>
            @endif
        </dl>
        <div class="mt-6 pt-4 border-t">
            <a href="{{ route('admin.activity-log.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">&larr; Back to Activity Log</a>
        </div>
    </div>
</div>
@endsection