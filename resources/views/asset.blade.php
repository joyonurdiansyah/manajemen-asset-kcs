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
    
    .breadcrumb-item + .breadcrumb-item::before {
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
        border-bottom: 1px solid rgba(0,0,0,.125);
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
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    }
    
    .table tbody td {
        padding: 12px 15px;
        vertical-align: middle;
        border-color: #edf2f9;
    }
    
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }
    
    .table-bordered {
        border: 1px solid #dee2e6;
    }
    
    .table-bordered td, .table-bordered th {
        border: 1px solid #dee2e6;
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
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h4 class="fw-bold">Data Asset IT</h4>
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Data Asset</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 card-title">Data Asset IT</h5>
            <div class="header-buttons">
                <button type="button" class="btn btn-export" id="exportBtn">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button type="button" class="btn btn-add" id="addAssetBtn">
                    <i class="fas fa-plus-circle"></i> Tambah Data Asset IT
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="assetsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Asset</th>
                            <th>Merk Barang</th>
                            <th>Serial Number</th>
                            <th>Lokasi Awal</th>
                            <th>Lokasi Tujuan</th>
                            <th>Type Barang</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Status Barang</th>
                            <th>Notes</th>
                            <th>Dibuat tanggal</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAssetModalLabel">Tambah Data Asset IT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAssetForm">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warehouse_master_site_id">Warehouse Site</label>
                                <select class="form-control" id="warehouse_master_site_id" name="warehouse_master_site_id" required>
                                    <option value="">Pilih Warehouse</option>
                                    @foreach(App\Models\WarehouseMasterSite::all() as $site)
                                        <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Kategori</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach(App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_code">Nomor Asset</label>
                                <input type="text" class="form-control" id="asset_code" name="asset_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">Merk Barang</label>
                                <input type="text" class="form-control" id="brand" name="brand" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipe">Tipe</label>
                                <input type="text" class="form-control" id="tipe" name="tipe">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="serial_number" name="serial_number">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi_awal">Lokasi Awal</label>
                                <input type="text" class="form-control" id="lokasi_awal" name="lokasi_awal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lokasi_tujuan">Lokasi Tujuan</label>
                                <input type="text" class="form-control" id="lokasi_tujuan" name="lokasi_tujuan">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type">Type Barang</label>
                                <input type="text" class="form-control" id="type" name="type">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_visit">Tanggal Kunjungan</label>
                                <input type="date" class="form-control" id="tanggal_visit" name="tanggal_visit">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status_barang">Status Barang</label>
                                <select class="form-control" id="status_barang" name="status_barang" required>
                                    <option value="oke">Oke</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="perbaikan">Perbaikan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Asset Modal -->
<div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAssetModalLabel">Edit Data Asset IT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAssetForm">
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_asset_id" name="asset_id">
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_warehouse_master_site_id">Warehouse Site</label>
                                <select class="form-control" id="edit_warehouse_master_site_id" name="warehouse_master_site_id" required>
                                    <option value="">Pilih Warehouse</option>
                                    @foreach(App\Models\WarehouseMasterSite::all() as $site)
                                        <option value="{{ $site->id }}">{{ $site->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_category_id">Kategori</label>
                                <select class="form-control" id="edit_category_id" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach(App\Models\Category::all() as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_asset_code">Nomor Asset</label>
                                <input type="text" class="form-control" id="edit_asset_code" name="asset_code" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_brand">Merk Barang</label>
                                <input type="text" class="form-control" id="edit_brand" name="brand" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tipe">Tipe</label>
                                <input type="text" class="form-control" id="edit_tipe" name="tipe">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_serial_number">Serial Number</label>
                                <input type="text" class="form-control" id="edit_serial_number" name="serial_number">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_lokasi_awal">Lokasi Awal</label>
                                <input type="text" class="form-control" id="edit_lokasi_awal" name="lokasi_awal">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_lokasi_tujuan">Lokasi Tujuan</label>
                                <input type="text" class="form-control" id="edit_lokasi_tujuan" name="lokasi_tujuan">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_type">Type Barang</label>
                                <input type="text" class="form-control" id="edit_type" name="type">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_tanggal_visit">Tanggal Kunjungan</label>
                                <input type="date" class="form-control" id="edit_tanggal_visit" name="tanggal_visit">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_status_barang">Status Barang</label>
                                <select class="form-control" id="edit_status_barang" name="status_barang" required>
                                    <option value="oke">Oke</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="perbaikan">Perbaikan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_notes">Notes</label>
                                <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteAssetModal" tabindex="-1" aria-labelledby="deleteAssetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAssetModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data asset ini?</p>
                <p>Asset: <span id="delete_asset_name"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<!-- Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="alertMessage"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
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
            // Setup CSRF Token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize DataTable
            let assetsTable = $('#assetsTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                lengthMenu: [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]], 
                pageLength: 10,
                ajax: {
                    url: "{{ url('/assets/data') }}",
                    dataSrc: function(json) {
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    { data: 'asset_code' },
                    { data: 'brand' },
                    { data: 'serial_number' },
                    { data: 'lokasi_awal' },
                    { data: 'lokasi_tujuan' },
                    { data: 'type' },
                    {
                        data: 'tanggal_visit',
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                return `${year}-${month}-${day}`;
                            }
                            return '';
                        }
                    },
                    {
                        data: 'status_barang',
                        render: function(data) {
                            let badgeClass = '';
                        
                            if (data === 'oke') {
                                badgeClass = 'bg-success';
                            } else if (data === 'rusak') {
                                badgeClass = 'bg-danger';
                            } else if (data === 'perbaikan') {
                                badgeClass = 'bg-warning';
                            } else {
                                badgeClass = 'bg-secondary'; 
                            }
                            
                            const capitalizedData = data.charAt(0).toUpperCase() + data.slice(1);
                            return '<span class="badge ' + badgeClass + '">' + capitalizedData + '</span>';
                        }
                    },
                    { data: 'notes' },
                    {
                        data: 'created_at',
                        render: function(data) {
                            if (data) {
                                const date = new Date(data);
                                const year = date.getFullYear();
                                const month = String(date.getMonth() + 1).padStart(2, '0');
                                const day = String(date.getDate()).padStart(2, '0');
                                const hours = String(date.getHours()).padStart(2, '0');
                                const minutes = String(date.getMinutes()).padStart(2, '0');

                                return `${year}-${month}-${day} ${hours}:${minutes}`;
                            }
                            return '';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="action-buttons">
                                    <button class="btn btn-sm btn-edit" data-id="${row.id}" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-delete" data-id="${row.id}" 
                                            data-asset="${row.asset_code}" title="Delete">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
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
                }
            });

            // Show Add Modal
            $('#addAssetBtn').on('click', function() {
                $('#addAssetForm')[0].reset();
                $('#addAssetModal').modal('show');
            });

            // Add Asset Form Submit
            $('#addAssetForm').on('submit', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: "{{ route('assets.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addAssetModal').modal('hide');
                        assetsTable.ajax.reload();
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-success">
                                Data asset berhasil ditambahkan.
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat menyimpan data.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '<ul>';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += `<li>${value}</li>`;
                            });
                            errorMessage += '</ul>';
                        }
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-danger">
                                ${errorMessage}
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    }
                });
            });

            // Edit Asset
            $(document).on('click', '.btn-edit', function() {
                const assetId = $(this).data('id');
                
                // Fetch asset data
                $.ajax({
                    url: "{{ url('/assets/edit') }}/" + assetId,
                    method: 'GET',
                    success: function(response) {
                        const asset = response.asset;
                        
                        // Fill the edit form with asset data
                        $('#edit_asset_id').val(asset.id);
                        $('#edit_warehouse_master_site_id').val(asset.warehouse_master_site_id);
                        $('#edit_category_id').val(asset.category_id);
                        $('#edit_asset_code').val(asset.asset_code);
                        $('#edit_brand').val(asset.brand);
                        $('#edit_tipe').val(asset.tipe);
                        $('#edit_serial_number').val(asset.serial_number);
                        $('#edit_lokasi_awal').val(asset.lokasi_awal);
                        $('#edit_lokasi_tujuan').val(asset.lokasi_tujuan);
                        $('#edit_type').val(asset.type);
                        $('#edit_tanggal_visit').val(asset.tanggal_visit);
                        $('#edit_status_barang').val(asset.status_barang);
                        $('#edit_notes').val(asset.notes);
                        
                        // Show the edit modal
                        $('#editAssetModal').modal('show');
                    },
                    error: function() {
                        $('#alertMessage').html(`
                            <div class="alert alert-danger">
                                Gagal mengambil data asset.
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    }
                });
            });

            // Update Asset Form Submit
            $('#editAssetForm').on('submit', function(e) {
                e.preventDefault();
                const assetId = $('#edit_asset_id').val();
                
                $.ajax({
                    url: "{{ url('/assets/update') }}/" + assetId,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editAssetModal').modal('hide');
                        assetsTable.ajax.reload();
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-success">
                                Data asset berhasil diperbarui.
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memperbarui data.';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = '<ul>';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += `<li>${value}</li>`;
                            });
                            errorMessage += '</ul>';
                        }
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-danger">
                                ${errorMessage}
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    }
                });
            });

            // Delete Asset
            $(document).on('click', '.btn-delete', function() {
                const assetId = $(this).data('id');
                const assetCode = $(this).data('asset');
                
                $('#delete_asset_name').text(assetCode);
                $('#confirmDelete').data('id', assetId);
                $('#deleteAssetModal').modal('show');
            });

            // Confirm Delete
            $('#confirmDelete').on('click', function() {
                const assetId = $(this).data('id');
                
                $.ajax({
                    url: "{{ url('/assets/destroy') }}/" + assetId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteAssetModal').modal('hide');
                        assetsTable.ajax.reload();
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-success">
                                Data asset berhasil dihapus.
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    },
                    error: function() {
                        $('#deleteAssetModal').modal('hide');
                        
                        $('#alertMessage').html(`
                            <div class="alert alert-danger">
                                Gagal menghapus data asset.
                            </div>
                        `);
                        $('#alertModal').modal('show');
                    }
                });
            });

            // Export Excel
            $('#exportBtn').on('click', function() {
                window.location.href = "{{ route('assets.export') }}";
            });
        });
    </script>
@endsection