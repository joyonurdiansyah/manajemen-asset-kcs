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
    .table-hover-custom {
        background-color: rgba(0, 0, 0, 0.03);
        transition: background-color 0.2s;
    }

    /* Fix for footer issue */
    #main {
        min-height: calc(100vh - 180px);
        padding-bottom: 60px;
    }
    
    /* Make checkboxes more visible */
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #4154f1;
        border-color: #4154f1;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .table-responsive {
            border: 0;
            margin-bottom: 0;
        }
        
        .table th, .table td {
            white-space: nowrap;
        }
    }
</style>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Manage Role Permissions</h5>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('permissions.update') }}" method="POST">
                        @csrf
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr class="table-primary">
                                        <th scope="col" style="min-width: 200px;">Permission</th>
                                        @foreach($roles as $role)
                                            <th scope="col" class="text-center">{{ $role->name }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permissions as $permission)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-shield-lock me-2 text-muted"></i>
                                                    <span>{{ $permission->name }}</span>
                                                </div>
                                            </td>
                                            @foreach($roles as $role)
                                                <td class="text-center">
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="perm_{{ $role->id }}_{{ $permission->id }}"
                                                            name="permissions[{{ $role->id }}][]"
                                                            value="{{ $permission->name }}"
                                                            {{ $role->permissions->contains('name', $permission->name) ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 row">
                            <div class="mb-3 col-md-6">
                                <div class="input-group">
                                    <label class="input-group-text" for="role_id">
                                        <i class="bi bi-person-badge me-1"></i> Apply changes to:
                                    </label>
                                    <select name="role_id" class="form-select" id="role_id" required>
                                        <option value="" selected disabled>Select role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6 d-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Permissions
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add hover effect to rows
        $('tbody tr').hover(
            function() {
                $(this).addClass('table-hover-custom');
            },
            function() {
                $(this).removeClass('table-hover-custom');
            }
        );
        
        // Add tooltip to checkboxes for better UX
        $('input[type="checkbox"]').tooltip({
            title: function() {
                return $(this).prop('checked') ? 'Remove permission' : 'Grant permission';
            },
            placement: 'top',
            trigger: 'hover'
        });
    });
</script>

@endsection