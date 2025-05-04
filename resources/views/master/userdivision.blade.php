@extends('layouts.app')

@section('content')
    <style>
        /* Card styling */
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
            border: none;
        }

        /* Breadcrumb styling */
        .breadcrumb {
            background-color: transparent;
            padding: 0.5rem 0;
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
        }

        .page-header {
            margin-bottom: 1.5rem;
        }

        /* Button styling */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
            border-radius: 8px 8px 0 0;
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-add i {
            font-size: 0.875rem;
        }

        /* Export button styling */
        .btn-export {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .btn-export:hover {
            background-color: #138496;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-export i {
            font-size: 0.875rem;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        /* Table styling */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f2f2f2;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 12px 15px;
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #edf2f9;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, .02);
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
        }

        /* Text alignment for specific columns */
        .table tbody td:nth-child(2),
        .table tbody td:nth-child(3),
        .table tbody td:nth-child(5) {
            text-align: left;
        }

        .table tbody td:nth-child(1),
        .table tbody td:nth-child(4),
        .table tbody td:nth-child(6) {
            text-align: center;
        }

        /* DataTables styling */
        div.dataTables_wrapper div.dataTables_length {
            margin: 15px 0;
            padding-left: 15px;
        }

        div.dataTables_wrapper div.dataTables_filter {
            margin: 15px 0;
            padding-right: 15px;
        }

        div.dataTables_wrapper div.dataTables_info {
            padding: 15px;
            color: #6c757d;
        }

        div.dataTables_wrapper div.dataTables_paginate {
            padding: 15px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.375rem 0.75rem;
            margin-left: 2px;
            border-radius: 0.25rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #007bff;
            border-color: #007bff;
            color: white !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }

        /* Action button styling */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .btn-edit:hover {
            background-color: #0069d9;
            transform: translateY(-1px);
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }

        .btn-delete:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        /* Fix table responsive */
        .table-responsive {
            overflow-x: auto;
        }

        table.dataTable {
            width: 100%;
            table-layout: fixed;
        }

        table.dataTable td {
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Specific column widths */
        table.dataTable th:nth-child(1),
        table.dataTable td:nth-child(1) {
            width: 5%;
        }

        table.dataTable th:nth-child(2),
        table.dataTable td:nth-child(2) {
            width: 20%;
        }

        table.dataTable th:nth-child(3),
        table.dataTable td:nth-child(3) {
            width: 25%;
        }

        table.dataTable th:nth-child(4),
        table.dataTable td:nth-child(4) {
            width: 10%;
        }

        table.dataTable th:nth-child(5),
        table.dataTable td:nth-child(5) {
            width: 25%;
        }

        table.dataTable th:nth-child(6),
        table.dataTable td:nth-child(6) {
            width: 15%;
        }

        /* Modal styling */
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        .modal-title {
            font-weight: 600;
            color: #495057;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 1rem 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #495057;
        }

        .form-control {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 0.5rem 0.75rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Alert styling */
        .alert {
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        /* Toast notification styling */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1060;
        }

        .toast {
            min-width: 300px;
        }

        .toast-header {
            padding: 0.5rem 1rem;
        }

        .toast-body {
            padding: 0.75rem 1rem;
        }

        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
            border-left: 4px solid #28a745;
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="fw-bold">Data Master User & Division</h4>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">User & Division</li>
                </ol>
            </nav>
        </div>

        <!-- Alert Messages -->
        <div class="alert-container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Daftar User & Division</h5>
                <div class="header-buttons">
                    <button type="button" class="btn btn-export" id="exportExcel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus-circle"></i> Tambah Data User
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="user-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kode Divisi</th>
                                <th>Nama Divisi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data akan diisi oleh DataTables --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container"></div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3 form-group">
                            <label for="name" class="form-label">Nama User</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 form-group">
                            <label for="division_id" class="form-label">Divisi</label>
                            <select class="form-select" id="division_id" name="division_id" required>
                                <option value="">Pilih Divisi</option>
                                @foreach (\App\Models\Division::all() as $division)
                                    <option value="{{ $division->id }}">{{ $division->code }} - {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit Data User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Nama User</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_password">Password (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control" id="edit_password" name="password">
                        </div>
                        <div class="form-group">
                            <label for="edit_division_id">Divisi</label>
                            <select class="form-control" id="edit_division_id" name="division_id" required>
                                <option value="">Pilih Divisi</option>
                                @foreach (\App\Models\Division::all() as $division)
                                    <option value="{{ $division->id }}">{{ $division->code }} - {{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user <strong id="delete_user_name"></strong>?</p>
                    <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteUserForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const userTable = $('#user-table').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ route('user.division.get') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json.users;
                    }
                },
                lengthMenu: [
                    [10, 15, 30, 50, -1],
                    [10, 15, 30, 50, "All"]
                ],
                pageLength: 10,
                dom: '<"row mb-3"<"col-md-6"l><"col-md-6"f>>rt<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
                language: {
                    search: "<span class='me-2'>Search:</span> _INPUT_",
                    searchPlaceholder: "Cari data...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                },
                drawCallback: function(settings) {
                    if (settings._iDisplayLength === -1) {
                        $('.dataTables_info').html('Showing 1 to ' + settings.fnRecordsTotal() +
                            ' of ' + settings.fnRecordsTotal() + ' entries');
                    }
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        title: 'Nama'
                    },
                    {
                        data: 'email',
                        title: 'Email'
                    },
                    {
                        data: 'division_code',
                        title: 'Kode Divisi'
                    },
                    {
                        data: 'division_name',
                        title: 'Nama Divisi'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                <div class="action-buttons">
                    <button type="button" class="btn-edit" data-id="${data}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button type="button" class="btn-delete" data-id="${data}" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            `;
                        },
                        title: 'Action'
                    }
                ]
            });

            // Edit User - Using AJAX to get complete user data
            $('#user-table').on('click', '.btn-edit', function() {
                const userId = $(this).data('id');

                // Show loading indicator
                $('#editUserModal .modal-content').append(
                    '<div class="modal-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    );

                // Fetch user data via AJAX
                $.ajax({
                    url: "{{ url('user-division') }}/" + userId + "/edit",
                    type: "GET",
                    success: function(response) {
                        if (response.status === 'success') {
                            const user = response.user;

                            // Fill form fields with user data
                            $('#edit_name').val(user.name);
                            $('#edit_email').val(user.email);
                            $('#edit_password').val(''); // Clear password field
                            $('#edit_division_id').val(user.division_id);
                            $('#editUserForm').attr('action',
                                `{{ url('user-division') }}/${userId}`);

                            // Remove loading indicator
                            $('.modal-overlay').remove();
                        } else {
                            showNotification('error', 'Failed to load user data');
                            $('#editUserModal').modal('hide');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Failed to load user data');
                        $('#editUserModal').modal('hide');
                        $('.modal-overlay').remove();
                    }
                });
            });

            // Delete User
            $('#user-table').on('click', '.btn-delete', function() {
                const userId = $(this).data('id');
                const userName = $(this).closest('tr').find('td:eq(1)').text();

                $('#delete_user_name').text(userName);
                $('#deleteUserForm').attr('action', `{{ url('user-division') }}/${userId}`);
            });

            // Export to Excel
            $('#exportExcel').click(function() {
                const data = userTable.data().toArray();

                // Create workbook and worksheet
                const wb = XLSX.utils.book_new();

                // Convert data to appropriate format for XLSX
                const wsData = data.map((row, index) => {
                    return {
                        'No': index + 1,
                        'Nama': row.name,
                        'Email': row.email,
                        'Kode Divisi': row.division_code,
                        'Nama Divisi': row.division_name
                    };
                });

                const ws = XLSX.utils.json_to_sheet(wsData);

                // Add worksheet to workbook
                XLSX.utils.book_append_sheet(wb, ws, 'Users');

                // Generate Excel file
                const now = new Date();
                const dateStr = now.toISOString().split('T')[0];
                const fileName = `user_division_data_${dateStr}.xlsx`;

                XLSX.writeFile(wb, fileName);
            });

            // Form validation for Add User
            $('#addUserForm').submit(function(e) {
                e.preventDefault();

                const form = $(this)[0];
                const formData = new FormData(form);

                $.ajax({
                    url: "{{ route('user.division.store') }}",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            showNotification('success', response.message);
                            $('#addUserModal').modal('hide');
                            $('#user-table').DataTable().ajax.reload(null, false);
                        } else {
                            showNotification('error', 'Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            let errorMessage = '<ul style="margin: 0; padding-left: 20px;">';
                            for (const key in errors) {
                                errorMessage += `<li>${errors[key][0]}</li>`;
                                $(`#${key}`).addClass('is-invalid');
                                $(`#${key}-error`).text(errors[key][0]);
                            }
                            errorMessage += '</ul>';

                            showNotification('error', errorMessage);
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });


            // Form validation for Edit User
            $('#editUserForm').submit(function(e) {
                e.preventDefault();

                const userId = $(this).attr('action').split('/').pop();
                const formData = new FormData(this);

                $.ajax({
                    url: "{{ url('user-division') }}/" + userId,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showNotification('success', response.message);
                            $('#editUserModal').modal('hide');
                            $('#user-table').DataTable().ajax.reload(null, false);
                        } else {
                            showNotification('error', 'Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            let errorMessage = '<ul style="margin: 0; padding-left: 20px;">';
                            for (const key in errors) {
                                errorMessage += `<li>${errors[key][0]}</li>`;
                                $(`#edit_${key}`).addClass('is-invalid');
                            }
                            errorMessage += '</ul>';

                            showNotification('error', errorMessage);
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Delete User Form Submit
            $('#deleteUserForm').submit(function(e) {
                e.preventDefault();

                const userId = $(this).attr('action').split('/').pop();

                $.ajax({
                    url: "{{ url('user-division') }}/" + userId,
                    type: "POST",
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        '_method': 'DELETE'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            showNotification('success', response.message);
                            $('#deleteUserModal').modal('hide');
                            $('#user-table').DataTable().ajax.reload(null, false);
                        } else {
                            showNotification('error', 'Terjadi kesalahan: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Auto-close alerts after 5 seconds
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);

            // Reset form fields when modal is closed
            $('#addUserModal, #editUserModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $(this).find('.is-invalid').removeClass('is-invalid');
            });
        });

        // Function to show notification
        function showNotification(type, message) {
            var iconClass = type === 'success' ? 'fas fa-check-circle text-success' :
                'fas fa-exclamation-circle text-danger';
            var bgClass = type === 'success' ? 'bg-light border-start border-success border-5' :
                'bg-light border-start border-danger border-5';

            var toastId = 'toast-' + Date.now();

            var toast = `
        <div id="${toastId}" class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
            <div class="toast-header">
                <i class="${iconClass} me-2"></i>
                <strong class="me-auto">${type === 'success' ? 'Sukses' : 'Error'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;

            $('.toast-container').append(toast);
            var toastElement = new bootstrap.Toast(document.getElementById(toastId));
            toastElement.show();

            document.getElementById(toastId).addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        }
    </script>
@endsection
