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
            <h4 class="fw-bold">Data Master Site</h4>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Referensi Data</li>
                    <li class="breadcrumb-item active">Site</li>
                </ol>
            </nav>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Daftar Site</h5>
                <div class="header-buttons">
                    <button type="button" class="btn btn-export" id="btnExport">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-add" id="btnAddSite">
                        <i class="fas fa-plus-circle"></i> Tambah Data Site
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="assetsTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="15%">Kode Warehouse</th>
                                <th width="25%">Nama Lokasi</th>
                                <th width="40%">Alamat</th>
                                <th width="15%">Action</th> 
                            </tr>
                        </thead>
                        <tbody>
                            {{-- response disini --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Toast Notification Container -->
    <div class="toast-container"></div>

    <!-- Add Site Modal -->
    <div class="modal fade" id="addSiteModal" tabindex="-1" aria-labelledby="addSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSiteModalLabel">Tambah Data Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addSiteForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_kode_lokasi">Kode Warehouse</label>
                            <input type="text" class="form-control" id="add_kode_lokasi" name="kode" required>
                            <div class="invalid-feedback" id="add_kode_lokasi_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="add_nama_lokasi">Nama Lokasi</label>
                            <input type="text" class="form-control" id="add_nama_lokasi" name="nama_lokasi" required>
                            <div class="invalid-feedback" id="add_nama_lokasi_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="add_alamat">Alamat</label>
                            <textarea class="form-control" id="add_alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="add_alamat_error"></div>
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

    <!-- Edit Site Modal -->
    <div class="modal fade" id="editSiteModal" tabindex="-1" aria-labelledby="editSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSiteModalLabel">Edit Data Site</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editSiteForm">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <input type="hidden" id="edit_site_id">
                        <div class="form-group">
                            <label for="edit_kode_lokasi">Kode Warehouse</label>
                            <input type="text" class="form-control" id="edit_kode_lokasi" name="kode" required>
                            <div class="invalid-feedback" id="edit_kode_lokasi_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_nama_lokasi">Nama Lokasi</label>
                            <input type="text" class="form-control" id="edit_nama_lokasi" name="nama_lokasi" required>
                            <div class="invalid-feedback" id="edit_nama_lokasi_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_alamat">Alamat</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                            <div class="invalid-feedback" id="edit_alamat_error"></div>
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
    <div class="modal fade" id="deleteSiteModal" tabindex="-1" aria-labelledby="deleteSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteSiteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data site ini?</p>
                    <p class="fw-bold" id="delete_site_name"></p>
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
            var table = $('#assetsTable').DataTable({
                processing: false,
                serverSide: false,
                responsive: true,
                ajax: {
                    url: '{{ route('site.data') }}',
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
                        $('.dataTables_info').html('Showing 1 to ' + settings.fnRecordsTotal() + ' of ' + settings.fnRecordsTotal() + ' entries');
                    }
                },
                columns: [
                    { 
                        data: null, 
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'kode' },
                    { data: 'nama_lokasi' },
                    { data: 'alamat' },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            return `
                                <div class="action-buttons">
                                    <button type="button" class="btn-edit" data-id="${data}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn-delete" data-id="${data}" data-name="${row.nama_lokasi}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            `;
                        }
                    }
                ]
            });
            
            // CSRF token setup for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            // Add CSRF token to all forms
            var token = $('meta[name="csrf-token"]').attr('content');
            $('form').append('<input type="hidden" name="_token" value="' + token + '">');
            
            // Show Add Site Modal
            $('#btnAddSite').click(function() {
                // Reset form
                $('#addSiteForm')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Show modal
                $('#addSiteModal').modal('show');
            });
            
            // Handle Add Site Form Submit
            $('#addSiteForm').submit(function(e) {
                e.preventDefault();
                
                // Reset validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Get form data
                var formData = {
                    kode: $('#add_kode_lokasi').val(),
                    nama_lokasi: $('#add_nama_lokasi').val(),
                    alamat: $('#add_alamat').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                // Submit data via AJAX
                $.ajax({
                    type: 'POST',
                    url: '{{ route('site.store') }}',
                    data: formData,
                    success: function(response) {
                        // Close modal
                        $('#addSiteModal').modal('hide');
                        
                        // Show success notification
                        showNotification('success', response.message);
                        
                        // Reload table
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            
                            // Display validation errors
                            if (errors.kode_lokasi) {
                                $('#add_kode_lokasi').addClass('is-invalid');
                                $('#add_kode_lokasi_error').text(errors.kode_lokasi[0]);
                            }
                            
                            if (errors.nama_lokasi) {
                                $('#add_nama_lokasi').addClass('is-invalid');
                                $('#add_nama_lokasi_error').text(errors.nama_lokasi[0]);
                            }
                            
                            if (errors.alamat) {
                                $('#add_alamat').addClass('is-invalid');
                                $('#add_alamat_error').text(errors.alamat[0]);
                            }
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });
            
            // Show Edit Site Modal when Edit button is clicked
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                
                // Reset validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Get site data
                $.ajax({
                    type: 'GET',
                    url: '{{ url('master-site/edit') }}/' + id,
                    success: function(response) {
                        // Fill form with site data
                        $('#edit_site_id').val(response.id);
                        $('#edit_kode_lokasi').val(response.kode);
                        $('#edit_nama_lokasi').val(response.nama_lokasi);
                        $('#edit_alamat').val(response.alamat);
                        
                        // Show modal
                        $('#editSiteModal').modal('show');
                    },
                    error: function() {
                        showNotification('error', 'Gagal mengambil data site.');
                    }
                });
            });
            
            // Handle Edit Site Form Submit
            $('#editSiteForm').submit(function(e) {
                e.preventDefault();
                
                // Reset validation errors
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                var id = $('#edit_site_id').val();
                
                // Get form data
                var formData = {
                    kode: $('#edit_kode_lokasi').val(),
                    nama_lokasi: $('#edit_nama_lokasi').val(),
                    alamat: $('#edit_alamat').val(),
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PUT'
                };
                
                // Submit data via AJAX
                $.ajax({
                    type: 'PUT',
                    url: '{{ url('master-site/update') }}/' + id,
                    data: formData,
                    success: function(response) {
                        // Close modal
                        $('#editSiteModal').modal('hide');
                        
                        // Show success notification
                        showNotification('success', response.message);
                        
                        // Reload table
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            
                            // Display validation errors
                            if (errors.kode_lokasi) {
                                $('#edit_kode_lokasi').addClass('is-invalid');
                                $('#edit_kode_lokasi_error').text(errors.kode_lokasi[0]);
                            }
                            
                            if (errors.nama_lokasi) {
                                $('#edit_nama_lokasi').addClass('is-invalid');
                                $('#edit_nama_lokasi_error').text(errors.nama_lokasi[0]);
                            }
                            
                            if (errors.alamat) {
                                $('#edit_alamat').addClass('is-invalid');
                                $('#edit_alamat_error').text(errors.alamat[0]);
                            }
                        } else {
                            showNotification('error', 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    }
                });
            });
            
            // Show Delete Confirmation Modal
            $(document).on('click', '.btn-delete', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                
                $('#delete_site_name').text(name);
                $('#confirmDelete').data('id', id);
                $('#deleteSiteModal').modal('show');
            });
            
            // Handle Delete Confirmation
            $('#confirmDelete').click(function() {
                var id = $(this).data('id');
                
                $.ajax({
                    type: 'DELETE',
                    url: '{{ url('master-site/destroy') }}/' + id,
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Close modal
                        $('#deleteSiteModal').modal('hide');
                        
                        // Show success notification
                        showNotification('success', response.message);
                        
                        // Reload table
                        table.ajax.reload();
                    },
                    error: function() {
                        showNotification('error', 'Gagal menghapus data site.');
                    }
                });
            });
            
            // Handle Export Excel
            $('#btnExport').click(function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('site.export') }}',
                    success: function(data) {
                        // Convert JSON to Excel
                        var worksheet = XLSX.utils.json_to_sheet(data);
                        var workbook = XLSX.utils.book_new();
                        XLSX.utils.book_append_sheet(workbook, worksheet, "Site Data");
                        
                        // Auto-size columns
                        var wscols = [
                            {wch: 5}, // No
                            {wch: 15}, // Kode Warehouse
                            {wch: 30}, // Nama Lokasi
                            {wch: 50}  // Alamat
                        ];
                        worksheet['!cols'] = wscols;
                        
                        // Generate Excel file
                        var excelBuffer = XLSX.write(workbook, {bookType: 'xlsx', type: 'array'});
                        var blob = new Blob([excelBuffer], {type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
                        saveAs(blob, 'Data_Site_' + new Date().toISOString().split('T')[0] + '.xlsx');
                        
                        showNotification('success', 'Data berhasil diekspor ke Excel.');
                    },
                    error: function() {
                        showNotification('error', 'Gagal mengekspor data.');
                    }
                });
            });
            
            // Function to show notification
            function showNotification(type, message) {
                var iconClass = type === 'success' ? 'fas fa-check-circle text-success' : 'fas fa-exclamation-circle text-danger';
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