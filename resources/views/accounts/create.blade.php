@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#0f0f1a] text-white flex flex-col items-center py-10">
    <div class="w-full max-w-3xl bg-[#181826] rounded-2xl shadow-lg p-8">
        <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-purple-400 mb-4 inline-block">
            ← Back to Dashboard
        </a>

        <h2 class="text-2xl font-semibold mb-6">Add New Account</h2>
        <p class="text-sm text-gray-400 mb-8">
            Manually input account data with heroes and skins
        </p>

        <form action="{{ route('accounts.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Rank and Price -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Current Rank</label>
                    <select name="rank" required class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select rank</option>
                        @foreach ($ranks as $rank)
                            <option value="{{ $rank }}">{{ $rank }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Highest Rank</label>
                    <select name="highest_rank" class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select highest rank</option>
                        @foreach ($highestRanks as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Win Rate and Total Matches -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Win Rate (%)</label>
                    <select name="winrate" class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select win rate</option>
                        <option value="40">< 45%</option>
                        <option value="47.5">45-50%</option>
                        <option value="53">51-55%</option>
                        <option value="58">56-60%</option>
                        <option value="65.5">61-70%</option>
                        <option value="75">> 70%</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Total Matches</label>
                    <select name="total_matches" class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select total matches</option>
                        <option value="1000">< 1500</option>
                        <option value="1750">1500-2000</option>
                        <option value="2500">2000-3000</option>
                        <option value="3500">3000-4000</option>
                        <option value="4500">4000-5000</option>
                        <option value="6000">> 5000</option>
                    </select>
                </div>
            </div>

            <!-- Predicted Price -->
            <div class="bg-gradient-to-r from-purple-900/30 to-blue-900/30 border border-purple-500/50 p-4 rounded-lg">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="text-sm text-gray-400 mb-1">Predicted Price</h4>
                        <p id="predicted-price" class="text-2xl font-bold text-purple-400">Rp 0</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Based on ML Model</p>
                        <p class="text-xs text-gray-500">Will update after selection</p>
                    </div>
                </div>
            </div>

            <!-- Select Heroes -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm">Select Heroes ({{ count($heroes) }} heroes available)</label>
                    <label class="flex items-center space-x-2 text-sm text-purple-400 cursor-pointer">
                        <input type="checkbox" id="select-all-heroes" class="text-purple-500 focus:ring-purple-500">
                        <span>Select All</span>
                    </label>
                </div>
                <div class="bg-[#181828] p-3 rounded-lg border border-gray-700 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach ($heroes as $heroId => $heroName)
                            @php
                                $hero = \App\Models\Hero::find($heroId);
                            @endphp
                            <label class="flex flex-col items-center space-y-2 bg-[#25253b] p-3 rounded-lg border border-gray-700 hover:border-purple-500 cursor-pointer transition">
                                <input type="checkbox" name="heroes[]" value="{{ $heroId }}" class="text-purple-500 focus:ring-purple-500" data-hero-id="{{ $heroId }}">
                                @if($hero && $hero->hero_image)
                                    <img src="{{ \App\Helpers\ImageHelper::getProxiedImage($hero->hero_image, $hero->hero_name) }}" 
                                         alt="{{ $heroName }}"
                                         class="w-16 h-16 rounded-lg object-cover"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ \App\Helpers\ImageHelper::getFallbackImage($heroName, 64) }}'">
                                @endif
                                <span class="text-xs text-center">{{ $heroName }}</span>
                                <span class="text-xs text-gray-400">{{ $skins[$heroId] ? count($skins[$heroId]) : 0 }} skins</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Available Skins (auto updated by JS) -->
            <div id="skins-section" class="hidden">
                <div class="bg-[#1b263b] rounded-lg p-4 border border-purple-500">
                    <div class="flex justify-between items-center mb-3">
                        <div>
                            <h4 class="font-medium">Select Skins from Selected Heroes</h4>
                            <div id="category-counts" class="flex flex-wrap gap-2 mt-2 text-xs"></div>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <label class="flex items-center space-x-2 text-sm text-purple-400 cursor-pointer">
                                <input type="checkbox" id="select-all-skins" class="text-purple-500 focus:ring-purple-500">
                                <span>Select All</span>
                            </label>
                            <span id="skin-count" class="text-sm text-gray-400"></span>
                        </div>
                    </div>
                    <div id="skins-container" class="space-y-4"></div>
                </div>
            </div>

            <!-- Account Summary -->
            <div class="bg-[#181828] border border-gray-700 p-4 rounded-lg">
                <h4 class="font-medium mb-2">Account Summary</h4>
                <div id="summary" class="text-sm text-gray-400">No heroes selected</div>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="reset" class="px-4 py-2 bg-gray-600 rounded-md hover:bg-gray-500">Reset Form</button>
                <button type="submit" class="px-4 py-2 bg-purple-600 rounded-md hover:bg-purple-500">Add Account</button>
            </div>
        </form>
    </div>
