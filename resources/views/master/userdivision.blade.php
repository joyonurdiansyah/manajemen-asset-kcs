@extends('layouts.app')

@section('content')
    <style>
        /* Button styling */
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 1.25rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .btn-add {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        .btn-add i {
            font-size: 0.875rem;
        }

        /* Export button styling */
        .btn-export {
            background-color: #17a2b8;
            color: white;
            border: none;
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-right: 10px;
        }

        .btn-export:hover {
            background-color: #138496;
        }

        .btn-export i {
            font-size: 0.875rem;
        }

        .header-buttons {
            display: flex;
            gap: 10px;
        }

        div.dataTables_length {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        div.dataTables_filter {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        /* Action button styling */
        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
        }

        .btn-edit {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .btn-edit:hover {
            background-color: #0069d9;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }
    </style>

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Data Master User & Division</h5>
            <div class="header-buttons">
                <button type="button" class="btn btn-export">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button type="button" class="btn btn-add">
                    <i class="fas fa-plus-circle"></i> Tambah Data User
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="user-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="w-10">No</th>
                            <th class="w-25">Nama</th>
                            <th class="w-25">Email</th>
                            <th class="w-20">Kode Divisi</th>
                            <th class="w-20">Nama Divisi</th>
                            <th class="w-10">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
            $('#user-table').DataTable({
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
                            <button type="button" class="btn-edit" data-id="${data}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="button" class="btn-delete" data-id="${data}">
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
