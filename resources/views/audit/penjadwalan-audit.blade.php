@extends('layouts.app')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    :root {
        --unassigned-color: #FF6B6B;
        --open-color: #4ECDC4;
        --waiting-color: #45B7D1;
        --resolved-color: #2AB673;
        
        --low-priority-color: #FFA726;
        --medium-priority-color: #FF7043;
        --high-priority-color: #E53935;
    }

    body {
        background-color: #f4f6f9;
        font-family: 'Arial', sans-serif;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .fc-event:hover {
        opacity: 0.9;
        transform: scale(1.02);
    }

    /* Status Colors */
    .event-unassigned {
        background-color: var(--unassigned-color) !important;
        border-color: var(--unassigned-color) !important;
    }

    .event-open {
        background-color: var(--open-color) !important;
        border-color: var(--open-color) !important;
    }

    .event-waiting {
        background-color: var(--waiting-color) !important;
        border-color: var(--waiting-color) !important;
    }

    .event-resolved {
        background-color: var(--resolved-color) !important;
        border-color: var(--resolved-color) !important;
    }

    /* Priority Indicators */
    .priority-low {
        border-left: 4px solid var(--low-priority-color);
    }

    .priority-medium {
        border-left: 4px solid var(--medium-priority-color);
    }

    .priority-high {
        border-left: 4px solid var(--high-priority-color);
    }

    #calendar {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        padding: 15px;
    }

    .fc-toolbar-title {
        font-weight: bold;
        color: #2c3e50;
        text-transform: capitalize;
    }

    .fc-button {
        background-color: #3498db !important;
        border: none !important;
        transition: all 0.3s ease;
    }

    .fc-button:hover {
        background-color: #2980b9 !important;
    }

    .fc-event-main {
        display: flex;
        flex-direction: column;
        padding: 5px;
    }

    .fc-event-title {
        font-weight: bold;
        margin-bottom: 3px;
    }

    .fc-event-dates {
        font-size: 0.8em;
        opacity: 0.8;
    }

    .filter-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }

    .status-legend, .priority-legend {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-bottom: 10px;
    }

    .status-legend-item, .priority-legend-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .status-legend-color, .priority-legend-color {
        width: 15px;
        height: 15px;
        border-radius: 50%;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Penjadwalan Pengecekan Barang</h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <nav aria-label="breadcrumb">
                            <ol class="mb-0 breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Penjadwalan Pengecekan Barang</li>
                            </ol>
                        </nav>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">Tambah Jadwal</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Penjadwalan -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formAddEdit">
                @csrf
                <input type="hidden" name="id" id="eventId">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddEditLabel">Tambah Jadwal Pengecekan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="warehouse_master_site_id" class="form-label">Lokasi</label>
                        <select class="form-select" name="warehouse_master_site_id" required>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="request_subject" class="form-label">Subject</label>
                        <input type="text" class="form-control" name="request_subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="priority" class="form-label">Prioritas</label>
                        <select class="form-select" name="priority">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="arrival_date" class="form-label">Tanggal Datang</label>
                        <input type="date" class="form-control" name="arrival_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="arrival_completed_date" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" name="arrival_completed_date">
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="unassigned">Unassigned</option>
                            <option value="open">Open</option>
                            <option value="waiting">Waiting</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            editable: true,
            eventResizableFromStart: true,
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            views: {
                dayGridMonth: { buttonText: 'Bulan' },
                timeGridWeek: { buttonText: 'Minggu' },
                listWeek: { buttonText: 'Agenda' }
            },
            events: '/jadwal/fetch',

            eventContent: function(arg) {
                let event = arg.event;
                let startDate = event.start ? event.start.toLocaleDateString() : '';
                let endDate = event.end ? event.end.toLocaleDateString() : startDate;

                const priority = event.extendedProps.priority || 'low';
                const status = event.extendedProps.status || 'unassigned';
                
                // Create priority icon
                const getPriorityIcon = (priorityLevel) => {
                    switch(priorityLevel) {
                        case 'high': return '🔥';
                        case 'medium': return '⚠️'; 
                        default: return '✅'; 
                    }
                };
                
                // Create a custom HTML element for the event rendering
                let eventElement = document.createElement('div');
                eventElement.classList.add(`priority-${priority}`);
                eventElement.innerHTML = `
                    <div class="fc-event-main">
                        <div class="fc-event-title">
                            <span class="priority-icon">${getPriorityIcon(priority)}</span>
                            ${event.title || event.extendedProps.request_subject}
                        </div>
                        <div class="fc-event-dates">
                            <small>🕒 Mulai: ${startDate}</small>
                            <small>✔️ Selesai: ${endDate}</small>
                        </div>
                    </div>
                `;
                
                return { domNodes: [eventElement] };
            },

            eventClassNames: function(arg) {
                const status = arg.event.extendedProps.status || 'unassigned';
                const priority = arg.event.extendedProps.priority || 'low';
                
                return [
                    `event-${status}`,
                    `priority-${priority}`
                ];
            },
        
            
            // Improved event drop handler
            eventDrop: function(info) {
                const event = info.event;
                const oldEvent = info.oldEvent;
                
                // Calculate the date difference between original start and new start
                const originalStart = oldEvent.start;
                const newStart = event.start;
                const dateDiff = newStart ? 
                    Math.round((newStart.getTime() - originalStart.getTime()) / (1000 * 3600 * 24)) : 
                    0;

                // Prepare event data with adjusted dates
                const eventData = {
                    _token: document.querySelector('input[name="_token"]').value,
                    warehouse_master_site_id: event.extendedProps.warehouseMasterSite?.id || null,
                    request_subject: event.title || event.extendedProps.request_subject,
                    description: event.extendedProps.description || '',
                    priority: event.extendedProps.priority || 'low',
                    
                    // Adjust dates by the date difference
                    arrival_date: originalStart ? 
                        new Date(originalStart.getTime() + dateDiff * 24 * 60 * 60 * 1000)
                            .toISOString().split('T')[0] : 
                        null,
                    
                    arrival_completed_date: oldEvent.end ? 
                        new Date(oldEvent.end.getTime() + dateDiff * 24 * 60 * 60 * 1000)
                            .toISOString().split('T')[0] : 
                        null,

                    status: event.extendedProps.status || 'unassigned'
                };

                // Send update request to server
                fetch(`{{ url('/jadwal/update') }}/${event.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': eventData._token
                    },
                    body: JSON.stringify(eventData)
                })
                .then(response => {
                    if (!response.ok) {
                        // Revert event if update fails
                        info.revert();
                        return response.json().then(errorData => {
                            throw new Error(JSON.stringify(errorData));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Success handling
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Jadwal berhasil diperbarui',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Explicitly refresh events to ensure UI consistency
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Drag and Drop Error:', error);
                    
                    // Error handling
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Gagal memperbarui jadwal: ' + error.message,
                        showConfirmButton: true
                    });

                    // Ensure calendar state is consistent
                    calendar.refetchEvents();
                });
            },
    
            // Improved event resize handler
            eventResize: function(info) {
                const event = info.event;
                
                // Prepare data for update
                const eventData = {
                    _token: document.querySelector('input[name="_token"]').value,
                    warehouse_master_site_id: event.extendedProps.warehouseMasterSite?.id || null,
                    request_subject: event.title || event.extendedProps.request_subject,
                    description: event.extendedProps.description || '',
                    priority: event.extendedProps.priority || 'low',
                    arrival_date: event.start ? event.start.toISOString().split('T')[0] : null,
                    arrival_completed_date: event.end ? event.end.toISOString().split('T')[0] : null,
                    status: event.extendedProps.status || 'unassigned'
                };
    
                // Send update request to server
                fetch(`{{ url('/jadwal/update') }}/${event.id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': eventData._token
                    },
                    body: JSON.stringify(eventData)
                })
                .then(response => {
                    if (!response.ok) {
                        // Revert event if update fails
                        info.revert();
                        return response.json().then(errorData => {
                            throw new Error(JSON.stringify(errorData));
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Success handling
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Durasi jadwal berhasil diperbarui',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
    
                    // Explicitly refresh events to ensure UI consistency
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Resize Error:', error);
                    
                    // Error handling
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Gagal memperbarui durasi jadwal: ' + error.message,
                        showConfirmButton: true
                    });
    
                    // Ensure calendar state is consistent
                    calendar.refetchEvents();
                });
            },
            
            // Event click handler
            eventClick: function(info) {
                const event = info.event;
                Swal.fire({
                    title: 'Detail Jadwal',
                    html: `
                        <div class="text-start">
                            <p><strong>Judul:</strong> ${event.title || event.extendedProps.request_subject}</p>
                            <p><strong>Lokasi:</strong> ${event.extendedProps.warehouseMasterSite?.nama_lokasi || 'Tidak tersedia'}</p>
                            <p><strong>Deskripsi:</strong> ${event.extendedProps.description || 'Tidak ada deskripsi'}</p>
                            <p><strong>Prioritas:</strong> ${event.extendedProps.priority || 'Tidak ditentukan'}</p>
                            <p><strong>Status:</strong> ${event.extendedProps.status || 'Tidak ditentukan'}</p>
                            <p><strong>Tanggal Datang:</strong> ${event.start ? event.start.toLocaleDateString() : 'Tidak tersedia'}</p>
                            <p><strong>Tanggal Selesai:</strong> ${event.end ? event.end.toLocaleDateString() : 'Belum ditentukan'}</p>
                        </div>
                    `,
                    showDenyButton: true,
                    showCancelButton: true,
                    confirmButtonText: 'Edit',
                    denyButtonText: 'Hapus',
                    cancelButtonText: 'Tutup'
                }).then((result) => {
                    if (result.isConfirmed) {
                        editEvent(event.id);
                    } else if (result.isDenied) {
                        deleteEvent(event.id);
                    }
                });
            },

            eventDidMount: function(info) {
            // Add custom styling to make dates more visible
            info.el.style.backgroundColor = '#2196f3';
            info.el.style.color = 'white';
            info.el.style.padding = '5px';
            info.el.style.borderRadius = '4px';
            },

            
            // Coloring and styling
            eventColor: '#2196f3', 
            eventClassNames: function(arg) {
                let status = arg.event.extendedProps.status;
                return [`event-${status}`];
            }
        });
        calendar.render();
    
        // Form submission handler
        document.getElementById('formAddEdit').addEventListener('submit', function (e) {
            e.preventDefault();
            let formData = new FormData(this);
            let eventId = document.getElementById('eventId').value;
            let url = eventId 
                ? `{{ url('/jadwal/update') }}/${eventId}`
                : "{{ route('jadwal.store') }}";
    
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw new Error(JSON.stringify(errorData));
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success' || data.status === 'updated') {
                    // Refresh events
                    calendar.refetchEvents();
                    
                    // Reset form
                    document.getElementById('formAddEdit').reset();
                    document.getElementById('eventId').value = '';
                    
                    // Hide modal
                    var modal = bootstrap.Modal.getInstance(document.getElementById('modalAdd'));
                    modal.hide();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: eventId ? 'Jadwal berhasil diperbarui' : 'Jadwal berhasil ditambahkan'
                    });
                } else {
                    throw new Error(data.message || 'Gagal menyimpan jadwal');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                let errorMessage = 'Terjadi kesalahan saat menyimpan jadwal';
                
                try {
                    const errorResponse = JSON.parse(error.message);
                    if (errorResponse.errors) {
                        errorMessage = Object.values(errorResponse.errors).flat().join('\n');
                    }
                } catch {
                    errorMessage = error.message;
                }
    
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: errorMessage
                });
            });
        });
    
        // Edit event function
        function editEvent(id) {
            fetch(`{{ url('/jadwal/edit') }}/${id}`)
                .then(response => response.json())
                .then(data => {
                    // Populate form with event data
                    document.getElementById('eventId').value = data.id;
                    document.querySelector('select[name="warehouse_master_site_id"]').value = data.warehouse_master_site_id;
                    document.querySelector('input[name="request_subject"]').value = data.request_subject;
                    document.querySelector('textarea[name="description"]').value = data.description || '';
                    document.querySelector('select[name="priority"]').value = data.priority || 'low';
                    document.querySelector('input[name="arrival_date"]').value = data.arrival_date;
                    document.querySelector('input[name="arrival_completed_date"]').value = data.arrival_completed_date || '';
                    document.querySelector('select[name="status"]').value = data.status || 'unassigned';
    
                    // Change modal title
                    document.getElementById('modalAddEditLabel').textContent = 'Edit Jadwal Pengecekan';
                    
                    // Show modal
                    var modal = new bootstrap.Modal(document.getElementById('modalAdd'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Gagal mengambil data jadwal'
                    });
                });
        }
    
        // Delete event function
        function deleteEvent(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan jadwal yang dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('/jadwal/delete') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'deleted') {
                            calendar.refetchEvents();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Jadwal berhasil dihapus'
                            });
                        } else {
                            throw new Error('Gagal menghapus jadwal');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat menghapus jadwal'
                        });
                    });
                }
            });
        }
    });
</script>
@endsection