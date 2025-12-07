@extends('layouts.app')

@section('title', 'Account Price Prediction')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-gradient mb-3">
                    <i class="bi bi-calculator text-warning"></i> Account Price Predictor
                </h1>
                <p class="lead text-muted">Predict your MLBB account price using AI/ML</p>
                <div class="alert alert-info mt-3">
                    <i class="bi bi-lightbulb-fill"></i> <strong>How it works:</strong> 
                    Simply enter your account details below. Scores will be automatically calculated based on your input!
                </div>
            </div>

            <!-- Prediction Form Card -->
            <div class="card bg-dark border-secondary shadow-lg">
                <div class="card-body p-4">
                    <form id="predictionForm">
                        @csrf
                        
                        <div class="row g-3">
                            <!-- Rank Tertinggi -->
                            <div class="col-md-12">
                                <label for="rank" class="form-label fw-bold">
                                    <i class="bi bi-trophy-fill text-warning"></i> Rank Tertinggi
                                </label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" 
                                       id="rank" name="rank" placeholder="e.g., Legend I, Mythic (19 Bintang), Epic III" required>
                                <small class="text-muted">Your highest rank achieved (Score will be auto-calculated)</small>
                            </div>

                            <!-- Koleksi -->
                            <div class="col-md-12">
                                <label for="koleksi" class="form-label fw-bold">
                                    <i class="bi bi-gem text-primary"></i> Koleksi Level
                                </label>
                                <input type="text" class="form-control bg-dark text-white border-secondary" 
                                       id="koleksi" name="koleksi" placeholder="e.g., Kolektor Ternama IV, Kolektor Juragan II">
                                <small class="text-muted">Collector level (Score will be auto-calculated)</small>
                            </div>

                            <!-- Jumlah Skin -->
                            <div class="col-md-12">
                                <label for="jumlah_skin" class="form-label fw-bold">
                                    <i class="bi bi-palette-fill text-danger"></i> Jumlah Skin
                                </label>
                                <input type="number" class="form-control bg-dark text-white border-secondary" 
                                       id="jumlah_skin" name="jumlah_skin" min="0" value="0" required>
                                <small class="text-muted">Total skins owned</small>
                            </div>

                            <!-- Winrate -->
                            <div class="col-md-6">
                                <label for="winrate" class="form-label fw-bold">
                                    <i class="bi bi-graph-up-arrow text-success"></i> Winrate (%)
                                </label>
                                <input type="number" class="form-control bg-dark text-white border-secondary" 
                                       id="winrate" name="winrate" min="0" max="100" step="0.01" value="50.0" required>
                                <small class="text-muted">Overall winrate percentage (Score auto-calculated)</small>
                            </div>

                            <!-- Total Match -->
                            <div class="col-md-6">
                                <label for="total_match" class="form-label fw-bold">
                                    <i class="bi bi-joystick text-info"></i> Total Match
                                </label>
                                <input type="number" class="form-control bg-dark text-white border-secondary" 
                                       id="total_match" name="total_match" min="0" value="0" required>
                                <small class="text-muted">Total matches played (Score auto-calculated)</small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-warning btn-lg fw-bold" id="predictBtn">
                                <i class="bi bi-lightning-charge-fill"></i> Predict Price
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Result Card (Hidden by default) -->
            <div id="resultCard" class="card bg-gradient-success border-0 shadow-lg mt-4" style="display: none;">
                <div class="card-body p-4 text-center">
                    <h3 class="text-white mb-3">
                        <i class="bi bi-currency-dollar"></i> Predicted Price
                    </h3>
                    <h1 class="display-3 fw-bold text-white" id="predictedPrice">Rp 0</h1>
                    <p class="text-white-50 mt-3 mb-2">Based on AI/ML Random Forest model</p>
                    <small class="text-white-50">Model Accuracy (R²): 76.54% | Dataset: benerlagi.csv</small>
                    
                    <!-- Calculated Scores Display -->
                    <div class="mt-4 pt-3 border-top border-white-50">
                        <h6 class="text-white mb-3"><i class="bi bi-calculator"></i> Auto-Calculated Scores:</h6>
                        <div class="row text-white-50 small">
                            <div class="col-6 col-md-3 mb-2">
                                <i class="bi bi-trophy-fill"></i> Rank: <strong id="scoreRank">-</strong>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <i class="bi bi-gem"></i> Koleksi: <strong id="scoreKoleksi">-</strong>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <i class="bi bi-graph-up-arrow"></i> Winrate: <strong id="scoreWinrate">-</strong>
                            </div>
                            <div class="col-6 col-md-3 mb-2">
                                <i class="bi bi-joystick"></i> Match: <strong id="scoreMatch">-</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Alert (Hidden by default) -->
            <div id="errorAlert" class="alert alert-danger mt-4" style="display: none;">
                <i class="bi bi-exclamation-triangle-fill"></i> 
                <span id="errorMessage"></span>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center mt-4" style="display: none;">
                <div class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Predicting price...</p>
            </div>

            <!-- Model Info -->
            <div class="card bg-dark border-secondary mt-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-info-circle text-info"></i> Model Information
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li><i class="bi bi-check-circle text-success"></i> Model: Random Forest Regressor</li>
                        <li><i class="bi bi-check-circle text-success"></i> Features: 9 (Rank + Score Rank + Koleksi + Jumlah Skin + Score Koleksi + Winrate + Score Winrate + Total Match + Score Total Match)</li>
                        <li><i class="bi bi-check-circle text-success"></i> Accuracy (R²): 76.54%</li>
                        <li><i class="bi bi-check-circle text-success"></i> MAE: Rp 30,783,655</li>
                        <li><i class="bi bi-check-circle text-success"></i> Most Important Feature: Jumlah Skin (62.18%)</li>
                        <li><i class="bi bi-info-circle text-warning"></i> Scores are automatically calculated based on your input!</li>
                    </ul>
                </div>
            </div>

            <!-- Scoring Reference -->
            <div class="card bg-dark border-secondary mt-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <i class="bi bi-book text-primary"></i> Scoring Reference (Auto-Calculated)
                    </h5>
                    
                    <div class="accordion accordion-flush" id="scoringAccordion">
                        <!-- Rank Score -->
                        <div class="accordion-item bg-dark border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRank">
                                    <i class="bi bi-trophy-fill text-warning me-2"></i> Rank Scoring (1-7)
                                </button>
                            </h2>
                            <div id="collapseRank" class="accordion-collapse collapse" data-bs-parent="#scoringAccordion">
                                <div class="accordion-body text-white-50">
                                    <table class="table table-dark table-sm">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>Warrior - Grandmaster</td><td class="text-warning">1</td></tr>
                                            <tr><td>Epic - Legend</td><td class="text-warning">2</td></tr>
                                            <tr><td>Mythic</td><td class="text-warning">3</td></tr>
                                            <tr><td>Mythical Honor</td><td class="text-warning">4</td></tr>
                                            <tr><td>Mythical Glory</td><td class="text-warning">5</td></tr>
                                            <tr><td>Immortal (< 500 pts)</td><td class="text-warning">6</td></tr>
                                            <tr><td>Immortal (> 500 pts)</td><td class="text-warning">7</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Koleksi Score -->
                        <div class="accordion-item bg-dark border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseKoleksi">
                                    <i class="bi bi-gem text-primary me-2"></i> Koleksi Scoring (1-36)
                                </button>
                            </h2>
                            <div id="collapseKoleksi" class="accordion-collapse collapse" data-bs-parent="#scoringAccordion">
                                <div class="accordion-body text-white-50">
                                    <table class="table table-dark table-sm">
                                        <thead>
                                            <tr>
                                                <th>Collector Level</th>
                                                <th>Score Range</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>Kolektor Amatir 1-5</td><td class="text-primary">1-5</td></tr>
                                            <tr><td>Kolektor Junior 1-5</td><td class="text-primary">6-10</td></tr>
                                            <tr><td>Kolektor Senior 1-5</td><td class="text-primary">11-15</td></tr>
                                            <tr><td>Kolektor Ahli 1-5</td><td class="text-primary">16-20</td></tr>
                                            <tr><td>Kolektor Ternama 1-5</td><td class="text-primary">21-25</td></tr>
                                            <tr><td>Kolektor Terhormat 1-5</td><td class="text-primary">26-30</td></tr>
                                            <tr><td>Kolektor Juragan 1-5</td><td class="text-primary">31-35</td></tr>
                                            <tr><td>Kolektor Sultan</td><td class="text-primary">36</td></tr>
                                        </tbody>
                                    </table>
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Example: "Kolektor Ternama IV" = Score 24</small>
                                </div>
                            </div>
                        </div>

                        <!-- Winrate Score -->
                        <div class="accordion-item bg-dark border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWinrate">
                                    <i class="bi bi-graph-up-arrow text-success me-2"></i> Winrate Scoring (1-6)
                                </button>
                            </h2>
                            <div id="collapseWinrate" class="accordion-collapse collapse" data-bs-parent="#scoringAccordion">
                                <div class="accordion-body text-white-50">
                                    <table class="table table-dark table-sm">
                                        <thead>
                                            <tr>
                                                <th>Winrate Range</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>< 45%</td><td class="text-success">1</td></tr>
                                            <tr><td>45% - 50%</td><td class="text-success">2</td></tr>
                                            <tr><td>51% - 55%</td><td class="text-success">3</td></tr>
                                            <tr><td>56% - 60%</td><td class="text-success">4</td></tr>
                                            <tr><td>61% - 70%</td><td class="text-success">5</td></tr>
                                            <tr><td>> 70%</td><td class="text-success">6</td></tr>
                                        </tbody>
                                    </table>
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Higher winrate = Higher score</small>
                                </div>
                            </div>
                        </div>

                        <!-- Total Match Score -->
                        <div class="accordion-item bg-dark border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-dark text-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMatch">
                                    <i class="bi bi-joystick text-info me-2"></i> Total Match Scoring (1-6)
                                </button>
                            </h2>
                            <div id="collapseMatch" class="accordion-collapse collapse" data-bs-parent="#scoringAccordion">
                                <div class="accordion-body text-white-50">
                                    <table class="table table-dark table-sm">
                                        <thead>
                                            <tr>
                                                <th>Total Match Range</th>
                                                <th>Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr><td>> 5000</td><td class="text-info">1</td></tr>
                                            <tr><td>4000 - 5000</td><td class="text-info">2</td></tr>
                                            <tr><td>3000 - 4000</td><td class="text-info">3</td></tr>
                                            <tr><td>2000 - 3000</td><td class="text-info">4</td></tr>
                                            <tr><td>1500 - 2000</td><td class="text-info">5</td></tr>
                                            <tr><td>< 1500</td><td class="text-info">6</td></tr>
                                        </tbody>
                                    </table>
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> More matches = Lower score (but higher experience)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient {
        background: linear-gradient(45deg, #FFD700, #FFA500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .form-control:focus {
        background-color: #1a1a2e !important;
        border-color: #FFD700 !important;
        color: white !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }
</style>

<script>
document.getElementById('predictionForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Hide previous results/errors
    document.getElementById('resultCard').style.display = 'none';
    document.getElementById('errorAlert').style.display = 'none';
    document.getElementById('loadingSpinner').style.display = 'block';
    
    // Get form data
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
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
            // Show result
            document.getElementById('predictedPrice').textContent = result.formatted_price;
            
            // Display calculated scores
            if (result.calculated_scores) {
                document.getElementById('scoreRank').textContent = result.calculated_scores.score_rank;
                document.getElementById('scoreKoleksi').textContent = result.calculated_scores.score_koleksi;
                document.getElementById('scoreWinrate').textContent = result.calculated_scores.score_winrate;
                document.getElementById('scoreMatch').textContent = result.calculated_scores.score_total_match;
            }
            
            document.getElementById('resultCard').style.display = 'block';
            
            // Smooth scroll to result
            document.getElementById('resultCard').scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            // Show error
            document.getElementById('errorMessage').textContent = result.error || 'Prediction failed';
            document.getElementById('errorAlert').style.display = 'block';
        }
    } catch (error) {
        document.getElementById('loadingSpinner').style.display = 'none';
        document.getElementById('errorMessage').textContent = 'Network error: ' + error.message;
        document.getElementById('errorAlert').style.display = 'block';
    }
});
</script>
@endsection
