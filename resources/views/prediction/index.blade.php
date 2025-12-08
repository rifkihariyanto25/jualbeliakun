@extends('layouts.app')

@section('title', 'Account Price Prediction')

@section('content')
<div class="container-fluid px-3 py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <!-- Hero Section with Glassmorphism -->
            <div class="hero-section text-center mb-5">
                <div class="floating-icon mb-4">
                    <i class="bi bi-calculator"></i>
                </div>
                <h1 class="display-3 fw-bold mb-3 gradient-text">
                    Prediksi Harga Akun MLBB
                </h1>
                <p class="lead mb-4 text-muted">Didukung oleh Teknologi AI & Machine Learning</p>
                <div class="info-badge">
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    <span>Masukkan detail akun dan biarkan AI kami memprediksi nilai pasar secara instan</span>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left Column - Form -->
                <div class="col-lg-7">
                    <div class="glass-card">
                        <div class="card-header-custom">
                            <h4 class="mb-0 fw-bold">
                                <i class="bi bi-pencil-square me-2"></i>Detail Akun
                            </h4>
                        </div>
                        
                        <form id="predictionForm" class="p-4">
                            @csrf
                            
                            <!-- Rank Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi bi-trophy-fill text-warning"></i>
                                    <span>Informasi Rank</span>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label-modern">Kategori Rank Tertinggi</label>
                                        <select class="form-control-modern" id="rankCategory" name="rankCategory" required>
                                            <option value="">Pilih Rank</option>
                                            <option value="Warrior">⚔️ Warrior</option>
                                            <option value="Elite">🛡️ Elite</option>
                                            <option value="Master">🎖️ Master</option>
                                            <option value="Grandmaster">🏅 Grandmaster</option>
                                            <option value="Epic">💎 Epic</option>
                                            <option value="Legend">👑 Legend</option>
                                            <option value="Mythic">🌟 Mythic</option>
                                            <option value="Mythical Honor">⭐ Mythical Honor</option>
                                            <option value="Mythical Glory">✨ Mythical Glory</option>
                                            <option value="Immortal">🔥 Immortal</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-5">
                                        <label class="form-label-modern">Tier</label>
                                        <select class="form-control-modern" id="rankTier" name="rankTier">
                                            <option value="">-</option>
                                            <option value="V">V</option>
                                            <option value="IV">IV</option>
                                            <option value="III">III</option>
                                            <option value="II">II</option>
                                            <option value="I">I</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-12" id="starContainer" style="display: none;">
                                        <label class="form-label-modern">Jumlah Bintang</label>
                                        <input type="number" class="form-control-modern" id="stars" name="stars" min="0" max="100" placeholder="Contoh: 50">
                                    </div>
                                </div>
                            </div>

                            <!-- Collection Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi bi-gem text-primary"></i>
                                    <span>Level Koleksi</span>
                                </div>
                                
                                <select class="form-control-modern" id="koleksi" name="koleksi">
                                    <option value="">Pilih Level Koleksi (Opsional)</option>
                                    <optgroup label="🌱 Kolektor Pemula">
                                        <option value="Kolektor Pemula I">Kolektor Pemula I</option>
                                        <option value="Kolektor Pemula II">Kolektor Pemula II</option>
                                        <option value="Kolektor Pemula III">Kolektor Pemula III</option>
                                    </optgroup>
                                    <optgroup label="📚 Kolektor Ahli">
                                        <option value="Kolektor Ahli I">Kolektor Ahli I</option>
                                        <option value="Kolektor Ahli II">Kolektor Ahli II</option>
                                        <option value="Kolektor Ahli III">Kolektor Ahli III</option>
                                        <option value="Kolektor Ahli IV">Kolektor Ahli IV</option>
                                        <option value="Kolektor Ahli V">Kolektor Ahli V</option>
                                    </optgroup>
                                    <optgroup label="🎯 Kolektor Senior">
                                        <option value="Kolektor Senior I">Kolektor Senior I</option>
                                        <option value="Kolektor Senior II">Kolektor Senior II</option>
                                        <option value="Kolektor Senior III">Kolektor Senior III</option>
                                        <option value="Kolektor Senior IV">Kolektor Senior IV</option>
                                        <option value="Kolektor Senior V">Kolektor Senior V</option>
                                    </optgroup>
                                    <optgroup label="⚡ Kolektor Mahir">
                                        <option value="Kolektor Mahir I">Kolektor Mahir I</option>
                                        <option value="Kolektor Mahir II">Kolektor Mahir II</option>
                                        <option value="Kolektor Mahir III">Kolektor Mahir III</option>
                                        <option value="Kolektor Mahir IV">Kolektor Mahir IV</option>
                                        <option value="Kolektor Mahir V">Kolektor Mahir V</option>
                                    </optgroup>
                                    <optgroup label="🏆 Kolektor Terhormat">
                                        <option value="Kolektor Terhormat I">Kolektor Terhormat I</option>
                                        <option value="Kolektor Terhormat II">Kolektor Terhormat II</option>
                                        <option value="Kolektor Terhormat III">Kolektor Terhormat III</option>
                                        <option value="Kolektor Terhormat IV">Kolektor Terhormat IV</option>
                                        <option value="Kolektor Terhormat V">Kolektor Terhormat V</option>
                                    </optgroup>
                                    <optgroup label="💫 Kolektor Ternama">
                                        <option value="Kolektor Ternama I">Kolektor Ternama I</option>
                                        <option value="Kolektor Ternama II">Kolektor Ternama II</option>
                                        <option value="Kolektor Ternama III">Kolektor Ternama III</option>
                                        <option value="Kolektor Ternama IV">Kolektor Ternama IV</option>
                                        <option value="Kolektor Ternama V">Kolektor Ternama V</option>
                                    </optgroup>
                                    <optgroup label="👔 Kolektor Juragan">
                                        <option value="Kolektor Juragan">Kolektor Juragan</option>
                                        <option value="Kolektor Juragan I">Kolektor Juragan I</option>
                                        <option value="Kolektor Juragan II">Kolektor Juragan II</option>
                                        <option value="Kolektor Juragan III">Kolektor Juragan III</option>
                                        <option value="Kolektor Juragan IV">Kolektor Juragan IV</option>
                                        <option value="Kolektor Juragan V">Kolektor Juragan V</option>
                                    </optgroup>
                                    <optgroup label="👑 Kolektor Sultan">
                                        <option value="Kolektor Sultan">Kolektor Sultan</option>
                                    </optgroup>
                                </select>
                            </div>

                            <!-- Stats Section -->
                            <div class="form-section">
                                <div class="section-title">
                                    <i class="bi bi-bar-chart-fill text-info"></i>
                                    <span>Statistik Akun</span>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label-modern">
                                            <i class="bi bi-palette-fill text-danger me-2"></i>Total Skin
                                        </label>
                                        <input type="number" class="form-control-modern" id="jumlah_skin" name="jumlah_skin" min="0" value="0" placeholder="Contoh: 300" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label-modern">
                                            <i class="bi bi-graph-up-arrow text-success me-2"></i>Winrate (%)
                                        </label>
                                        <input type="number" class="form-control-modern" id="winrate" name="winrate" min="0" max="100" step="0.01" value="50.0" placeholder="Contoh: 55.5" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label-modern">
                                            <i class="bi bi-joystick text-warning me-2"></i>Total Match
                                        </label>
                                        <input type="number" class="form-control-modern" id="total_match" name="total_match" min="0" value="0" placeholder="Contoh: 3000" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="btn-gradient w-100" id="predictBtn">
                                <i class="bi bi-lightning-charge-fill me-2"></i>
                                <span>Prediksi Harga Sekarang</span>
                                <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Column - Results & Info -->
                <div class="col-lg-5">
                    <!-- Loading State -->
                    <div id="loadingSpinner" class="glass-card text-center p-5" style="display: none;">
                        <div class="loading-animation mb-3">
                            <div class="spinner"></div>
                        </div>
                        <h5 class="mb-2">Menganalisis Data...</h5>
                        <p class="text-muted small mb-0">AI sedang menghitung nilai akun Anda</p>
                    </div>

                    <!-- Result Card -->
                    <div id="resultCard" class="result-card" style="display: none;">
                        <div class="result-glow"></div>
                        <div class="result-content">
                            <div class="result-icon mb-3">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h5 class="text-white-50 mb-2">Estimasi Harga</h5>
                            <h1 class="result-price mb-3" id="predictedPrice">Rp 0</h1>
                            
                            <div class="result-meta mb-4">
                                <span><i class="bi bi-cpu me-1"></i>Random Forest AI</span>
                                <span><i class="bi bi-graph-up me-1"></i>85.29% Akurasi</span>
                            </div>
                            
                            <div class="score-grid">
                                <div class="score-item">
                                    <i class="bi bi-trophy-fill"></i>
                                    <div>
                                        <small>Skor Rank</small>
                                        <strong id="scoreRank">-</strong>
                                    </div>
                                </div>
                                <div class="score-item">
                                    <i class="bi bi-gem"></i>
                                    <div>
                                        <small>Skor Koleksi</small>
                                        <strong id="scoreKoleksi">-</strong>
                                    </div>
                                </div>
                                <div class="score-item">
                                    <i class="bi bi-graph-up-arrow"></i>
                                    <div>
                                        <small>Skor Winrate</small>
                                        <strong id="scoreWinrate">-</strong>
                                    </div>
                                </div>
                                <div class="score-item">
                                    <i class="bi bi-joystick"></i>
                                    <div>
                                        <small>Skor Match</small>
                                        <strong id="scoreMatch">-</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <div id="errorAlert" class="error-card" style="display: none;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Prediksi Gagal</strong>
                            <p id="errorMessage"></p>
                        </div>
                    </div>

                    <!-- Model Info Card -->
                    <div class="glass-card mt-4">
                        <div class="info-header">
                            <i class="bi bi-info-circle"></i>
                            <h6>Informasi Model</h6>
                        </div>
                        <div class="info-list">
                            <div class="info-item">
                                <i class="bi bi-cpu-fill text-primary"></i>
                                <span>Random Forest Regressor</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-diagram-3-fill text-success"></i>
                                <span>9 Fitur Analisis</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-graph-up text-warning"></i>
                                <span>85.29% Akurasi (R²)</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-palette-fill text-danger"></i>
                                <span>68.98% Dampak Skin</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-database-fill text-info"></i>
                                <span>755 Data Training</span>
                            </div>
                            <div class="info-item">
                                <i class="bi bi-speedometer2 text-success"></i>
                                <span>Rp 149K Rata-rata Error</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --glass-bg: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
}

