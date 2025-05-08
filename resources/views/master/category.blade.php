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
            <h4 class="fw-bold">Data Kategori Item</h4>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Kategori Item</li>
                </ol>
            </nav>
        </div>

        <!-- Toast notifications container -->
        <div class="toast-container"></div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Daftar Kategori</h5>
                <div class="header-buttons">
                    <button type="button" class="btn btn-export" id="export-excel">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fas fa-plus-circle"></i> Tambah Kategori Item
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="category-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
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

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Tambah Kategori Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-category-form">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category-name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="category-name" name="name"
                                placeholder="Masukkan nama kategori" required>
                            <div class="invalid-feedback" id="name-error"></div>
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

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Kategori Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-category-form">
                    <div class="modal-body">
                        <input type="hidden" id="edit-category-id">
                        <div class="mb-3">
                            <label for="edit-category-name" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit-category-name" name="name" required>
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus kategori "<span id="delete-category-name"></span>"?</p>
                    <input type="hidden" id="delete-category-id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Asset Status -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Kategori: <span id="categoryName"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="asset-status-table" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Asset</th>
                                    <th>Brand</th>
                                    <th>Serial Number</th>
                                    <th>Subkategori</th>
                                    <th>Tanggal Visit</th>
                                    <th>Status Barang</th>
                                </tr>
                            </thead>
                            <tbody id="asset-status-body">
                                <!-- Data akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>
                    <div id="no-data-message" class="p-3 text-center d-none">
                        <p>Tidak ada data asset status untuk kategori ini.</p>
                    </div>
                    <div id="loading-indicator" class="p-3 text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
            var table = $('#category-table').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ route('category.get') }}',
                    type: 'GET',
                    dataSrc: function(json) {
                        console.log(json);
                        return json.kategori_item;
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
                    searchPlaceholder: "Cari kategori...",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)"
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'name',
                        title: 'Kategori Item'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                        <div class="action-buttons">
                            <button type="button" class="text-white btn-detail btn btn-info" data-id="${data}" data-name="${row.name}">
                                        <i class="fas fa-info-circle"></i> Detail
                                    </button>
                            <button type="button" class="btn-edit" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn-delete" data-id="${row.id}" data-name="${row.name}">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    `;
                        },
                        title: 'Action'
                    }
                ]
            });

            // Add Category Form Submission
            $('#add-category-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('category.store') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: $('#category-name').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#addCategoryModal').modal('hide');
                            $('#add-category-form')[0].reset();
                            table.ajax.reload();
                            showNotification('success', response.message);
                        } else {
                            showNotification('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            if (errors.name) {
                                $('#category-name').addClass('is-invalid');
                                $('#name-error').text(errors.name[0]);
                            }
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Open Edit Modal with Category Data
            $(document).on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#edit-category-id').val(id);
                $('#edit-category-name').val(name);
                $('#editCategoryModal').modal('show');
            });

            // Edit Category Form Submission
            $('#edit-category-form').on('submit', function(e) {
                e.preventDefault();

                const id = $('#edit-category-id').val();

                $.ajax({
                    url: `/category-data/update/${id}`,
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: $('#edit-category-name').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editCategoryModal').modal('hide');
                            table.ajax.reload();
                            showNotification('success', response.message);
                        } else {
                            showNotification('error', response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            if (errors.name) {
                                $('#edit-category-name').addClass('is-invalid');
                                $('#edit-name-error').text(errors.name[0]);
                            }
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });

            // Open Delete Confirmation Modal
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');

                $('#delete-category-id').val(id);
                $('#delete-category-name').text(name);
                $('#deleteCategoryModal').modal('show');
            });

            // Confirm Delete Category
            $('#confirm-delete').on('click', function() {
                const id = $('#delete-category-id').val();

                $.ajax({
                    url: `/category-data/delete/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#deleteCategoryModal').modal('hide');
                            table.ajax.reload();
                            showNotification('success', response.message);
                        } else {
                            showNotification('error', response.message);
                        }
                    },
                    error: function() {
                        showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                });
            });

            // Export to Excel
            $('#export-excel').on('click', function() {
                $.ajax({
                    url: '{{ route('category.get') }}',
                    type: 'GET',
                    success: function(response) {
                        if (response.success && response.kategori_item) {
                            exportToExcel(response.kategori_item);
                        } else {
                            showNotification('error', 'Tidak ada data untuk diekspor');
                        }
                    },
                    error: function() {
                        showNotification('error', 'Gagal mengambil data untuk ekspor');
                    }
                });
            });

            $(document).on('click', '.btn-detail', function() {
            const categoryId = $(this).data('id');
            const categoryName = $(this).data('name');
            
            $('#categoryName').text(categoryName);
            
            $('#loading-indicator').removeClass('d-none');
            $('#no-data-message').addClass('d-none');
            $('#asset-status-body').empty();
            
            // Tampilkan modal
            $('#detailModal').modal('show');
            
            $.ajax({
                url: `/category-data/${categoryId}/asset-status`,
                type: 'GET',
                dataType: 'json',
                success: function(response) {

                    $('#loading-indicator').addClass('d-none');
                    
                    if (response.success) {
                        const assetStatuses = response.data.asset_statuses;
                        
                        if (assetStatuses.length > 0) {
  
                            let html = '';
                            
                            assetStatuses.forEach((item, index) => {
                                html += `
                                    <tr>
                                        <td>${index + 1}</td>
                                        <td>${item.asset_code || '-'}</td>
                                        <td>${item.brand || '-'}</td>
                                        <td>${item.serial_number || '-'}</td>
                                        <td>${item.subcategory}</td>
                                        <td>${item.tanggal_visit}</td>
                                        <td>
                                            <span class="badge bg-${getStatusBadgeColor(item.status_barang)}">
                                                ${item.status_barang}
                                            </span>
                                        </td>
                                    </tr>
                                `;
                            });
                            
                            $('#asset-status-body').html(html);
                            
                            if (!$.fn.DataTable.isDataTable('#asset-status-table')) {
                                $('#asset-status-table').DataTable({
                                    responsive: true,
                                    pageLength: 5,
                                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                                    language: {
                                        search: "<span class='me-2'>Cari:</span> _INPUT_",
                                        searchPlaceholder: "Cari data...",
                                        lengthMenu: "Tampilkan _MENU_ entri",
                                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                                        infoFiltered: "(disaring dari _MAX_ total entri)",
                                        paginate: {
                                            first: "Pertama",
                                            last: "Terakhir",
                                            next: "Selanjutnya",
                                            previous: "Sebelumnya"
                                        }
                                    }
                                });
                            } else {

                                $('#asset-status-table').DataTable().destroy();
                                $('#asset-status-table').DataTable({
                                    responsive: true,
                                    pageLength: 5,
                                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
                                    language: {
                                        search: "<span class='me-2'>Cari:</span> _INPUT_",
                                        searchPlaceholder: "Cari data...",
                                        lengthMenu: "Tampilkan _MENU_ entri",
                                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                                        infoFiltered: "(disaring dari _MAX_ total entri)",
                                        paginate: {
                                            first: "Pertama",
                                            last: "Terakhir",
                                            next: "Selanjutnya",
                                            previous: "Sebelumnya"
                                        }
                                    }
                                });
                            }
                        } else {

                            $('#no-data-message').removeClass('d-none');
                        }
                    } else {

                        $('#no-data-message').removeClass('d-none')
                            .html(`<div class="alert alert-danger">Gagal memuat data: ${response.message}</div>`);
                    }
                },
                error: function(xhr, status, error) {

                    $('#loading-indicator').addClass('d-none');
                    $('#no-data-message').removeClass('d-none')
                        .html(`<div class="alert alert-danger">Error: ${error}</div>`);
                }
            });
        });

        function getStatusBadgeColor(status) {
            switch (status.toLowerCase()) {
                case 'oke':
                    return 'success';
                case 'rusak':
                    return 'danger';
                case 'perbaikan':
                    return 'warning';
                default:
                    return 'secondary';
            }
        }

        $('#detailModal').on('hidden.bs.modal', function() {
            if ($.fn.DataTable.isDataTable('#asset-status-table')) {
                $('#asset-status-table').DataTable().destroy();
            }
            $('#asset-status-body').empty();
        });


            function exportToExcel(data) {
                const exportData = data.map((item, index) => {
                    return {
                        'No': index + 1,
                        'Nama Kategori': item.name
                    };
                });

                const worksheet = XLSX.utils.json_to_sheet(exportData);

                // Set column nih
                const colWidths = [{
                        wch: 5
                    }, // No
                    {
                        wch: 40
                    }, // Nama Kategori
                ];
                worksheet['!cols'] = colWidths;

                // Create workbook
                const workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Kategori Item');

                // Generate Excel file
                const date = new Date().toISOString().slice(0, 10);
                const fileName = `Data_Kategori_Item_${date}.xlsx`;

                XLSX.writeFile(workbook, fileName);
                showNotification('success', 'Data berhasil diekspor ke Excel');
            }

            // Reset form and validation on modal close
            $('#addCategoryModal').on('hidden.bs.modal', function() {
                $('#add-category-form')[0].reset();
                $('#category-name').removeClass('is-invalid');
                $('#name-error').text('');
            });

            $('#editCategoryModal').on('hidden.bs.modal', function() {
                $('#edit-category-name').removeClass('is-invalid');
                $('#edit-name-error').text('');
            });

            // Function to show notifications
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
        });
    </script>
@endsection
