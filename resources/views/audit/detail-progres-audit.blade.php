@extends('layouts.app')

@section('content')

<style>
    .progress-tracker {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin: 40px 0;
    }
    
    .progress-tracker::before {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 2px;
        width: 100%;
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .progress-step {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 30px;
    }
    
    .progress-step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fff;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }
    
    .progress-step.active .progress-step-circle {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .progress-step.completed .progress-step-circle {
        background-color: #198754;
        border-color: #198754;
        color: white;
    }
    
    .progress-step-text {
        font-size: 12px;
        position: absolute;
        top: 35px;
        white-space: nowrap;
    }
    
    .progress-line {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 2px;
        background-color: #e9ecef;
        z-index: 0;
    }
    
    .progress-line.active {
        background-color: #0d6efd;
    }
    
    .card-asset {
        transition: transform 0.3s;
        cursor: pointer;
        border-left: 5px solid transparent;
    }
    
    .card-asset:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card-asset.priority-low {
        border-left-color: #0dcaf0;
    }
    
    .card-asset.priority-medium {
        border-left-color: #ffc107;
    }
    
    .card-asset.priority-high {
        border-left-color: #dc3545;
    }
    
    .badge-status-unassigned {
        background-color: #6c757d;
    }
    
    .badge-status-open {
        background-color: #0dcaf0;
    }
    
    .badge-status-waiting {
        background-color: #ffc107;
    }
    
    .badge-status-resolved {
        background-color: #198754;
    }
    
    .search-box {
        position: relative;
    }
    
    .search-box .form-control {
        padding-left: 2.5rem;
    }
    
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
    }
</style>


