@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Role & User Management</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Role & User Management</li>
        </ol>
    </nav>
</div>

<style>
    .permission-group {
        border-left: 3px solid #4154f1;
        padding-left: 15px;
        margin-bottom: 25px;
    }
    
    .permission-title {
        font-weight: 600;
        color: #012970;
        margin-bottom: 15px;
    }
    
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #4154f1;
        border-color: #4154f1;
    }
    
    .role-card {
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(1, 41, 112, 0.1);
        transition: all 0.3s;
    }
    
    .role-card:hover {
        box-shadow: 0 0 30px rgba(1, 41, 112, 0.15);
    }
    
    .role-header {
        padding: 15px;
        border-bottom: 1px solid #ebeef4;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .role-body {
        padding: 20px;
    }
    
    .role-icon {
        font-size: 24px;
        color: #4154f1;
        margin-right: 10px;
    }
    
    .close-btn {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .close-btn:hover {
        color: #dc3545;
        transform: scale(1.1);
    }
    
    .permission-form-container {
        transition: all 0.4s ease-in-out;
        transform-origin: top;
    }
    
    .permission-form-container.closing {
        opacity: 0;
        transform: scaleY(0.8);
        max-height: 0;
        overflow: hidden;
    }
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .role-cards {
            grid-template-columns: 1fr;
        }
    }
    
    /* Selection indicator */
    .role-selector-container {
        transition: all 0.3s ease;
    }
    
    .role-selector-container.has-selection {
        border-left: 4px solid #4154f1;
        background: linear-gradient(90deg, rgba(65, 84, 241, 0.1) 0%, rgba(255, 255, 255, 0) 100%);
    }
    
    /* Tab styles */
    .nav-tabs .nav-link {
        border: none;
        background: transparent;
        color: #012970;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    
    .nav-tabs .nav-link.active {
        background: transparent;
        border-bottom: 3px solid #4154f1;
        color: #4154f1;
        font-weight: 600;
    }
    
    .nav-tabs .nav-link:hover {
        border-bottom: 3px solid #4154f199;
        color: #4154f1;
    }
    
    /* User management styles */
    .user-card {
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(1, 41, 112, 0.1);
        transition: all 0.3s;
        margin-bottom: 20px;
    }
    
    .user-card:hover {
        box-shadow: 0 0 30px rgba(1, 41, 112, 0.15);
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(45deg, #4154f1, #677ce4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 16px;
    }
    
    .role-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .user-search-container {
        position: relative;
    }
    
    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-input {
        padding-left: 35px;
    }
    
    /* User role management form styles */
    .user-role-form-container {
        transition: all 0.4s ease-in-out;
        transform-origin: top;
    }
    
    .user-role-form-container.closing {
        opacity: 0;
        transform: scaleY(0.8);
        max-height: 0;
        overflow: hidden;
    }
</style>

<section class="section">
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-1"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Tab Navigation -->
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="managementTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="role-permissions-tab" data-bs-toggle="tab" 
                                    data-bs-target="#role-permissions" type="button" role="tab">
                                <i class="bi bi-shield-lock me-2"></i>Role Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="user-roles-tab" data-bs-toggle="tab" 
                                    data-bs-target="#user-roles" type="button" role="tab">
                                <i class="bi bi-people me-2"></i>User Roles
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Tab Content -->
            <div class="tab-content" id="managementTabContent">
                
                <!-- Role Permissions Tab -->
                <div class="tab-pane fade show active" id="role-permissions" role="tabpanel">
                    
                    <!-- Role selector -->
                    <div class="mb-4 card role-selector-container">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="card-title">Select Role to Configure</h5>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-primary" style="font-size: 1.2rem;"></i>
                                        <select id="roleSelector" class="form-select" style="max-width: 300px;">
                                            <option selected disabled>Choose a role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="clearSelection" style="display: none;">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelectionBtn">
                                        <i class="bi bi-x-circle me-1"></i> Clear Selection
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Permission form - initially hidden -->
                    <div id="permissionForm" class="permission-form-container" style="display: none;">
                        <div class="card role-card">
                            <form action="{{ route('permissions.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="role_id" id="selectedRoleId">
                                
                                <div class="role-header">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-shield-lock role-icon"></i>
                                        <h5 class="mb-0 card-title" id="roleName"></h5>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-primary me-3" id="permissionCount"></span>
                                        <button type="button" class="close-btn" id="closePermissionForm" title="Close permission form">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="role-body">
                                    <div class="row">
                                        <!-- Permission groups will be generated dynamically -->
                                        <div class="permission-groups">
                                            <!-- Assets Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Assets Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_assets') !== false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="assets">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_assets', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Sites Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Sites Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_sites') !== false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="sites">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_sites', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Divisions Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Divisions Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_divisions') !== false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="divisions">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_divisions', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Categories Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Categories Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_categories') !== false && strpos($permission->name, '_subcategories') === false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="categories">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_categories', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Subcategories Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Subcategories Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_subcategories') !== false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="subcategories">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_subcategories', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Permissions Group -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Permission Management</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(strpos($permission->name, '_permissions') !== false)
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="permissions">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_permissions', '', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                            
                                            <!-- Other Permissions -->
                                            <div class="permission-group">
                                                <h6 class="permission-title">Other Permissions</h6>
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        @if(
                                                            strpos($permission->name, '_assets') === false &&
                                                            strpos($permission->name, '_sites') === false &&
                                                            strpos($permission->name, '_divisions') === false &&
                                                            strpos($permission->name, '_categories') === false &&
                                                            strpos($permission->name, '_subcategories') === false &&
                                                            strpos($permission->name, '_permissions') === false
                                                        )
                                                            <div class="mb-3 col-md-3 col-sm-6">
                                                                <div class="form-check">
                                                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                                                        id="perm_{{ $permission->id }}"
                                                                        name="permissions[]"
                                                                        value="{{ $permission->name }}"
                                                                        data-group="other">
                                                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 d-flex justify-content-between align-items-center">
                                        <div>
                                            <button type="button" class="btn btn-outline-primary btn-sm me-2" id="selectAll">
                                                <i class="bi bi-check-all me-1"></i> Select All
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="deselectAll">
                                                <i class="bi bi-x-lg me-1"></i> Deselect All
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-save me-1"></i> Update Permissions
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Role Cards -->
                    <div class="mt-4 row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Role Overview</h5>
                                    <div class="row role-cards" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                                        @foreach($roles as $role)
                                        <div class="role-card card">
                                            <div class="role-header">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-person-badge-fill role-icon"></i>
                                                    <h5 class="mb-0 card-title">{{ $role->name }}</h5>
                                                </div>
                                                <span class="badge bg-primary">{{ $role->permissions->count() }} permissions</span>
                                            </div>
                                            <div class="role-body">
                                                <div class="flex-wrap gap-1 d-flex">
                                                    @foreach($role->permissions->take(5) as $permission)
                                                        <span class="badge bg-light text-dark">
                                                            {{ ucfirst(explode('_', $permission->name)[0]) }}
                                                        </span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 5)
                                                        <span class="badge bg-secondary">
                                                            +{{ $role->permissions->count() - 5 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                                <button class="mt-3 btn btn-sm btn-outline-primary edit-role-btn" 
                                                        data-role-id="{{ $role->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit Permissions
                                                </button>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- User Roles Tab -->
                <div class="tab-pane fade" id="user-roles" role="tabpanel">
                    
                    <!-- User Search & Selection -->
                    <div class="mb-4 card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h5 class="card-title">Assign Role to User</h5>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <label for="userSearch" class="form-label">Search User</label>
                                            <div class="user-search-container">
                                                <i class="bi bi-search search-icon"></i>
                                                <input type="text" class="form-control search-input" id="userSearch" 
                                                       placeholder="Search by name or email...">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="userSelector" class="form-label">Select User</label>
                                            <select id="userSelector" class="form-select">
                                                <option selected disabled>Choose a user</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" 
                                                            data-name="{{ $user->name }}" 
                                                            data-email="{{ $user->email }}">
                                                        {{ $user->name }} ({{ $user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="roleAssignSelector" class="form-label">Select Role</label>
                                            <select id="roleAssignSelector" class="form-select">
                                                <option selected disabled>Choose a role</option>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-primary me-2" id="assignRoleBtn" disabled>
                                            <i class="bi bi-person-plus me-1"></i> Assign Role
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" id="clearUserSelection">
                                            <i class="bi bi-x-circle me-1"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Selected User Info -->
                    <div id="selectedUserInfo" class="mb-4 card" style="display: none;">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3" id="userAvatar"></div>
                                    <div>
                                        <h6 class="mb-1" id="selectedUserName"></h6>
                                        <small class="text-muted" id="selectedUserEmail"></small>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-info me-2">Current Roles:</span>
                                    <div id="currentUserRoles" class="d-inline"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Role Management Form -->
                    <div id="userRoleForm" class="user-role-form-container" style="display: none;">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-gear me-2"></i>
                                        Manage User Roles
                                    </h5>
                                    <button type="button" class="close-btn" id="closeUserRoleForm">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="userRoleManagementForm">
                                    @csrf
                                    <input type="hidden" id="selectedUserId" name="user_id">
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Available Roles</h6>
                                            <div id="availableRoles" class="list-group" style="max-height: 300px; overflow-y: auto;">
                                                <!-- Available roles will be populated here -->
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-3">Assigned Roles</h6>
                                            <div id="assignedRoles" class="list-group" style="max-height: 300px; overflow-y: auto;">
                                                <!-- Assigned roles will be populated here -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 d-flex justify-content-end">
                                        <button type="button" class="btn btn-outline-secondary me-2" id="cancelUserRoleForm">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-lg me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Users Overview -->
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Users Overview</h5>
                            <div class="row">
                                @foreach($users as $user)
                                <div class="mb-3 col-lg-6 col-xl-4">
                                    <div class="user-card card">
                                        <div class="card-body">
                                            <div class="mb-3 d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted">Roles:</small>
                                                <div class="mt-1">
                                                    @if($user->roles->count() > 0)
                                                        @foreach($user->roles as $role)
                                                            <span class="text-white role-badge bg-primary me-1">
                                                                {{ $role->name }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="role-badge bg-light text-muted">No roles assigned</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <button class="btn btn-sm btn-outline-success edit-user-role-btn" 
                                                    data-user-id="{{ $user->id }}">
                                                <i class="bi bi-person-gear me-1"></i> Manage Roles
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const roles = @json($roles);
        const users = @json($users);
        
        // Role Permissions Tab Functionality
        // Handle role selection
        $('#roleSelector').change(function() {
            const roleId = $(this).val();
            if(roleId) {
                loadRolePermissions(roleId);
                $('.role-selector-container').addClass('has-selection');
                $('#clearSelection').show();
            }
        });
        
        // Edit role button click
        $('.edit-role-btn').click(function() {
            const roleId = $(this).data('role-id');
            $('#roleSelector').val(roleId).trigger('change');
            $('html, body').animate({
                scrollTop: $("#roleSelector").offset().top - 100
            }, 500);
        });
        
        // Close permission form
        $('#closePermissionForm').click(function() {
            closePermissionForm();
        });
        
        // Clear selection button
        $('#clearSelectionBtn').click(function() {
            closePermissionForm();
        });
        
        // Select/Deselect all buttons
        $('#selectAll').click(function() {
            $('.permission-checkbox').prop('checked', true);
            updatePermissionCount();
        });
        
        $('#deselectAll').click(function() {
            $('.permission-checkbox').prop('checked', false);
            updatePermissionCount();
        });
        
        // Update permission count when checkboxes change
        $(document).on('change', '.permission-checkbox', function() {
            updatePermissionCount();
        });
        
        function loadRolePermissions(roleId) {
            // Find the role
            const role = roles.find(r => r.id == roleId);
            if (!role) return;
            
            // Set form values
            $('#selectedRoleId').val(roleId);
            $('#roleName').text(role.name);
            
            // Reset all checkboxes
            $('.permission-checkbox').prop('checked', false);
            
            // Check the permissions the role has
            if (role.permissions && role.permissions.length > 0) {
                role.permissions.forEach(permission => {
                    $(`input[value="${permission.name}"]`).prop('checked', true);
                });
            }
            
            // Update count and show form with animation
            updatePermissionCount();
            $('#permissionForm').removeClass('closing').slideDown(400);
        }
        
        function closePermissionForm() {
            // Add closing animation
            $('#permissionForm').addClass('closing');
            
            // Hide the form after animation
            setTimeout(() => {
                $('#permissionForm').hide().removeClass('closing');
                
                // Reset form state
                $('#roleSelector').val('').prop('selectedIndex', 0);
                $('.role-selector-container').removeClass('has-selection');
                $('#clearSelection').hide();
                $('.permission-checkbox').prop('checked', false);
                $('#selectedRoleId').val('');
                $('#roleName').text('');
                $('#permissionCount').text('');
            }, 300);
        }
        
        function updatePermissionCount() {
            const checkedCount = $('.permission-checkbox:checked').length;
            $('#permissionCount').text(`${checkedCount} permissions`);
        }
        
        // User Roles Tab Functionality
        // User search functionality
        $('#userSearch').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            const userSelector = $('#userSelector');
            
            userSelector.find('option:not(:first)').each(function() {
                const optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
            
            // Clear selection if search is cleared
            if (searchTerm === '') {
                userSelector.val('').trigger('change');
            }
        });
        
        // User selection change
        $('#userSelector').change(function() {
            const userId = $(this).val();
            if (userId) {
                const user = users.find(u => u.id == userId);
                if (user) {
                    showSelectedUserInfo(user);
                    checkAssignButtonState();
                }
            } else {
                hideSelectedUserInfo();
                checkAssignButtonState();
            }
        });
        
        // Role selection change
        $('#roleAssignSelector').change(function() {
            checkAssignButtonState();
        });
        
        // Assign role button
        $('#assignRoleBtn').click(function() {
            const userId = $('#userSelector').val();
            const roleName = $('#roleAssignSelector').val();
            
            if (userId && roleName) {
                assignRoleToUser(userId, roleName);
            }
        });
        
        // Clear user selection
        $('#clearUserSelection').click(function() {
            $('#userSelector').val('').trigger('change');
            $('#roleAssignSelector').val('');
            $('#userSearch').val('');
            hideSelectedUserInfo();
            checkAssignButtonState();
        });
        
        // Edit user role button click
        $('.edit-user-role-btn').click(function() {
            const userId = $(this).data('user-id');
            loadUserRoleManagement(userId);
        });
        
        // Close user role form
        $('#closeUserRoleForm, #cancelUserRoleForm').click(function() {
            closeUserRoleForm();
        });
        
        // User role management form submission
        $('#userRoleManagementForm').submit(function(e) {
            e.preventDefault();
            
            const userId = $('#selectedUserId').val();
            const assignedRoles = [];
            
            $('#assignedRoles .list-group-item').each(function() {
                const roleName = $(this).data('role-name');
                if (roleName) {
                    assignedRoles.push(roleName);
                }
            });
            
            updateUserRoles(userId, assignedRoles);
        });
        
        function showSelectedUserInfo(user) {
            $('#selectedUserName').text(user.name);
            $('#selectedUserEmail').text(user.email);
            $('#userAvatar').text(user.name.charAt(0).toUpperCase());
            
            // Show current roles
            const rolesContainer = $('#currentUserRoles');
            rolesContainer.empty();
            
            if (user.roles && user.roles.length > 0) {
                user.roles.forEach(role => {
                    rolesContainer.append(`
                        <span class="text-white role-badge bg-primary me-1">
                            ${role.name}
                        </span>
                    `);
                });
            } else {
                rolesContainer.append(`
                    <span class="role-badge bg-light text-muted">
                        No roles assigned
                    </span>
                `);
            }
            
            $('#selectedUserInfo').slideDown();
        }
        
        function hideSelectedUserInfo() {
            $('#selectedUserInfo').slideUp();
        }
        
        function checkAssignButtonState() {
            const userId = $('#userSelector').val();
            const roleName = $('#roleAssignSelector').val();
            $('#assignRoleBtn').prop('disabled', !userId || !roleName);
        }
        
        function assignRoleToUser(userId, roleName) {
            // Show loading state
            $('#assignRoleBtn').prop('disabled', true).html('<i class="spinner-border spinner-border-sm me-1"></i> Assigning...');
            
            $.ajax({
                url: '{{ route("users.assign-role") }}',
                type: 'POST',
                data: {
                    user_id: userId,
                    role_name: roleName,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message);
                        
                        // Update users data
                        const userIndex = users.findIndex(u => u.id == userId);
                        if (userIndex !== -1) {
                            users[userIndex] = response.user;
                            showSelectedUserInfo(users[userIndex]);
                            updateUserCard(users[userIndex]);
                        }
                        
                        // Clear selections
                        $('#roleAssignSelector').val('');
                        checkAssignButtonState();
                    } else {
                        showAlert('error', response.message || 'Failed to assign role');
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to assign role';
                    showAlert('error', errorMessage);
                },
                complete: function() {
                    // Reset button state
                    $('#assignRoleBtn').prop('disabled', false).html('<i class="bi bi-person-plus me-1"></i> Assign Role');
                    checkAssignButtonState();
                }
            });
        }
        
        function loadUserRoleManagement(userId) {
            const user = users.find(u => u.id == userId);
            if (!user) return;
            
            $('#selectedUserId').val(userId);
            
            // Clear role lists
            $('#availableRoles').empty();
            $('#assignedRoles').empty();
            
            const userRoleNames = user.roles ? user.roles.map(r => r.name) : [];
            
            // Populate available and assigned roles
            roles.forEach(role => {
                const isAssigned = userRoleNames.includes(role.name);
                const roleItem = createRoleListItem(role, isAssigned);
                
                if (isAssigned) {
                    $('#assignedRoles').append(roleItem);
                } else {
                    $('#availableRoles').append(roleItem);
                }
            });
            
            // Show form with animation
            $('#userRoleForm').removeClass('closing').slideDown(400);
            
            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#userRoleForm").offset().top - 100
            }, 500);
        }
        
        function createRoleListItem(role, isAssigned) {
            const actionClass = isAssigned ? 'remove-role' : 'add-role';
            const actionIcon = isAssigned ? 'bi-dash-circle' : 'bi-plus-circle';
            const actionColor = isAssigned ? 'text-danger' : 'text-success';
            
            return `
                <div class="list-group-item d-flex justify-content-between align-items-center" data-role-name="${role.name}">
                    <div>
                        <h6 class="mb-1">${role.name}</h6>
                        <small class="text-muted">${role.permissions ? role.permissions.length : 0} permissions</small>
                    </div>
                    <button type="button" class="btn btn-sm ${actionClass}" data-role="${role.name}">
                        <i class="bi ${actionIcon} ${actionColor}"></i>
                    </button>
                </div>
            `;
        }
        
        // Handle role add/remove in management form
        $(document).on('click', '.add-role', function() {
            const roleName = $(this).data('role');
            const role = roles.find(r => r.name === roleName);
            const roleItem = $(this).closest('.list-group-item');
            
            // Move to assigned roles
            roleItem.find('.add-role')
                    .removeClass('add-role')
                    .addClass('remove-role')
                    .html('<i class="bi bi-dash-circle text-danger"></i>');
            
            $('#assignedRoles').append(roleItem);
        });
        
        $(document).on('click', '.remove-role', function() {
            const roleName = $(this).data('role');
            const role = roles.find(r => r.name === roleName);
            const roleItem = $(this).closest('.list-group-item');
            
            // Move to available roles
            roleItem.find('.remove-role')
                    .removeClass('remove-role')
                    .addClass('add-role')
                    .html('<i class="bi bi-plus-circle text-success"></i>');
            
            $('#availableRoles').append(roleItem);
        });
        
        function closeUserRoleForm() {
            // Add closing animation
            $('#userRoleForm').addClass('closing');
            
            // Hide the form after animation
            setTimeout(() => {
                $('#userRoleForm').hide().removeClass('closing');
                
                // Reset form state
                $('#selectedUserId').val('');
                $('#availableRoles').empty();
                $('#assignedRoles').empty();
            }, 300);
        }
        
        function updateUserRoles(userId, roleNames) {
            // Show loading state
            $('#userRoleManagementForm button[type="submit"]').prop('disabled', true)
                .html('<i class="spinner-border spinner-border-sm me-1"></i> Saving...');
            
            $.ajax({
                url: '{{ route("users.update-roles") }}',
                type: 'POST',
                data: {
                    user_id: userId,
                    roles: roleNames,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        showAlert('success', response.message);
                        
                        // Update users data
                        const userIndex = users.findIndex(u => u.id == userId);
                        if (userIndex !== -1) {
                            users[userIndex] = response.user;
                            updateUserCard(users[userIndex]);
                        }
                        
                        // Close form
                        closeUserRoleForm();
                    } else {
                        showAlert('error', response.message || 'Failed to update user roles');
                    }
                },
                error: function(xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to update user roles';
                    showAlert('error', errorMessage);
                },
                complete: function() {
                    // Reset button state
                    $('#userRoleManagementForm button[type="submit"]').prop('disabled', false)
                        .html('<i class="bi bi-check-lg me-1"></i> Save Changes');
                }
            });
        }
        
        function updateUserCard(user) {
            const userCard = $(`.edit-user-role-btn[data-user-id="${user.id}"]`).closest('.user-card');
            const rolesContainer = userCard.find('.role-badge').parent();
            
            // Clear existing roles
            rolesContainer.empty();
            
            // Add updated roles
            if (user.roles && user.roles.length > 0) {
                user.roles.forEach(role => {
                    rolesContainer.append(`
                        <span class="text-white role-badge bg-primary me-1">
                            ${role.name}
                        </span>
                    `);
                });
            } else {
                rolesContainer.append(`
                    <span class="role-badge bg-light text-muted">No roles assigned</span>
                `);
            }
        }
        
        function showAlert(type, message) {
            const alertType = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-triangle';
            
            const alertHtml = `
                <div class="alert ${alertType} alert-dismissible fade show" role="alert">
                    <i class="bi ${icon} me-1"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Remove existing alerts
            $('.alert').remove();
            
            // Add new alert at the top
            $('.section').prepend(alertHtml);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }
        
        // ESC key to close forms
        $(document).keyup(function(e) {
            if (e.keyCode === 27) { // ESC key
                if ($('#permissionForm').is(':visible')) {
                    closePermissionForm();
                }
                if ($('#userRoleForm').is(':visible')) {
                    closeUserRoleForm();
                }
            }
        });
    });
</script>
@endsection