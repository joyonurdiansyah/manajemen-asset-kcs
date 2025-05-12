<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function permissionHome()
    {
        // Get all roles with their permissions
        $roles = Role::with('permissions')->get();
        
        // Get all permissions
        $permissions = Permission::all();
        
        return view('permission.akses', compact('roles', 'permissions'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Find the role
            $role = Role::findOrFail($request->role_id);
            
            // Sync permissions - this will remove any permissions not in the array
            // and add any that are new
            $role->syncPermissions($request->permissions ?? []);
            
            DB::commit();
            return redirect()->back()->with('success', 'Permissions for ' . $role->name . ' role updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating permissions: ' . $e->getMessage());
        }
    }
    
    /**
     * View for assigning roles to users
     */
    public function userRoles()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        
        return view('permission.user-roles', compact('users', 'roles'));
    }
    
    /**
     * Update roles for a user
     */
    public function updateUserRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        try {
            $user = User::findOrFail($request->user_id);
            $user->syncRoles($request->roles ?? []);
            
            DB::commit();
            return redirect()->back()->with('success', 'Roles for user ' . $user->name . ' updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error updating user roles: ' . $e->getMessage());
        }
    }
    
    /**
     * Get permissions for a role via AJAX
     */
    public function getRolePermissions(Request $request)
    {
        $role = Role::with('permissions')->findOrFail($request->role_id);
        return response()->json([
            'permissions' => $role->permissions
        ]);
    }
    
    /**
     * Get roles for a user via AJAX
     */
    public function getUserRoles(Request $request)
    {
        $user = User::with('roles')->findOrFail($request->user_id);
        return response()->json([
            'roles' => $user->roles
        ]);
    }
}