</div>

<script>
    const heroesData = @json($heroes);
    const heroesFullData = @json($heroesFullData ?? []);
    const skinsData = @json($skins);

    const heroCheckboxes = document.querySelectorAll('input[name="heroes[]"]');
    const skinsSection = document.getElementById('skins-section');
    const skinsContainer = document.getElementById('skins-container');
    const skinCount = document.getElementById('skin-count');
    const categoryCounts = document.getElementById('category-counts');
    const summaryDiv = document.getElementById('summary');
    const predictedPriceEl = document.getElementById('predicted-price');

    // Store selected skins state
    let selectedSkins = new Set();

    heroCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSummary);
    });

    // Select All Heroes
    document.getElementById('select-all-heroes').addEventListener('change', function() {
        heroCheckboxes.forEach(cb => {
            cb.checked = this.checked;
        });
        updateSummary();
    });

    // Select All Skins (delegated event)
    document.getElementById('select-all-skins').addEventListener('change', function() {
        const skinCheckboxes = document.querySelectorAll('.skin-checkbox');
        skinCheckboxes.forEach(cb => {
            cb.checked = this.checked;
            updateSkinSelection(cb);
        });
    });

    // Select All Skins per Hero (delegated event)
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-hero-skins')) {
            const heroId = e.target.dataset.heroId;
            const heroSkinCheckboxes = document.querySelectorAll(`.skin-checkbox[data-hero-id="${heroId}"]`);
            heroSkinCheckboxes.forEach(cb => {
                cb.checked = e.target.checked;
                updateSkinSelection(cb);
            });
        }
    });

    function updateSummary() {
        const selectedHeroes = Array.from(heroCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        // Update summary
        if (selectedHeroes.length === 0) {
            summaryDiv.innerHTML = '<p class="text-gray-400">No heroes selected</p>';
            skinsSection.classList.add('hidden');
            selectedSkins.clear(); // Clear selected skins when no heroes selected
            return;
        }

        // Display summary with selected skins count
        updateSummaryDisplay(selectedHeroes);

        // Show skins for ALL selected heroes
        let allSkinsHtml = '';
        let totalSkinsCount = 0;

        selectedHeroes.forEach(heroId => {
            const hero = heroesFullData[heroId];
            const heroSkins = skinsData[heroId] || [];
            
            if (heroSkins.length > 0) {
                totalSkinsCount += heroSkins.length;
                
                allSkinsHtml += `
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-600">
                            <div class="flex items-center gap-2">
                                <img src="${getProxiedImage(hero.image, hero.name)}" 
                                     alt="${hero.name}"
                                     class="w-8 h-8 rounded object-cover"
                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(hero.name)}&size=32&background=random&color=fff'">
                                <h5 class="font-semibold text-purple-300">${hero.name}</h5>
                                <span class="text-xs text-gray-400">(${heroSkins.length} skins)</span>
                            </div>
                            <label class="flex items-center space-x-2 text-sm text-purple-400 cursor-pointer">
                                <input type="checkbox" class="select-hero-skins text-purple-500 focus:ring-purple-500" data-hero-id="${heroId}">
                                <span>Select All</span>
                            </label>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            ${heroSkins.map(skin => {
                                const isChecked = selectedSkins.has(skin.id.toString());
                                const categoryColors = {
                                    'Other': 'bg-gray-600',
                                    'Common': 'bg-green-600',
                                    'Exquisite': 'bg-blue-600',
                                    'Exceptional': 'bg-purple-600',
                                    'Supreme': 'bg-yellow-600',
                                    'Deluxe': 'bg-pink-600',
                                    'Legend': 'bg-orange-600'
                                };
                                const categoryColor = categoryColors[skin.category] || 'bg-gray-600';
                                
                                return `
                                <label class="flex flex-col bg-[#25253b] p-2 rounded-md border border-gray-700 hover:border-purple-400 cursor-pointer transition">
                                    <div class="relative mb-2">
                                        <img src="${getProxiedImage(skin.image, skin.name)}" 
                                             alt="${skin.name}"
                                             class="w-full h-24 object-cover rounded"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(skin.name)}&size=96&background=random&color=fff'">
                                        <span class="absolute top-1 left-1 text-xs px-2 py-0.5 rounded ${categoryColor} text-white">
                                            ${skin.category}
                                        </span>
                                        <input type="checkbox" 
                                               name="skins[]" 
                                               value="${skin.id}" 
                                               class="skin-checkbox absolute top-1 right-1 text-purple-500 focus:ring-purple-500 w-5 h-5"
                                               data-hero-id="${heroId}"
                                               ${isChecked ? 'checked' : ''}
                                               onchange="updateSkinSelection(this)">
                                    </div>
                                    <span class="text-xs text-center">${skin.name}</span>
                                </label>
                            `;
                            }).join('')}
                        </div>
                    </div>
                `;
            }
        });

        if (totalSkinsCount > 0) {
            skinsSection.classList.remove('hidden');
            skinsContainer.innerHTML = allSkinsHtml;
            skinCount.textContent = `${totalSkinsCount} skins available | ${selectedSkins.size} selected`;
            updateCategoryCounts();
        } else {
            skinsSection.classList.add('hidden');
        }
        
        updatePredictedPrice();
    }

    function updateCategoryCounts() {
        const categoryCounts = {};
        const categoryColors = {
            'Other': 'bg-gray-600',
            'Common': 'bg-green-600',
            'Exquisite': 'bg-blue-600',
            'Exceptional': 'bg-purple-600',
            'Supreme': 'bg-yellow-600',
            'Deluxe': 'bg-pink-600',
            'Legend': 'bg-orange-600'
        };
        
        // Count selected skins by category
        selectedSkins.forEach(skinId => {
            const skin = findSkinById(skinId);
            if (skin) {
                categoryCounts[skin.category] = (categoryCounts[skin.category] || 0) + 1;
            }
        });
        
        // Display category counts
        const countsHtml = Object.entries(categoryCounts)
            .map(([category, count]) => {
                const color = categoryColors[category] || 'bg-gray-600';
                return `<span class="${color} text-white px-2 py-1 rounded">${category}: ${count}</span>`;
            })
            .join('');
        
        document.getElementById('category-counts').innerHTML = countsHtml || '<span class="text-gray-500">No skins selected</span>';
    }
    
    function findSkinById(skinId) {
        for (const heroId in skinsData) {
            const skin = skinsData[heroId].find(s => s.id.toString() === skinId.toString());
            if (skin) return skin;
        }
        return null;
    }
    
    function updatePredictedPrice() {
        // Simple price calculation based on heroes and skins
        const selectedHeroes = Array.from(heroCheckboxes).filter(cb => cb.checked).length;
        const basePrice = 50000;
        const heroPrice = selectedHeroes * 15000;
        const skinPrice = selectedSkins.size * 8000;
        
        // Add category bonuses
        let categoryBonus = 0;
        selectedSkins.forEach(skinId => {
            const skin = findSkinById(skinId);
            if (skin) {
                const bonuses = {
                    'Legend': 50000,
                    'Deluxe': 30000,
                    'Supreme': 20000,
                    'Exceptional': 10000,
                    'Exquisite': 5000,
                    'Common': 2000,
                    'Other': 1000
                };
                categoryBonus += bonuses[skin.category] || 0;
            }
        });
        
        const totalPrice = basePrice + heroPrice + skinPrice + categoryBonus;
        predictedPriceEl.textContent = `Rp ${totalPrice.toLocaleString('id-ID')}`;
    }

    function updateSummaryDisplay(selectedHeroes) {
        const heroNames = selectedHeroes.map(id => heroesData[id]);
        
        summaryDiv.innerHTML = `
            <p><strong>Heroes Selected:</strong> ${selectedHeroes.length}</p>
            <p class="text-sm text-gray-400">${heroNames.join(', ')}</p>
            <p class="text-sm"><strong>Total Skins Selected:</strong> ${selectedSkins.size}</p>
        `;
    }

    function updateSkinSelection(checkbox) {
        const skinId = checkbox.value;
        
        if (checkbox.checked) {
            selectedSkins.add(skinId);
        } else {
            selectedSkins.delete(skinId);
        }
        
        // Update summary count
        const selectedHeroes = Array.from(heroCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        if (selectedHeroes.length > 0) {
            updateSummaryDisplay(selectedHeroes);
            
            // Update skin count display
            const totalAvailable = Array.from(document.querySelectorAll('.skin-checkbox')).length;
            skinCount.textContent = `${totalAvailable} skins available | ${selectedSkins.size} selected`;
            
            updateCategoryCounts();
            updatePredictedPrice();
        }
    }

    // Helper function untuk proxy gambar
    function getProxiedImage(url, fallbackText) {
        if (!url) {
            return `https://ui-avatars.com/api/?name=${encodeURIComponent(fallbackText)}&size=200&background=random&color=fff`;
        }
        
        if (url.includes('wikia.nocookie.net') || url.includes('fandom.com')) {
            return 'https://images.weserv.nl/?url=' + encodeURIComponent(url);
        }
        
        return url;
    }
</script>
@endsection
