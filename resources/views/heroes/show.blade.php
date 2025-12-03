@extends('layouts.app')

@section('title', $hero->hero_name . ' - MLBB Heroes')

@section('content')
<div class="container py-5">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('heroes.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Heroes
        </a>
    </div>

    <!-- Hero Header -->
    <div class="card shadow-lg mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ \App\Helpers\ImageHelper::getProxiedImage($hero->hero_image, $hero->hero_name) }}" 
                         alt="{{ $hero->hero_name }}"
                         class="img-fluid rounded shadow"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getFallbackImage($hero->hero_name, 300) }}'">
                </div>
                <div class="col-md-9">
                    <h1 class="display-3 fw-bold mb-3">{{ $hero->hero_name }}</h1>
                    <div class="mb-3">
                        <span class="badge bg-primary fs-5 me-2">
                            <i class="bi bi-palette"></i> {{ $hero->total_skins }} Total Skins
                        </span>
                    </div>
                    @if($hero->url)
                    <a href="{{ $hero->url }}" 
                       target="_blank" 
                       class="btn btn-info btn-lg">
                        <i class="bi bi-link-45deg"></i> Lihat di Wiki
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Skins Section -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">
                <i class="bi bi-images"></i> Skin Collection
            </h2>
        </div>
        <div class="card-body">
            <div class="row g-4">
                @forelse($hero->skins as $index => $skin)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm skin-card">
                        <div class="position-relative">
                            <img src="{{ \App\Helpers\ImageHelper::getProxiedImage($skin->skin_image, $skin->skin_name) }}" 
                                 class="card-img-top" 
                                 alt="{{ $skin->skin_name }}"
                                 style="height: 250px; object-fit: cover;"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getFallbackImage($skin->skin_name, 250) }}'">
                            <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-dark">
                                    #{{ $index + 1 }}
                                </span>
                            </div>
                            <div class="position-absolute top-0 end-0 m-2">
                                @php
                                    $categoryClass = match($skin->category) {
                                        'Other' => 'bg-secondary',
                                        'Common' => 'bg-success',
                                        'Exquisite' => 'bg-info',
                                        'Exceptional' => 'bg-primary',
                                        'Supreme' => 'bg-warning text-dark',
                                        'Deluxe' => 'bg-danger',
                                        'Legend' => 'bg-dark',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $categoryClass }}">
                                    {{ $skin->category }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $skin->skin_name }}</h5>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle"></i> Belum ada skin tersedia untuk hero ini.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
.skin-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.skin-card:hover {
    transform: scale(1.05);
    box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important;
}

.skin-card img {
    transition: transform 0.3s ease;
}

.skin-card:hover img {
    transform: scale(1.1);
}
</style>
@endsection
