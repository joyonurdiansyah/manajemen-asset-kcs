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
            border-bottom: 1px solid rgba(0,0,0,.125);
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
            <h5 class="card-title mb-0">Data Asset IT</h5>
            <div class="header-buttons">
                <button type="button" class="btn btn-export">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <button type="button" class="btn btn-add">
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
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            let assetsTable = $('#assetsTable').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                lengthMenu: [[5, 10, 15, 20, -1], [5, 10, 15, 20, "All"]], 
                pageLength: 10,
                ajax: {
                    url: "{{ url('/assets/data') }}",
                    dataSrc: function(json) {
                        console.log(json);
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
                        data: 'lokasi_awal'
                    },
                    {
                        data: 'lokasi_tujuan'
                    },
                    { data: 'type' },
                    {
                        data: 'tanggal_visit',
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
                        data: 'status_barang',
                        render: function(data) {
                            let badgeClass = '';
                        
                            if (data === 'oke') {
                                badgeClass = 'bg-success';
                            } else if (data === 'rusak') {
                                badgeClass = 'bg-danger';
                            } else {
                                badgeClass = 'bg-secondary'; 
                            }
                            
                            const capitalizedData = data.charAt(0).toUpperCase() + data.slice(1);
                            return '<span class="badge ' + badgeClass + '">' + capitalizedData + '</span>';
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
                                    <button class="btn-edit" data-id="${row.id}" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button class="btn-delete" data-id="${row.id}" title="Delete">
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
            
            $('.btn-add').click(function() {
                window.location.href = "{{ url('/assets/create') }}";
            });
            
            $('.btn-export').click(function() {
                let tableData = [];
                let headers = [];
                
                $('#assetsTable thead th').each(function(index) {
                    if (index < $('#assetsTable thead th').length - 1) { 
                        headers.push($(this).text());
                    }
                });
                
                tableData.push(headers);
                
                let visibleData = assetsTable.rows({ search: 'applied' }).data();
                
                visibleData.each(function(rowData, index) {
                    let row = [];
                    
                    // tambah nomor kolom
                    row.push(index + 1);
                    row.push(rowData.asset_code);
                    row.push(rowData.brand);
                    row.push(rowData.serial_number);
                    row.push(rowData.Pangkalan);
                    
                    // Format Tanggal
                    let dateReceived = '';
                    if (rowData.date_received) {
                        const date = new Date(rowData.date_received);
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const hours = String(date.getHours()).padStart(2, '0');
                        const minutes = String(date.getMinutes()).padStart(2, '0');
                        dateReceived = `${year}-${month}-${day} ${hours}:${minutes}`;
                    }
                    row.push(dateReceived);
                    row.push(rowData.status_barang.charAt(0).toUpperCase() + rowData.status_barang.slice(1));
                    row.push(rowData.notes);
                    
                    let createdAt = '';
                    if (rowData.created_at) {
                        const date = new Date(rowData.created_at);
                        const year = date.getFullYear();
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const day = String(date.getDate()).padStart(2, '0');
                        const hours = String(date.getHours()).padStart(2, '0');
                        const minutes = String(date.getMinutes()).padStart(2, '0');
                        createdAt = `${year}-${month}-${day} ${hours}:${minutes}`;
                    }
                    row.push(createdAt);
                    
                    tableData.push(row);
                });
                

                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(tableData);
                

                XLSX.utils.book_append_sheet(wb, ws, "Assets");
                const now = new Date();
                const dateStr = now.getFullYear() + '-' + 
                String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                String(now.getDate()).padStart(2, '0');
                

                XLSX.writeFile(wb, `Assets_Report_${dateStr}.xlsx`);
            });
            
            $('#assetsTable').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                window.location.href = "{{ url('/assets/edit') }}/" + id;
            });
            
            $('#assetsTable').on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                
                if (confirm('Are you sure you want to delete this asset?')) {
                    $.ajax({
                        url: "{{ url('/assets/delete') }}/" + id,
                        type: 'DELETE',
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(result) {
                            $('#assetsTable').DataTable().ajax.reload();
                            alert('Asset deleted successfully!');
                        },
                        error: function(error) {
                            console.error('Error:', error);
                            alert('Failed to delete asset. Please try again.');
                        }
                    });
                }
            });
        });
    </script>
@endsection