<div class="py-4 container-fluid">
    <!-- Header with search and filters -->
    <div class="mb-4 row">
        <div class="col-md-4">
            <div class="search-box">
                <i class="bi bi-search text-muted"></i>
                <input type="text" class="form-control" id="searchInput" placeholder="Cari Aset...">
            </div>
        </div>
        <div class="col-md-8">
            <div class="gap-2 d-flex justify-content-md-end">
                <select class="w-auto form-select" id="statusFilter">
                    <option selected value="">Semua Status</option>
                    <option value="unassigned">Unassigned</option>
                    <option value="open">Open</option>
                    <option value="waiting">Waiting</option>
                    <option value="resolved">Resolved</option>
                </select>
                <select class="w-auto form-select" id="priorityFilter">
                    <option selected value="">Semua Prioritas</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
                <select class="w-auto form-select" id="locationFilter">
                    <option selected value="">Semua Lokasi</option>
                    @foreach($sites as $site)
                        <option value="{{ $site->id }}">{{ $site->nama_lokasi }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary" id="searchButton">Cari</button>
            </div>
        </div>
    </div>

    <!-- Asset Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="assetCards">
        @foreach($assetSchedules as $schedule)
        <div class="col asset-card" 
             data-status="{{ $schedule->status }}" 
             data-priority="{{ $schedule->priority }}" 
             data-location="{{ $schedule->warehouse_master_site_id }}">
            <div class="card card-asset priority-{{ $schedule->priority }} h-100" data-id="{{ $schedule->id }}">
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-start">
                        <h5 class="mb-0 card-title">{{ $schedule->request_subject }}</h5>
                        <span class="text-white badge badge-status-{{ $schedule->status }}">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </div>
                    <p class="mb-3 card-text text-muted">Lokasi: {{ $schedule->warehouseMasterSite->nama_lokasi }}</p>
                    
                    <!-- Progress Tracker -->
                    <div class="progress-tracker">
                        @php
                            $statuses = ['unassigned', 'open', 'waiting', 'resolved'];
                            $currentStatusIndex = array_search($schedule->status, $statuses);
                        @endphp

                        @foreach($statuses as $index => $status)
                            <div class="progress-step {{ $index < $currentStatusIndex ? 'completed' : ($index == $currentStatusIndex ? 'active' : '') }}">
                                <div class="progress-step-circle">
                                    @if($index < $currentStatusIndex)
                                        <i class="bi bi-check"></i>
                                    @elseif($index == $currentStatusIndex)
                                        @if($status == 'unassigned')
                                            <i class="bi bi-question"></i>
                                        @elseif($status == 'open')
                                            <i class="bi bi-person-check"></i>
                                        @elseif($status == 'waiting')
                                            <i class="bi bi-hourglass-split"></i>
                                        @else
                                            <i class="bi bi-check-all"></i>
                                        @endif
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="progress-step-text">{{ ucfirst($status) }}</span>
                            </div>
                        @endforeach
                        
                        <!-- Progress Lines -->
                        @for($i = 0; $i < 3; $i++)
                            <div class="progress-line {{ $i < $currentStatusIndex ? 'active' : '' }}" 
                                 style="width: 33%; left: {{ $i * 33 }}%;"></div>
                        @endfor
                    </div>
                    
                    <div class="mt-4">
                        <p class="card-text">
                            <small class="text-muted">
                                @if($schedule->status == 'resolved')
                                    Tanggal Selesai: {{ $schedule->arrival_completed_date ? date('d/m/Y', strtotime($schedule->arrival_completed_date)) : 'Belum selesai' }}
                                @else
                                    Tanggal Jadwal: {{ $schedule->arrival_date ? date('d/m/Y', strtotime($schedule->arrival_date)) : 'Belum dijadwalkan' }}
                                @endif
                            </small>
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge {{ $schedule->priority == 'high' ? 'bg-danger' : ($schedule->priority == 'medium' ? 'bg-warning' : 'bg-info') }}">
                                Prioritas {{ $schedule->priority == 'high' ? 'Tinggi' : ($schedule->priority == 'medium' ? 'Sedang' : 'Rendah') }}
                            </span>
                            <button class="btn btn-sm btn-outline-primary view-detail" 
                                    data-id="{{ $schedule->id }}"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#assetDetailModal">Lihat Detail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- No Results Message -->
    <div id="noResults" class="py-5 text-center d-none">
        <i class="mb-3 bi bi-search" style="font-size: 3rem;"></i>
        <h4>Tidak ada hasil yang ditemukan</h4>
        <p class="text-muted">Silakan coba dengan filter atau pencarian yang berbeda</p>
    </div>
</div>

<!-- Asset Detail Modal -->
<div class="modal fade" id="assetDetailModal" tabindex="-1" aria-labelledby="assetDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetDetailModalLabel">Detail Pemeriksaan Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="mb-4 col-md-12">
                        <!-- Progress Tracker in Modal -->
                        <div class="progress-tracker" id="modalProgressTracker">
                            <!-- Will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold">Subjek Permintaan:</label>
                            <p id="modalSubject"></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Deskripsi:</label>
                            <p id="modalDescription"></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Prioritas:</label>
                            <p id="modalPriority"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="fw-bold">Lokasi:</label>
                            <p id="modalLocation"></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tanggal Jadwal:</label>
                            <p id="modalArrivalDate"></p>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Tanggal Selesai:</label>
                            <p id="modalCompletedDate"></p>
                        </div>
                    </div>
                </div>

                <div class="mt-3 row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">Riwayat Status</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="statusHistory">
                                    <!-- Will be populated by JavaScript -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="updateStatusBtn">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Aset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="assetScheduleId" name="asset_check_schedule_id">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">Status Baru</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="unassigned">Unassigned</option>
                            <option value="open">Open</option>
                            <option value="waiting">Waiting</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    <div class="mb-3" id="completionDateGroup" style="display: none;">
                        <label for="completionDate" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="completionDate" name="arrival_completed_date">
                    </div>
                    <div class="mb-3">
                        <label for="statusNote" class="form-label">Catatan</label>
                        <textarea class="form-control" id="statusNote" name="note" rows="3"></textarea>
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
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Filter functionality
        function filterAssets() {
            const statusFilter = $('#statusFilter').val();
            const priorityFilter = $('#priorityFilter').val();
            const locationFilter = $('#locationFilter').val();
            const searchTerm = $('#searchInput').val().toLowerCase();
            
            let visible = 0;
            
            $('.asset-card').each(function() {
                const card = $(this);
                const status = card.data('status');
                const priority = card.data('priority');
                const location = card.data('location').toString();
                const title = card.find('.card-title').text().toLowerCase();
                
                const statusMatch = !statusFilter || status === statusFilter;
                const priorityMatch = !priorityFilter || priority === priorityFilter;
                const locationMatch = !locationFilter || location === locationFilter;
                const searchMatch = !searchTerm || title.includes(searchTerm);
                
                if (statusMatch && priorityMatch && locationMatch && searchMatch) {
                    card.removeClass('d-none');
                    visible++;
                } else {
                    card.addClass('d-none');
                }
            });
            
            if (visible === 0) {
                $('#noResults').removeClass('d-none');
            } else {
                $('#noResults').addClass('d-none');
            }
        }
        
        $('#searchButton').on('click', function() {
            filterAssets();
        });
        
        $('#searchInput').on('keyup', function(e) {
            if (e.key === 'Enter') {
                filterAssets();
            }
        });
        
        // Show asset details in modal
        $('.view-detail').on('click', function() {
            const assetId = $(this).data('id');
            
            // AJAX request to get asset details
            $.ajax({
                url: `/detail-audit/${assetId}`,
                method: 'GET',
                success: function(response) {
                    // Fill modal with asset details
                    const data = response.data;

                    // Fill modal with asset details
                    $('#modalSubject').text(data.request_subject);
                    $('#modalDescription').text(data.description || 'Tidak ada deskripsi');

                    // Format priority display
                    let priorityText, priorityBadge;
                    if (data.priority === 'high') {
                        priorityText = 'Tinggi';
                        priorityBadge = 'bg-danger';
                    } else if (data.priority === 'medium') {
                        priorityText = 'Sedang';
                        priorityBadge = 'bg-warning';
                    } else {
                        priorityText = 'Rendah';
                        priorityBadge = 'bg-info';
                    }

                    $('#modalPriority').html(`<span class="badge ${priorityBadge}">Prioritas ${priorityText}</span>`);
                    $('#modalLocation').text(data.warehouse_master_site.nama_lokasi);

                    const arrivalDate = data.arrival_date ? new Date(data.arrival_date).toLocaleDateString('id-ID') : 'Belum dijadwalkan';
                    const completedDate = data.arrival_completed_date ? new Date(data.arrival_completed_date).toLocaleDateString('id-ID') : 'Belum selesai';

                    $('#modalArrivalDate').text(arrivalDate);
                    $('#modalCompletedDate').text(completedDate);

                    // Populate progress tracker in modal
                    const statuses = ['unassigned', 'open', 'waiting', 'resolved'];
                    const currentStatusIndex = statuses.indexOf(data.status);

                    let progressTrackerHtml = '';
                    statuses.forEach((status, index) => {
                        let stepClass = '';
                        let stepIcon = '';

                        if (index < currentStatusIndex) {
                            stepClass = 'completed';
                            stepIcon = '<i class="bi bi-check"></i>';
                        } else if (index === currentStatusIndex) {
                            stepClass = 'active';
                            if (status === 'unassigned') {
                                stepIcon = '<i class="bi bi-question"></i>';
                            } else if (status === 'open') {
                                stepIcon = '<i class="bi bi-person-check"></i>';
                            } else if (status === 'waiting') {
                                stepIcon = '<i class="bi bi-hourglass-split"></i>';
                            } else {
                                stepIcon = '<i class="bi bi-check-all"></i>';
                            }
                        } else {
                            stepIcon = (index + 1).toString();
                        }

                        progressTrackerHtml += `
                            <div class="progress-step ${stepClass}">
                                <div class="progress-step-circle">
                                    ${stepIcon}
                                </div>
                                <span class="progress-step-text">${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                            </div>
                        `;
                    });

                    for (let i = 0; i < 3; i++) {
                        const lineClass = i < currentStatusIndex ? 'active' : '';
                        progressTrackerHtml += `<div class="progress-line ${lineClass}" style="width: 33%; left: ${i * 33}%;"></div>`;
                    }

                    $('#modalProgressTracker').html(progressTrackerHtml);

                    // Populate status history if available (optional, assuming you have it)
                    if (data.status_history && data.status_history.length > 0) {
                        let historyHtml = '';
                        data.status_history.forEach(history => {
                            historyHtml += `
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>${history.status.charAt(0).toUpperCase() + history.status.slice(1)}</span>
                                        <small class="text-muted">${new Date(history.created_at).toLocaleString('id-ID')}</small>
                                    </div>
                                    ${history.note ? `<small class="text-muted">${history.note}</small>` : ''}
                                </li>
                            `;
                        });
                        $('#statusHistory').html(historyHtml);
                    } else {
                        $('#statusHistory').html('<li class="text-center list-group-item">Tidak ada riwayat status</li>');
                    }
                    
                    // Set asset ID for update status form
                    $('#assetScheduleId').val(response.id);
                    $('#newStatus').val(response.status);
                },
                error: function(error) {
                    console.error('Error fetching asset details:', error);
                }
            });
        });
        
        // Update Status Button Click
        $('#updateStatusBtn').on('click', function() {
            $('#assetDetailModal').modal('hide');
            $('#updateStatusModal').modal('show');
        });

        // Submit update status form via AJAX
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();

            const assetId = $('#assetScheduleId').val(); // Get asset ID
            const status = $('#newStatus').val();
            const note = $('#statusNote').val();
            const completedDate = $('#completionDate').val();

            $.ajax({
                url: `/detail-audit/${assetId}/update-status`,
                type: 'PUT',
                data: {
                    _token: $('input[name="_token"]').val(), // CSRF token
                    status: status,
                    note: note,
                    arrival_completed_date: completedDate
                },
                success: function(response) {
                    $('#updateStatusModal').modal('hide');
                    alert('Status berhasil diperbarui.');

                    // Optional: reload page or update card UI
                    location.reload();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        let errorMessage = 'Validasi gagal:\n';
                        for (const key in errors) {
                            errorMessage += `- ${errors[key]}\n`;
                        }
                        alert(errorMessage);
                    } else {
                        alert('Terjadi kesalahan saat memperbarui status.');
                    }
                }
            });
        });

        
        // Show/hide completion date based on status selection
        $('#newStatus').on('change', function() {
            if ($(this).val() === 'resolved') {
                $('#completionDateGroup').show();
            } else {
                $('#completionDateGroup').hide();
            }
        });
    });
</script>
@endsection