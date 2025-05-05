@extends('layouts.app')

@section('content')
    <div class="container">

        <section class="text-center section error-404 min-vh-100 d-flex flex-column align-items-center justify-content-center">
            <h1 style="font-size: 6rem; font-weight: bold;">404</h1>
            <h2 class="mb-4">Halaman yang kamu cari sedang dalam development, bro.</h2>
            <a class="mb-3 btn btn-primary" href="{{ route('dashboard') }}">← Kembali ke Halaman Awal</a>
            <img src="{{ asset('assets/img/not-found.svg') }}" class="py-4 img-fluid" style="max-width: 500px;" alt="Page Not Found">
            <div class="mt-3 credits">
                Designed by <a href="#">🧡 Bangjoy</a>
            </div>
        </section>

    </div>
@endsection