body {
    background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
    min-height: 100vh;
}

/* Floating Icon Animation */
.floating-icon {
    font-size: 4rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: gradient-shift 3s ease infinite;
    background-size: 200% 200%;
}

@keyframes gradient-shift {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

/* Info Badge */
.info-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 50px;
    backdrop-filter: blur(10px);
    color: rgba(255, 255, 255, 0.9);
}

/* Glass Card */
.glass-card {
    background: var(--glass-bg);
    border: 1px solid var(--glass-border);
    border-radius: 24px;
    backdrop-filter: blur(20px);
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.glass-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 48px rgba(0, 0, 0, 0.2);
}

.card-header-custom {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
    border-bottom: 1px solid var(--glass-border);
    padding: 1.5rem;
}

.card-header-custom h4 {
    color: white;
    margin: 0;
}

/* Form Sections */
.form-section {
    margin-bottom: 2rem;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    font-size: 1.1rem;
    color: white;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
}

.section-title i {
    font-size: 1.3rem;
}

/* Modern Form Controls */
.form-label-modern {
    color: rgba(255, 255, 255, 0.8);
    font-weight: 500;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control-modern {
    width: 100%;
    padding: 0.875rem 1.25rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control-modern:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.08);
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.form-control-modern option {
    background: #1a1a2e;
    color: white;
}

/* Gradient Button */
.btn-gradient {
    width: 100%;
    padding: 1.25rem;
    background: var(--primary-gradient);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 2rem;
}

.btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.6);
}

