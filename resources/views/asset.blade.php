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

        //style filter
        .filter-container {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }

        .filter-group {
            min-width: 180px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 3px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .btn-filter {
            background-color: #17a2b8;
            color: white;
            margin-right: 10px;
        }

        .btn-filter:hover {
            background-color: #138496;
            color: white;
        }

        /* Style for the export button */
        .btn-export {
            background-color: #28a745;
            color: white;
            margin-right: 10px;
        }

        .btn-export:hover {
            background-color: #218838;
            color: white;
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
                    <button type="button" class="gap-2 btn btn-success d-flex align-items-center" id="importBtn" data-bs-toggle="modal" data-bs-target="#importAssetModal">
                        <i class="fas fa-file-import"></i> <span>Import Excel</span>
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
                                    <select class="form-control" id="warehouse_master_site_id"
                                        name="warehouse_master_site_id" required readonly>
                                        <option value="">Pilih Warehouse</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                    <em class="text-muted">Warehouse site akan otomatis terisi dari lokasi awal</em>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category_id">Kategori</label>
                                    <select class="form-control" id="category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach (App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subcategory_id">Subkategori</label>
                                    <select class="form-control" id="subcategory_id" name="subcategory_id">
                                        <option value="">Pilih Sub Kategori</option>
                                        @foreach (App\Models\Subcategory::all() as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                                    <label for="serial_number">Serial Number</label>
                                    <input type="text" class="form-control" id="serial_number" name="serial_number">
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
                                    <label for="lokasi_awal_id">Lokasi Awal</label>
                                    <select class="form-control" id="lokasi_awal_id" name="lokasi_awal_id">
                                        <option value="">Pilih Lokasi Awal</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="lokasi_tujuan_id">Lokasi Tujuan</label>
                                    <select class="form-control" id="lokasi_tujuan_id" name="lokasi_tujuan_id">
                                        <option value="">Pilih Lokasi Tujuan</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="mb-3 form-group">
                                <label for="notes">Notes</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                                <div class="invalid-feedback" id="notes_error"></div>
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
    <div class="modal fade" id="editAssetModal" tabindex="-1" aria-labelledby="editAssetModalLabel"
        aria-hidden="true">
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
                        <input type="hidden" id="edit_asset_id" name="id">
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_warehouse_master_site_id">Warehouse Site</label>
                                    <select class="form-control" id="edit_warehouse_master_site_id"
                                        name="warehouse_master_site_id" required>
                                        <option value="">Pilih Warehouse</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                    <em class="text-muted">Warehouse site akan otomatis terisi dari lokasi awal</em>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_category_id">Kategori</label>
                                    <select class="form-control" id="edit_category_id" name="category_id" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach (App\Models\Category::all() as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_subcategory_id">Subkategori</label>
                                    <select class="form-control" id="edit_subcategory_id" name="subcategory_id">
                                        <option value="">Pilih Subkategori</option>
                                        <!-- Will be populated dynamically via JavaScript -->
                                    </select>
                                </div>
                            </div>
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
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_asset_code">Nomor Asset</label>
                                    <input type="text" class="form-control" id="edit_asset_code" name="asset_code"
                                        required>
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
                                    <label for="edit_serial_number">Serial Number</label>
                                    <input type="text" class="form-control" id="edit_serial_number"
                                        name="serial_number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_tanggal_visit">Tanggal Kunjungan</label>
                                    <input type="date" class="form-control" id="edit_tanggal_visit"
                                        name="tanggal_visit">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lokasi_awal_id">Lokasi Awal</label>
                                    <select class="form-control" id="edit_lokasi_awal_id" name="lokasi_awal_id">
                                        <option value="">Pilih Lokasi Awal</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_lokasi_tujuan_id">Lokasi Tujuan</label>
                                    <select class="form-control" id="edit_lokasi_tujuan_id" name="lokasi_tujuan_id">
                                        <option value="">Pilih Lokasi Tujuan</option>
                                        @foreach (App\Models\WarehouseMasterSite::all() as $site)
                                            <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <div class="col-md-12">
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
    <div class="modal fade" id="deleteAssetModal" tabindex="-1" aria-labelledby="deleteAssetModalLabel"
        aria-hidden="true">
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

    <!-- Import Excel Modal -->
    <div class="modal fade" id="importAssetModal" tabindex="-1" aria-labelledby="importAssetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="shadow-lg modal-content rounded-4">
                <div class="text-white modal-header bg-primary rounded-top-4">
                    <h5 class="modal-title" id="importAssetModalLabel"><i class="fas fa-upload me-2"></i>Import Data Asset IT</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="importAssetForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="excel_file" class="form-label fw-semibold">Pilih File Excel (.xlsx)</label>
                            <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx" required>
                        </div>
                        <div class="mb-4">
                            <p class="fw-bold">Catatan:</p>
                            <ul class="small text-muted ps-3">
                                <li>Format yang diterima adalah <code>.xlsx</code></li>
                                <li>Pastikan data lokasi awal, lokasi tujuan, kategori, dan subkategori sudah ada di sistem</li>
                                <li>Penulisan lokasi dibuat berdasarkan kode warehouse site, contoh <strong>cikarang</strong> gunakan <strong>111</strong></li>
                                <li>Jika subkategori tidak ditemukan, data akan tetap disimpan dengan subkategori kosong</li>
                            </ul>
                            <a href="{{ route('assets.import.template') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-download me-1"></i> Download Template
                            </a>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" form="importAssetForm" class="btn btn-primary" id="confirmImport">
                        <i class="fas fa-check-circle me-1"></i> Import
                    </button>
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
                lengthMenu: [
                    [5, 10, 15, 20, -1],
                    [5, 10, 15, 20, "All"]
                ],
                pageLength: 10,
                ajax: {
                    url: "{{ url('/assets/data') }}",
                    dataSrc: function(json) {
                        setTimeout(function() {
                            populateFilterOptions();
                        }, 100);
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + 1
                    },
                    {
                        data: 'asset_code'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'serial_number'
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.lokasi_awal ? data.lokasi_awal.nama_lokasi : '';
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.lokasi_tujuan ? data.lokasi_tujuan.nama_lokasi : '';
                        }
                    },
                    {
                        data: null,
                        render: function(data) {
                            return data.category ? data.category.name : '';
                        }
                    },
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
                            return '<span class="badge ' + badgeClass + '">' + capitalizedData +
                                '</span>';
                        }
                    },
                    {
                        data: 'notes'
                    },
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
                        $('.dataTables_info').html('Showing 1 to ' + settings.fnRecordsTotal() +
                            ' of ' + settings.fnRecordsTotal() + ' entries');
                    }
                }
            });

            // Add filter container above the table
            $('#assetsTable_wrapper .row:first').after(`
                <div class="mb-3 row filter-container" style="display:none;">
                    <div class="mb-2 col-12">
                        <h5>Filter Data</h5>
                    </div>
                    <div class="flex-wrap gap-2 col-md-12 d-flex">
                        <div class="filter-group">
                            <label for="filter-asset_code">Nomor Asset</label>
                            <select id="filter-asset_code" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-brand">Merk Barang</label>
                            <select id="filter-brand" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-lokasi_awal">Lokasi Awal</label>
                            <select id="filter-lokasi_awal" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-lokasi_tujuan">Lokasi Tujuan</label>
                            <select id="filter-lokasi_tujuan" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-category">Kategori</label>
                            <select id="filter-category" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="filter-status_barang">Status Barang</label>
                            <select id="filter-status_barang" class="form-select filter-select">
                                <option value="">Semua</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 col-md-12">
                        <button id="applyFilters" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        <button id="resetFilters" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            `);

            // Add filter toggle button in the header buttons
            $('.header-buttons').prepend(`
                <button type="button" class="btn btn-filter" id="filterBtn">
                    <i class="fas fa-filter"></i> Filter Data
                </button>
            `);

            // Function to populate filter options
            function populateFilterOptions() {
                // Get all data from the table
                const data = assetsTable.data().toArray();
                
                // Populate asset code filter
                const assetCodes = [...new Set(data.map(item => item.asset_code))].filter(Boolean).sort();
                $('#filter-asset_code').empty().append('<option value="">Semua</option>');
                assetCodes.forEach(code => {
                    $('#filter-asset_code').append(`<option value="${code}">${code}</option>`);
                });
                
                // Populate brand filter
                const brands = [...new Set(data.map(item => item.brand))].filter(Boolean).sort();
                $('#filter-brand').empty().append('<option value="">Semua</option>');
                brands.forEach(brand => {
                    $('#filter-brand').append(`<option value="${brand}">${brand}</option>`);
                });
                
                // Populate lokasi awal filter
                const lokasiAwal = [...new Set(data.map(item => item.lokasi_awal ? item.lokasi_awal.nama_lokasi : ''))].filter(Boolean).sort();
                $('#filter-lokasi_awal').empty().append('<option value="">Semua</option>');
                lokasiAwal.forEach(lokasi => {
                    $('#filter-lokasi_awal').append(`<option value="${lokasi}">${lokasi}</option>`);
                });
                
                // Populate lokasi tujuan filter
                const lokasiTujuan = [...new Set(data.map(item => item.lokasi_tujuan ? item.lokasi_tujuan.nama_lokasi : ''))].filter(Boolean).sort();
                $('#filter-lokasi_tujuan').empty().append('<option value="">Semua</option>');
                lokasiTujuan.forEach(lokasi => {
                    $('#filter-lokasi_tujuan').append(`<option value="${lokasi}">${lokasi}</option>`);
                });
                
                // Populate category filter
                const categories = [...new Set(data.map(item => item.category ? item.category.name : ''))].filter(Boolean).sort();
                $('#filter-category').empty().append('<option value="">Semua</option>');
                categories.forEach(category => {
                    $('#filter-category').append(`<option value="${category}">${category}</option>`);
                });
                
                // Populate status barang filter
                const statuses = [...new Set(data.map(item => item.status_barang))].filter(Boolean).sort();
                $('#filter-status_barang').empty().append('<option value="">Semua</option>');
                statuses.forEach(status => {
                    const capitalizedStatus = status.charAt(0).toUpperCase() + status.slice(1);
                    $('#filter-status_barang').append(`<option value="${status}">${capitalizedStatus}</option>`);
                });
            }

            // Event handler for filter button
            $('#filterBtn').on('click', function() {
                $('.filter-container').toggle();
            });

            // Event handler for apply filters button
            $('#applyFilters').on('click', function() {
                // Apply filters to the table
                assetsTable.columns().every(function(index) {
                    const column = this;
                    let filterValue = '';
                    
                    switch(index) {
                        case 1: // asset_code
                            filterValue = $('#filter-asset_code').val();
                            break;
                        case 2: // brand
                            filterValue = $('#filter-brand').val();
                            break;
                        case 4: // lokasi_awal
                            filterValue = $('#filter-lokasi_awal').val();
                            break;
                        case 5: // lokasi_tujuan
                            filterValue = $('#filter-lokasi_tujuan').val();
                            break;
                        case 6: // category
                            filterValue = $('#filter-category').val();
                            break;
                        case 8: // status_barang
                            filterValue = $('#filter-status_barang').val();
                            break;
                    }
                    
                    if (filterValue) {
                        column.search(filterValue === 'null' ? '' : filterValue, true, false).draw();
                    }
                });
            });

            // Event handler for reset filters button
            $('#resetFilters').on('click', function() {
                $('.filter-select').val('');
                assetsTable.columns().search('').draw();
            });

            $('#exportBtn').on('click', function() {
                const filteredData = assetsTable.rows({ search: 'applied' }).data().toArray();
                
                if (filteredData.length === 0) {
                    alert('Tidak ada data untuk diekspor!');
                    return;
                }
                
                $.ajax({
                    url: "{{ route('assets.export.filtered') }}",
                    method: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        filtered_data: JSON.stringify(filteredData)
                    },
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(response) {
                        // Create a blob from the response
                        const blob = new Blob([response], {
                            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        });
                        
                        // Create a download link and trigger it
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = 'Data_Assets_IT_' + new Date().toISOString().slice(0, 19).replace(/[-:T]/g, '') + '.xlsx';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan saat mengekspor data.');
                        console.error(xhr);
                    }
                });
            });

            // Event handler for lokasi_awal_id to auto-fill warehouse_master_site_id
            $('#lokasi_awal_id').on('change', function() {
                const lokasiAwalId = $(this).val();
                $('#warehouse_master_site_id').val(lokasiAwalId);
            });

            // Show Add Modal
            $('#addAssetBtn').on('click', function() {
                $('#addAssetForm')[0].reset();
                $('#addAssetModal').modal('show');
            });

            //import excel_file

            // Show Import Modal
            $('#importBtn').on('click', function() {
                $('#importAssetForm')[0].reset();
                $('#importAssetModal').modal('show');
            });

            // Handle Import Form Submit
            $('#confirmImport').on('click', function() {
                // Create a FormData object
                const formData = new FormData($('#importAssetForm')[0]);

                // Show loading indicator
                $(this).html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Importing...'
                    );
                $(this).prop('disabled', true);

                $.ajax({
                    url: "{{ route('assets.import') }}",
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#importAssetModal').modal('hide');
                        assetsTable.ajax.reload();

                        let message = `
                <div class="alert alert-success">
                    <p><strong>Import berhasil!</strong></p>
                    <p>Data yang berhasil diimport: ${response.success} baris</p>
                `;

                        if (response.errors && response.errors.length > 0) {
                            message +=
                                `<p>Ada ${response.errors.length} baris yang tidak dapat diimport:</p><ul>`;
                            response.errors.forEach(function(error) {
                                message +=
                                    `<li>Baris ${error.row}: ${error.message}</li>`;
                            });
                            message += `</ul>`;
                        }

                        message += `</div>`;

                        $('#alertMessage').html(message);
                        $('#alertModal').modal('show');

                        // Reset button
                        $('#confirmImport').html('Import');
                        $('#confirmImport').prop('disabled', false);
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengimport data.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

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

                        // Reset button
                        $('#confirmImport').html('Import');
                        $('#confirmImport').prop('disabled', false);
                    }
                });
            });

            // Load subcategories for add form
            $('#category_id').on('change', function() {
                const categoryId = $(this).val();
                const subcategorySelect = $('#subcategory_id');

                subcategorySelect.html('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: `/get-subcategories/${categoryId}`,
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Pilih Subkategori</option>';
                            response.forEach(function(sub) {
                                options +=
                                    `<option value="${sub.id}">${sub.name}</option>`;
                            });
                            subcategorySelect.html(options);
                        },
                        error: function() {
                            subcategorySelect.html(
                                '<option value="">Gagal memuat subkategori</option>');
                        }
                    });
                } else {
                    subcategorySelect.html('<option value="">Pilih Subkategori</option>');
                }
            });

            // Load subcategories for edit form
            $('#edit_category_id').on('change', function() {
                const categoryId = $(this).val();
                const subcategorySelect = $('#edit_subcategory_id');

                subcategorySelect.html('<option value="">Loading...</option>');

                if (categoryId) {
                    $.ajax({
                        url: `/get-subcategories/${categoryId}`,
                        type: 'GET',
                        success: function(response) {
                            let options = '<option value="">Pilih Subkategori</option>';
                            response.forEach(function(sub) {
                                options +=
                                    `<option value="${sub.id}">${sub.name}</option>`;
                            });
                            subcategorySelect.html(options);

                            // If we have a saved subcategory value, restore it
                            if (subcategorySelect.data('saved-value')) {
                                subcategorySelect.val(subcategorySelect.data('saved-value'));
                                subcategorySelect.removeData('saved-value');
                            }
                        },
                        error: function() {
                            subcategorySelect.html(
                                '<option value="">Gagal memuat subkategori</option>');
                        }
                    });
                } else {
                    subcategorySelect.html('<option value="">Pilih Subkategori</option>');
                }
            });

            // Add Asset Form Submit
            $('#addAssetForm').on('submit', function(e) {
                e.preventDefault();

                // Instead of setting value on disabled field, create a hidden input
                const lokasiAwalId = $('#lokasi_awal_id').val();

                // Remove the disabled attribute just before submitting
                $('#warehouse_master_site_id').prop('disabled', false);
                $('#warehouse_master_site_id').val(lokasiAwalId);

                $.ajax({
                    url: "{{ route('assets.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Re-disable the field after submission
                        $('#warehouse_master_site_id').prop('disabled', true);
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
                        // Re-disable the field after submission
                        $('#warehouse_master_site_id').prop('disabled', true);

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

                // Clear form before fetching new data
                $('#editAssetForm')[0].reset();

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
                        $('#edit_serial_number').val(asset.serial_number);
                        $('#edit_lokasi_awal_id').val(asset.lokasi_awal_id);
                        $('#edit_lokasi_tujuan_id').val(asset.lokasi_tujuan_id);
                        $('#edit_tanggal_visit').val(asset.tanggal_visit);
                        $('#edit_status_barang').val(asset.status_barang);
                        $('#edit_notes').val(asset.notes);

                        // Save the subcategory value to restore after the category change event
                        $('#edit_subcategory_id').data('saved-value', asset.subcategory_id);

                        // Trigger the change event to load subcategories via AJAX
                        $('#edit_category_id').trigger('change');

                        // Set the warehouse site from lokasi_awal_id
                        if (asset.lokasi_awal_id) {
                            $('#edit_warehouse_master_site_id').val(asset.lokasi_awal_id);
                        }

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

            // Event handler for edit lokasi_awal_id to auto-fill warehouse_master_site_id
            $('#edit_lokasi_awal_id').on('change', function() {
                const lokasiAwalId = $(this).val();
                $('#edit_warehouse_master_site_id').val(lokasiAwalId);
            });

            // Update Asset Form Submit
            $('#editAssetForm').on('submit', function(e) {
                e.preventDefault();
                const assetId = $('#edit_asset_id').val();

                // Set warehouse_master_site_id from lokasi_awal_id before submitting
                const lokasiAwalId = $('#edit_lokasi_awal_id').val();
                if (lokasiAwalId) {
                    $('#edit_warehouse_master_site_id').val(lokasiAwalId);
                }

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
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
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
            // $('#exportBtn').on('click', function() {
            //     window.location.href = "{{ route('assets.export') }}";
            // });
        });
    </script>
@endsection
