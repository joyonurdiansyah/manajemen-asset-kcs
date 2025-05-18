@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
    </div>
    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Kondisi Barang Oke -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Kondisi Barang <span>| Oke</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="text-white card-icon rounded-circle d-flex align-items-center justify-content-center bg-success">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>320</h6>
                                        <span class="pt-1 text-success small fw-bold">Stabil</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Barang Rusak -->
                    <div class="col-xxl-4 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Kondisi Barang <span>| Rusak</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="text-white card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger">
                                        <i class="bi bi-x-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>45</h6>
                                        <span class="pt-1 text-danger small fw-bold">Butuh Tindakan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Barang Perlu Maintenance -->
                    <div class="col-xxl-4 col-xl-12">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Kondisi Barang <span>| Perlu Maintenance</span></h5>

                                <div class="d-flex align-items-center">
                                    <div
                                        class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark">
                                        <i class="bi bi-tools"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>72</h6>
                                        <span class="pt-1 text-warning small fw-bold">Segera Cek</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Reports -->
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Data Keseluruhan Berdasarkan Brand</h5>
                                
                                <!-- Bar Chart -->
                                <canvas id="barChart" style="max-height: 400px;"></canvas>
                                
                            </div>
                        </div>
                    </div>


                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            <div class="col-lg-4">

                <div class="card">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>
                            <li><a class="dropdown-item" href="#">Today</a></li>
                            <li><a class="dropdown-item" href="#">This Month</a></li>
                            <li><a class="dropdown-item" href="#">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Aktivitas Aset IT <span>| Hari Ini</span></h5>

                        <div class="activity">

                            <div class="activity-item d-flex">
                                <div class="activite-label">10 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Wahyu telah menambahkan barang <span
                                        class="fw-bold text-primary">Handheld</span>
                                </div>
                            </div>

                            <div class="activity-item d-flex">
                                <div class="activite-label">25 min</div>
                                <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                <div class="activity-content">
                                    Joyo telah menambahkan <span class="fw-bold text-primary">Monitor</span>
                                </div>
                            </div>

                            <div class="activity-item d-flex">
                                <div class="activite-label">1 hr</div>
                                <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                                <div class="activity-content">
                                    Dedy telah menambahkan jadwal <span class="fw-bold text-warning">pengiriman ke
                                        gudang kering</span>
                                </div>
                            </div>

                            <div class="activity-item d-flex">
                                <div class="activite-label">2 hrs</div>
                                <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
                                <div class="activity-content">
                                    Andi memperbarui status <span class="fw-bold text-info">laptop rusak menjadi
                                        "Maintenance"</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div><!-- End Aktivitas Aset IT -->

                <!-- Pie Chart - Lokasi Aset -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Lokasi Asset</h5>
                            <canvas id="pieChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>

            </div><!-- End Right side columns -->

        </div>
    </section>
@endsection
@section('scripts')
<script>
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    let barChart;
    let pieChart;
    
    // Function to initialize or update bar chart
    function updateBarChart(labels, data, backgroundColors, borderColors) {
        const ctx = document.getElementById('barChart').getContext('2d');
        
        if (barChart) {
            // Update existing chart
            barChart.data.labels = labels;
            barChart.data.datasets[0].data = data;
            barChart.data.datasets[0].backgroundColor = backgroundColors;
            barChart.data.datasets[0].borderColor = borderColors;
            barChart.update();
        } else {
            // Create new chart
            barChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: borderColors,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItem) {
                                    return tooltipItem[0].label;
                                },
                                label: function(context) {
                                    return `Jumlah: ${context.raw}`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    // Function to initialize or update pie chart
    function updatePieChart(labels, data, percentages, backgroundColors) {
        const ctx = document.getElementById('pieChart').getContext('2d');
        
        const labelsWithPercentages = labels.map((label, index) => {
            return `${label} (${percentages[index]}%)`;
        });
        
        if (pieChart) {
            pieChart.data.labels = labelsWithPercentages;
            pieChart.data.datasets[0].data = data;
            pieChart.data.datasets[0].backgroundColor = backgroundColors;
            pieChart.update();
        } else {
            // Create new chart
            pieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labelsWithPercentages,
                    datasets: [{
                        data: data,
                        backgroundColor: backgroundColors,
                        hoverOffset: 4
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const percentage = percentages[context.dataIndex];
                                    return `${label.split(' (')[0]}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1000
                    },
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }
    

    function fetchBrandDataAndUpdate() {
        $.ajax({
            url: '{{ route("brand.data") }}',
            method: 'GET',
            success: function(response) {
                updateBarChart(
                    response.labels, 
                    response.data, 
                    response.backgroundColors, 
                    response.borderColors
                );
                console.log('Brand chart updated at: ' + new Date().toLocaleTimeString());
            },
            error: function(error) {
                console.error('Error fetching brand data:', error);
            }
        });
    }


    function fetchLocationDataAndUpdate() {
        $.ajax({
            url: '{{ route("location.data") }}',
            method: 'GET',
            success: function(response) {
                updatePieChart(
                    response.labels,
                    response.data,
                    response.percentages,
                    response.backgroundColors
                );
                console.log('Location chart updated at: ' + new Date().toLocaleTimeString());
            },
            error: function(error) {
                console.error('Error fetching location data:', error);
            }
        });
    }
    

    $(document).ready(function() {
        fetchBrandDataAndUpdate();
        fetchLocationDataAndUpdate();
        
        setInterval(function() {
            fetchBrandDataAndUpdate();
            fetchLocationDataAndUpdate();
        }, 7000);
    });
</script>
@endsection