@extends('layouts.app')

@section('title', 'MLBB Heroes')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4 fw-bold text-center mb-4">Mobile Legends Heroes</h1>
            
            <!-- Search Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="{{ route('heroes.search') }}" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-lg" 
                                   placeholder="Cari hero..." 
                                   value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(isset($search))
                <div class="alert alert-info">
                    Hasil pencarian untuk: <strong>{{ $search }}</strong> - {{ $heroes->total() }} hero ditemukan
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        @forelse($heroes as $hero)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="position-relative">
                    <img src="{{ \App\Helpers\ImageHelper::getProxiedImage($hero->hero_image, $hero->hero_name) }}" 
                         class="card-img-top" 
                         alt="{{ $hero->hero_name }}"
                         style="height: 200px; object-fit: cover;"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getFallbackImage($hero->hero_name, 200) }}'">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary rounded-pill">
                            {{ $hero->total_skins }} Skins
                        </span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-center">{{ $hero->hero_name }}</h5>
                    <div class="mt-auto">
                        <a href="{{ route('heroes.show', $hero->id) }}" 
                           class="btn btn-primary w-100">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle"></i> 
                Tidak ada hero ditemukan.
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $heroes->links() }}
    </div>
</div>

<style>
.hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
}
</style>
@endsection
