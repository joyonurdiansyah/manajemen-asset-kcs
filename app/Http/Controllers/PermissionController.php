<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function permissionHome()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('permission.akses', compact('roles', 'permissions'));
    }

    public function update(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions ?? []); // null fallback untuk kosong

        return redirect()->back()->with('success', 'Permissions updated successfully!');
    }
}
