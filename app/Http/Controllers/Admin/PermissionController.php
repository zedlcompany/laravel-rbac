<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Permission::class);

        $query = Permission::withCount('roles');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($module = $request->get('module')) {
            $query->where('module', $module);
        }

        $permissions = $query->orderBy('module')->orderBy('name')->paginate(config('rbac.admin.per_page'));
        $modules = Permission::getModules();

        return view('admin.permissions.index', compact('permissions', 'modules'));
    }

    public function create(): View
    {
        $this->authorize('create', Permission::class);

        $modules = Permission::getModules();

        return view('admin.permissions.create', compact('modules'));
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        $this->authorize('update', $permission);

        $modules = Permission::getModules();

        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $this->authorize('update', $permission);

        $validated = $request->validated();

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize('delete', $permission);

        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete a permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
