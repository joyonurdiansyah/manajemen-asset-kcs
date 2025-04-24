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
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-success text-white">
                                                <i class="bi bi-check-circle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>320</h6>
                                                <span class="text-success small pt-1 fw-bold">Stabil</span>
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
                                                class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-danger text-white">
                                                <i class="bi bi-x-circle"></i>
                                            </div>
                                            <div class="ps-3">
                                                <h6>45</h6>
                                                <span class="text-danger small pt-1 fw-bold">Butuh Tindakan</span>
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
                                                <span class="text-warning small pt-1 fw-bold">Segera Cek</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Reports -->
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Data Keseluruhan Barang</h5>

                                        <!-- Bar Chart -->
                                        <canvas id="barChart" style="max-height: 400px;"></canvas>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", () => {
                                                new Chart(document.querySelector('#barChart'), {
                                                    type: 'bar',
                                                    data: {
                                                        labels: ['Monitor', 'CPU', 'Printer', 'Switch', 'CCTV', 'Access Point', 'Print Server'],
                                                        datasets: [{
                                                            data: [25, 18, 12, 5, 8, 6, 3],
                                                            backgroundColor: [
                                                                'rgba(54, 162, 235, 0.2)',
                                                                'rgba(255, 206, 86, 0.2)',
                                                                'rgba(75, 192, 192, 0.2)',
                                                                'rgba(153, 102, 255, 0.2)',
                                                                'rgba(255, 159, 64, 0.2)',
                                                                'rgba(255, 99, 132, 0.2)',
                                                                'rgba(201, 203, 207, 0.2)'
                                                            ],
                                                            borderColor: [
                                                                'rgba(54, 162, 235, 1)',
                                                                'rgba(255, 206, 86, 1)',
                                                                'rgba(75, 192, 192, 1)',
                                                                'rgba(153, 102, 255, 1)',
                                                                'rgba(255, 159, 64, 1)',
                                                                'rgba(255, 99, 132, 1)',
                                                                'rgba(201, 203, 207, 1)'
                                                            ],
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
                                                            }
                                                        }
                                                    }
                                                });
                                            });
                                        </script>

                                        <!-- End Bar CHart -->

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

                        <!-- Data Berdasarkan Department -->
                        {{-- KCS --}}
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Lokasi Asset</h5>

                                    <!-- Doughnut Chart -->
                                    <canvas id="doughnutChart" style="max-height: 400px;"></canvas>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", () => {
                                            new Chart(document.querySelector('#doughnutChart'), {
                                                type: 'doughnut',
                                                data: {
                                                    labels: [
                                                        'KCS',
                                                        'TKS',
                                                        'MKA'
                                                    ],
                                                    datasets: [{
                                                        label: 'My First Dataset',
                                                        data: [300, 50, 100],
                                                        backgroundColor: [
                                                            'rgb(255, 99, 132)',
                                                            'rgb(54, 162, 235)',
                                                            'rgb(255, 205, 86)'
                                                        ],
                                                        hoverOffset: 4
                                                    }]
                                                }
                                            });
                                        });
                                    </script>
                                    <!-- End Doughnut CHart -->

                                </div>
                            </div>
                        </div> <!-- End KCS -->



                    </div><!-- End Right side columns -->

                </div>
            </section>
        @endsection
