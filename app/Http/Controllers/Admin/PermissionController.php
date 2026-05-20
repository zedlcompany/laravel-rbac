<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Request $request): View
    {
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
        $modules = Permission::getModules();

        return view('admin.permissions.create', compact('modules'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions', 'regex:/^[a-z0-9\.\-]+$/'],
            'description' => ['nullable', 'string', 'max:500'],
            'module' => ['required', 'string', 'max:255'],
        ]);

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission): View
    {
        $modules = Permission::getModules();

        return view('admin.permissions.edit', compact('permission', 'modules'));
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id), 'regex:/^[a-z0-9\.\-]+$/'],
            'description' => ['nullable', 'string', 'max:500'],
            'module' => ['required', 'string', 'max:255'],
        ]);

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'Cannot delete a permission that is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
