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
            text-align: center;
        }
        
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #edf2f9;
            text-align: center;
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
            width: 10%;
        }

        table.dataTable th:nth-child(2),
        table.dataTable td:nth-child(2) {
            width: 70%;
            text-align: left;
        }

        table.dataTable th:nth-child(3),
        table.dataTable td:nth-child(3) {
            width: 20%;
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
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 card-title">Daftar Kategori</h5>
                <div class="header-buttons">
                    <button type="button" class="btn btn-export">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                    <button type="button" class="btn btn-add">
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
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#category-table').DataTable({
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
                columns: [
                    {
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
                                    <button type="button" class="btn-edit" data-id="${row.id}">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn-delete" data-id="${row.id}">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            `;
                        },
                        title: 'Action' 
                    }
                ]
            });
        });
    </script>
@endsection