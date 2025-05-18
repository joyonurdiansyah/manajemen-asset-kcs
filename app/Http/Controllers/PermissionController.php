<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    public function permissionHome()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();
        
        return view('permission.akses', compact('roles', 'permissions', 'users'));
    }
    
    // Added update() method as an alias to updatePermissions()
    public function update(Request $request)
    {
        return $this->updatePermissions($request);
    }
    
    public function updatePermissions(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);
        
        try {
            DB::beginTransaction();
            
            $role = Role::findById($request->role_id);
            
            if (!$role) {
                return redirect()->back()->with('error', 'Role not found.');
            }
            
            // Sync permissions (this will add new ones and remove unselected ones)
            $permissions = $request->permissions ?? [];
            $role->syncPermissions($permissions);
            
            DB::commit();
            
            Log::info('Role permissions updated', [
                'role_id' => $role->id,
                'role_name' => $role->name,
                'permissions_count' => count($permissions),
                'updated_by' => auth()->id()
            ]);
            
            return redirect()->back()->with('success', "Permissions for role '{$role->name}' have been updated successfully.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update role permissions', [
                'error' => $e->getMessage(),
                'role_id' => $request->role_id,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->back()->with('error', 'Failed to update permissions. Please try again.');
        }
    }
    
    public function assignRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|string|exists:roles,name'
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::with('roles')->findOrFail($request->user_id);
            $role = Role::findByName($request->role_name);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.'
                ], 404);
            }
            
            // Check if user already has this role
            if ($user->hasRole($role)) {
                return response()->json([
                    'success' => false,
                    'message' => "User already has the '{$role->name}' role."
                ], 400);
            }
            
            // Assign the role
            $user->assignRole($role);
            
            // Reload user with roles for response
            $user->load('roles');
            
            DB::commit();
            
            Log::info('Role assigned to user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role_name' => $role->name,
                'assigned_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Role '{$role->name}' has been assigned to {$user->name} successfully.",
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign role to user', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'role_name' => $request->role_name,
                'assigned_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign role. Please try again.'
            ], 500);
        }
    }
    
    public function updateUserRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name'
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::with('roles')->findOrFail($request->user_id);
            $roleNames = $request->roles ?? [];
            
            // Get role instances
            $roles = Role::whereIn('name', $roleNames)->get();
            
            // Sync roles (this will add new ones and remove unselected ones)
            $user->syncRoles($roles);
            
            // Reload user with roles for response
            $user->load('roles');
            
            DB::commit();
            
            Log::info('User roles updated', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'roles' => $roleNames,
                'updated_by' => auth()->id()
            ]);
            
            $rolesCount = count($roleNames);
            $message = $rolesCount > 0 
                ? "User roles updated successfully. {$user->name} now has {$rolesCount} role(s)."
                : "All roles removed from {$user->name}.";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update user roles', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'roles' => $request->roles ?? [],
                'updated_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user roles. Please try again.'
            ], 500);
        }
    }
    
    public function removeRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_name' => 'required|string|exists:roles,name'
        ]);
        
        try {
            DB::beginTransaction();
            
            $user = User::with('roles')->findOrFail($request->user_id);
            $role = Role::findByName($request->role_name);
            
            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found.'
                ], 404);
            }
            
            // Check if user has this role
            if (!$user->hasRole($role)) {
                return response()->json([
                    'success' => false,
                    'message' => "User doesn't have the '{$role->name}' role."
                ], 400);
            }
            
            // Remove the role
            $user->removeRole($role);
            
            // Reload user with roles for response
            $user->load('roles');
            
            DB::commit();
            
            Log::info('Role removed from user', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'role_name' => $role->name,
                'removed_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Role '{$role->name}' has been removed from {$user->name} successfully.",
                'user' => $user
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to remove role from user', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id,
                'role_name' => $request->role_name,
                'removed_by' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove role. Please try again.'
            ], 500);
        }
    }
    
    public function getUserRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);
        
        try {
            $user = User::with('roles.permissions')->findOrFail($request->user_id);
            
            return response()->json([
                'success' => true,
                'user' => $user,
                'roles' => $user->roles
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to get user roles', [
                'error' => $e->getMessage(),
                'user_id' => $request->user_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user roles.'
            ], 500);
        }
    }
    
    public function searchUsers(Request $request)
    {
        $request->validate([
            'search' => 'string|max:255'
        ]);
        
        try {
            $search = $request->get('search', '');
            
            $users = User::with('roles')
                ->when($search, function ($query, $search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                    });
                })
                ->limit(20)
                ->get();
            
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to search users', [
                'error' => $e->getMessage(),
                'search' => $request->get('search')
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to search users.'
            ], 500);
        }
    }
}