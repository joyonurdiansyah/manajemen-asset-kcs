@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Role Permissions</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item">Settings</li>
            <li class="breadcrumb-item active">Role Permissions</li>
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
    
    /* Responsive improvements */
    @media (max-width: 768px) {
        .role-cards {
            grid-template-columns: 1fr;
        }
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
            
            <!-- Role selector -->
            <div class="mb-4 card">
                <div class="card-body">
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
            </div>
            
            <!-- Permission form - initially hidden -->
            <div id="permissionForm" class="card role-card" style="display: none;">
                <form action="{{ route('permissions.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="role_id" id="selectedRoleId">
                    
                    <div class="role-header">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-lock role-icon"></i>
                            <h5 class="mb-0 card-title" id="roleName"></h5>
                        </div>
                        <span class="badge bg-primary" id="permissionCount"></span>
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
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const roles = @json($roles);
        
        // Handle role selection
        $('#roleSelector').change(function() {
            const roleId = $(this).val();
            if(roleId) {
                loadRolePermissions(roleId);
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
            
            // Update count and show form
            updatePermissionCount();
            $('#permissionForm').slideDown();
        }
        
        function updatePermissionCount() {
            const checkedCount = $('.permission-checkbox:checked').length;
            $('#permissionCount').text(`${checkedCount} permissions`);
        }
    });
</script>
@endsection
```