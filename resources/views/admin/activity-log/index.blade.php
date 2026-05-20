@extends('layouts.admin')

@section('title', 'Activity Log')

@section('content')
<div class="flex items-center justify-between mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search activity..." class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <select name="log_name" class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            @foreach($logNames as $logName)
            <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>{{ ucfirst($logName) }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">Filter</button>
    </form>
</div>

<div class="bg-white rounded-xl shadow-sm border overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            @forelse($activities as $activity)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm text-gray-900">{{ $activity->description }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $activity->causer?->name ?? 'System' }}</td>
                <td class="px-6 py-4">
                    <span class="inline-block px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-800">{{ $activity->log_name }}</span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    @if($activity->subject)
                    {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                    @else
                    -
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $activity->created_at->format('M d, Y H:i') }}</td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.activity-log.show', $activity) }}" class="text-sm text-blue-600 hover:text-blue-800">Details</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">No activity recorded yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $activities->withQueryString()->links() }}
</div>
@endsection