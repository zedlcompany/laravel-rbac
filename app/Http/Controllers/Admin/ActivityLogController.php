<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::with('causer', 'subject');

        if ($search = $request->get('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        if ($logName = $request->get('log_name')) {
            $query->where('log_name', $logName);
        }

        if ($causerId = $request->get('causer_id')) {
            $query->where('causer_id', $causerId);
        }

        $activities = $query->latest()->paginate(config('rbac.admin.per_page'));

        $logNames = Activity::distinct('log_name')->pluck('log_name');

        return view('admin.activity-log.index', compact('activities', 'logNames'));
    }

    public function show(Activity $activity): View
    {
        $activity->load('causer', 'subject');

        return view('admin.activity-log.show', compact('activity'));
    }
}
