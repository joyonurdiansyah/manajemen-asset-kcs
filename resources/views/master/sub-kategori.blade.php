@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Main Container for both Cards and Tables -->
    <div id="main-container" style="height: 70vh;">
        <!-- Development Notice Section -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="shadow card">
                    <div class="text-center card-body">
                        <img src="{{ asset('images/flying.png') }}" alt="Development" class="mt-4 mb-4 img-fluid" style="max-height: 200px;">
                        <h2 class="card-title">Halaman Sedang Dalam Pengembangan</h2>
                        <p class="card-text">
                            Mohon maaf, halaman ini sedang dalam proses pengembangan oleh developer.
                            Silahkan kembali lagi nanti.
                        </p>
                        <div class="mt-4">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Any additional JavaScript can be added here
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Development page loaded');
    });
</script>
@endsection