.btn-gradient:active {
    transform: translateY(-1px);
}

/* Loading Animation */
.loading-animation {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.spinner {
    width: 100%;
    height: 100%;
    border: 4px solid rgba(102, 126, 234, 0.2);
    border-top-color: #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Result Card */
.result-card {
    position: relative;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 3rem 2rem;
    overflow: hidden;
}

.result-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: glow-rotate 8s linear infinite;
}

@keyframes glow-rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.result-content {
    position: relative;
    z-index: 1;
}

.result-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
}

.result-price {
    font-size: 3.5rem;
    font-weight: 800;
    color: white;
    text-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
}

.result-meta {
    display: flex;
    justify-content: center;
    gap: 2rem;
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
}

.score-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.score-item {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    backdrop-filter: blur(10px);
}

.score-item i {
    font-size: 1.5rem;
    color: rgba(255, 255, 255, 0.8);
}

.score-item small {
    display: block;
    color: rgba(255, 255, 255, 0.6);
    font-size: 0.75rem;
}

.score-item strong {
    display: block;
    color: white;
    font-size: 1.25rem;
}

/* Error Card */
.error-card {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    color: white;
}

.error-card i {
    font-size: 2rem;
    flex-shrink: 0;
}

.error-card strong {
    display: block;
    margin-bottom: 0.25rem;
}

