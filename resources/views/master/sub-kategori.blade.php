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


    <div class="container-fluid" style="height:70vh;">
        <!-- Page Header -->
        <div class="page-header">
            <h4 class="fw-bold">Data Master Site</h4>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item">Referensi Data</li>
                    <li class="breadcrumb-item active">Sub Kategori</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">Daftar Sub Kategori</h5>
                <div class="header-buttons">
                    <button type="button" class="btn btn-success btn-export" id="btnExport">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-success btn-add" id="btnAddSubcategory">
                        <i class="fas fa-plus-circle"></i> Tambah Data Sub Kategori
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="subcategoriesTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Kategori</th>
                                <th width="25%">Nama Kategori</th>
                                <th width="30%">Nama Sub Kategori</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container"></div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="subcategoryModal" tabindex="-1" aria-labelledby="subcategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subcategoryModalLabel">Tambah Sub Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subcategoryForm">
                    @csrf
                    <input type="hidden" id="subcategory_id" name="subcategory_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                            <div class="invalid-feedback" id="category_id_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Sub Kategori</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback" id="name_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Sub Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-md-4 fw-bold">Kode Kategori:</div>
                        <div class="col-md-8" id="detail_category_code"></div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4 fw-bold">Nama Kategori:</div>
                        <div class="col-md-8" id="detail_category_name"></div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-4 fw-bold">Nama Sub Kategori:</div>
                        <div class="col-md-8" id="detail_subcategory_name"></div>
                    </div>

                    <hr>
                    <h6 class="fw-bold">Asset Status Terkait:</h6>
                    <div class="mt-3 table-responsive">
                        <table class="table table-striped table-bordered" id="assetStatusTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Status</th>
                                    <th>Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody id="asset_status_data">
                                <!-- Asset status data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus sub kategori ini?</p>
                    <p class="text-danger fw-bold">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
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
            var table = $('#subcategoriesTable').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ route('subcategory.data') }}',
                    type: 'GET',
                    dataSrc: 'data',
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
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'category_code',
                        name: 'category_code',
                        visible: false
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
            });

            // Show Notification Function
            function showNotification(type, message) {
                var iconClass = type === 'success' ? 'fas fa-check-circle text-success' :
                    'fas fa-exclamation-circle text-danger';
                var bgClass = type === 'success' ? 'bg-success-light' : 'bg-danger-light';

                var toast = `
            <div class="toast ${bgClass}" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000">
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
                $('.toast').toast('show');

                // Remove toast after it's hidden
                $('.toast').on('hidden.bs.toast', function() {
                    $(this).remove();
                });
            }

            // Load Categories for Select
            function loadCategories() {
                $.ajax({
                    url: "{{ route('category.list') }}",
                    type: 'GET',
                    success: function(response) {
                        var options = '<option value="">Pilih Kategori</option>';
                        if (response.data && response.data.length > 0) {
                            $.each(response.data, function(index, category) {
                                options += '<option value="' + category.id + '">' + category
                                    .name + '</option>';
                            });
                        }
                        $('#category_id').html(options);
                    },
                    error: function() {
                        showNotification('error', 'Gagal memuat data kategori');
                    }
                });
            }

            // Open Add Modal
            $('#btnAddSubcategory').click(function() {
                $('#subcategoryModalLabel').text('Tambah Sub Kategori');
                $('#subcategoryForm').trigger('reset');
                $('#subcategory_id').val('');
                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');
                loadCategories();
                $('#subcategoryModal').modal('show');
            });

            // Handle Form Submit (Add/Edit)
            $('#subcategoryForm').submit(function(e) {
                e.preventDefault();
                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                let formData = $(this).serialize();
                let url = "{{ route('subcategory.store') }}";
                let method = 'POST';

                if ($('#subcategory_id').val()) {
                    // Fixed URL construction to include the ID parameter
                    url = "{{ url('subcategories') }}/" + $('#subcategory_id').val();
                    method = 'PUT';
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            $('#subcategoryModal').modal('hide');
                            table.ajax.reload();
                            showNotification('success', response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                $('#' + key + '_error').text(value[0]);
                                $('#' + key).addClass('is-invalid');
                            });
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Handle Edit
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');
                $('#subcategoryModalLabel').text('Edit Sub Kategori');
                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                // Load categories first
                loadCategories();

                // Fixed URL construction to include the ID parameter
                $.ajax({
                    url: "{{ url('subcategories') }}/" + id + "/edit",
                    type: 'GET',
                    success: function(response) {
                        $('#subcategory_id').val(response.id);
                        setTimeout(function() {
                            $('#category_id').val(response.category_id);
                        }, 500); // Small delay to ensure categories are loaded
                        $('#name').val(response.name);
                        $('#subcategoryModal').modal('show');
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Handle Delete
            let deleteId;
            $(document).on('click', '.delete-btn', function() {
                deleteId = $(this).data('id');
                $('#deleteModal').modal('show');
            });

            $('#confirmDelete').click(function() {
                $.ajax({
                    url: "{{ url('subcategories') }}/" + deleteId,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                            showNotification('success', response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            $('#deleteModal').modal('hide');
                            showNotification('error', xhr.responseJSON.message);
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Handle Detail View
            $(document).on('click', '.detail-btn', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: "{{ url('subcategories') }}/" + id,
                    type: 'GET',
                    success: function(response) {
                        $('#detail_category_code').text(response.category.code || '-');
                        $('#detail_category_name').text(response.category.name);
                        $('#detail_subcategory_name').text(response.name);

                        // Load asset statuses
                        let assetStatusHtml = '';
                        if (response.asset_statuses && response.asset_statuses.length > 0) {
                            $.each(response.asset_statuses, function(index, status) {
                                assetStatusHtml += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${status.name}</td>
                    <td>${status.description || '-'}</td>
                </tr>
            `;
                            });
                        } else {
                            assetStatusHtml = `
            <tr>
                <td colspan="3" class="text-center">Tidak ada data status aset terkait</td>
            </tr>
        `;
                        }

                        $('#asset_status_data').html(assetStatusHtml);
                        $('#detailModal').modal('show');
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Export to Excel
            $('#btnExport').click(function() {
                $.ajax({
                    url: "{{ route('subcategory.export') }}",
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.data && response.data.length > 0) {
                            exportToExcel(response.data);
                        } else {
                            showNotification('error', 'Tidak ada data untuk diekspor.');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan saat mengekspor data.');
                    }
                });
            });

            function exportToExcel(data) {
                // Create worksheet
                const ws = XLSX.utils.json_to_sheet(data);

                // Set column widths
                const wscols = [{
                        wch: 5
                    }, // No
                    {
                        wch: 15
                    }, // Kode Kategori
                    {
                        wch: 25
                    }, // Nama Kategori
                    {
                        wch: 30
                    } // Nama Sub Kategori
                ];
                ws['!cols'] = wscols;

                // Create workbook
                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Sub Kategori");

                const now = new Date();
                const fileName =
                    `SubKategori_${now.getFullYear()}${(now.getMonth()+1).toString().padStart(2, '0')}${now.getDate().toString().padStart(2, '0')}.xlsx`;

                // Save file
                XLSX.writeFile(wb, fileName);
            }

            // Clear invalid state on input change
            $(document).on('input change', '.form-control, .form-select', function() {
                $(this).removeClass('is-invalid');
                $('#' + $(this).attr('id') + '_error').text('');
            });
        });
    </script>
@endsection
