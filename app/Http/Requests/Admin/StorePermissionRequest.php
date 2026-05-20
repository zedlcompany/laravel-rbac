<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasPermission('permissions.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:permissions', 'regex:/^[a-z0-9\-\.]+$/'],
            'description' => ['nullable', 'string', 'max:500'],
            'module' => ['required', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug may only contain lowercase letters, numbers, hyphens, and dots.',
            'slug.unique' => 'This slug is already taken.',
            'module.required' => 'Module is required.',
        ];
    }
}