.error-card p {
    margin: 0;
    opacity: 0.9;
}

/* Info Card */
.info-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.info-header i {
    font-size: 1.5rem;
    color: #667eea;
}

.info-header h6 {
    margin: 0;
    color: white;
    font-weight: 600;
}

.info-list {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem 0;
    color: rgba(255, 255, 255, 0.8);
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    font-size: 1.25rem;
}

/* Responsive */
@media (max-width: 991px) {
    .gradient-text {
        font-size: 2.5rem;
    }
    
    .result-price {
        font-size: 2.5rem;
    }
}
</style>

<script>
// Show/hide star input based on rank selection
document.getElementById('rankCategory').addEventListener('change', function() {
    const starContainer = document.getElementById('starContainer');
    const rankTier = document.getElementById('rankTier');
    const selectedRank = this.value;
    
    if (selectedRank === 'Mythic' || selectedRank === 'Mythical Honor' || 
        selectedRank === 'Mythical Glory' || selectedRank === 'Immortal') {
        starContainer.style.display = 'block';
        rankTier.disabled = true;
        rankTier.value = '';
    } else {
        starContainer.style.display = 'none';
        rankTier.disabled = false;
        document.getElementById('stars').value = '';
    }
});

document.getElementById('predictionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    document.getElementById('resultCard').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';
    document.getElementById('loadingSpinner').style.display = 'block';
    
    const rankCategory = document.getElementById('rankCategory').value;
    const rankTier = document.getElementById('rankTier').value;
    const stars = document.getElementById('stars').value;
    
    let rankString = rankCategory;
    if (rankTier) {
        rankString += ' ' + rankTier;
    } else if (stars) {
        rankString += ' (' + stars + ' Bintang)';
    }
    
    const data = {
        rank: rankString,
        koleksi: document.getElementById('koleksi').value,
        jumlah_skin: document.getElementById('jumlah_skin').value,
        winrate: document.getElementById('winrate').value,
        total_match: document.getElementById('total_match').value
    };
    
    try {
        const response = await fetch('{{ route("prediction.predict") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        document.getElementById('loadingSpinner').style.display = 'none';
        
        if (result.success) {
            document.getElementById('predictedPrice').textContent = result.formatted_price;
            
            if (result.calculated_scores) {
                document.getElementById('scoreRank').textContent = result.calculated_scores.score_rank;
                document.getElementById('scoreKoleksi').textContent = result.calculated_scores.score_koleksi;
                document.getElementById('scoreWinrate').textContent = result.calculated_scores.score_winrate;
                document.getElementById('scoreMatch').textContent = result.calculated_scores.score_total_match;
            }
            
            document.getElementById('resultCard').style.display = 'block';
            document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            document.getElementById('errorMessage').textContent = result.error || 'Prediction failed';
            document.getElementById('errorAlert').style.display = 'flex';
        }
    } catch (error) {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorMessage').textContent = 'Network error: ' + error.message;
        document.getElementById('errorAlert').style.display = 'flex';
    }
});
</script>
@endsection