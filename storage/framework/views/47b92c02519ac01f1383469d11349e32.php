<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-[#0f0f1a] text-white flex flex-col items-center py-10">
    <div class="w-full max-w-3xl bg-[#181826] rounded-2xl shadow-lg p-8">
        <a href="<?php echo e(route('home')); ?>" class="text-sm text-gray-400 hover:text-purple-400 mb-4 inline-block">
            ← Back to Dashboard
        </a>

        <h2 class="text-2xl font-semibold mb-6">Add New Account</h2>
        <p class="text-sm text-gray-400 mb-8">
            Manually input account data with heroes and skins
        </p>

        <form action="<?php echo e(route('accounts.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <!-- Rank and Price -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm mb-1">Rank</label>
                    <select name="rank" class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                        <option value="">Select rank</option>
                        <?php $__currentLoopData = $ranks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rank): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($rank); ?>"><?php echo e($rank); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm mb-1">Price (Rp)</label>
                    <input type="number" name="price" value="1000000" class="w-full p-2 rounded-md bg-[#25253b] border border-gray-600 focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <!-- Select Heroes -->
            <div>
                <label class="block text-sm mb-2">Select Heroes (<?php echo e(count($heroes)); ?> heroes available)</label>
                <div class="bg-[#181828] p-3 rounded-lg border border-gray-700 max-h-96 overflow-y-auto">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        <?php $__currentLoopData = $heroes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $heroId => $heroName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $hero = \App\Models\Hero::find($heroId);
                            ?>
                            <label class="flex flex-col items-center space-y-2 bg-[#25253b] p-3 rounded-lg border border-gray-700 hover:border-purple-500 cursor-pointer transition">
                                <input type="checkbox" name="heroes[]" value="<?php echo e($heroId); ?>" class="text-purple-500 focus:ring-purple-500" data-hero-id="<?php echo e($heroId); ?>">
                                <?php if($hero && $hero->hero_image): ?>
                                    <img src="<?php echo e(\App\Helpers\ImageHelper::getProxiedImage($hero->hero_image, $hero->hero_name)); ?>" 
                                         alt="<?php echo e($heroName); ?>"
                                         class="w-16 h-16 rounded-lg object-cover"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='<?php echo e(\App\Helpers\ImageHelper::getFallbackImage($heroName, 64)); ?>'">
                                <?php endif; ?>
                                <span class="text-xs text-center"><?php echo e($heroName); ?></span>
                                <span class="text-xs text-gray-400"><?php echo e($skins[$heroId] ? count($skins[$heroId]) : 0); ?> skins</span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Available Skins (auto updated by JS) -->
            <div id="skins-section" class="hidden">
                <div class="bg-[#1b263b] rounded-lg p-4 border border-purple-500">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium">Select Skins from Selected Heroes</h4>
                        <span id="skin-count" class="text-sm text-gray-400"></span>
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
    const heroesData = <?php echo json_encode($heroes, 15, 512) ?>;
    const heroesFullData = <?php echo json_encode($heroesFullData ?? [], 15, 512) ?>;
    const skinsData = <?php echo json_encode($skins, 15, 512) ?>;

    const heroCheckboxes = document.querySelectorAll('input[name="heroes[]"]');
    const skinsSection = document.getElementById('skins-section');
    const skinsContainer = document.getElementById('skins-container');
    const skinCount = document.getElementById('skin-count');
    const summaryDiv = document.getElementById('summary');

    // Store selected skins state
    let selectedSkins = new Set();

    heroCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSummary);
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
                        <div class="flex items-center gap-2 mb-3 pb-2 border-b border-gray-600">
                            <img src="${getProxiedImage(hero.image, hero.name)}" 
                                 alt="${hero.name}"
                                 class="w-8 h-8 rounded object-cover"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(hero.name)}&size=32&background=random&color=fff'">
                            <h5 class="font-semibold text-purple-300">${hero.name}</h5>
                            <span class="text-xs text-gray-400">(${heroSkins.length} skins)</span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            ${heroSkins.map(skin => {
                                const isChecked = selectedSkins.has(skin.id.toString());
                                return `
                                <label class="flex flex-col bg-[#25253b] p-2 rounded-md border border-gray-700 hover:border-purple-400 cursor-pointer transition">
                                    <div class="relative mb-2">
                                        <img src="${getProxiedImage(skin.image, skin.name)}" 
                                             alt="${skin.name}"
                                             class="w-full h-24 object-cover rounded"
                                             loading="lazy"
                                             onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(skin.name)}&size=96&background=random&color=fff'">
                                        <input type="checkbox" 
                                               name="skins[]" 
                                               value="${skin.id}" 
                                               class="skin-checkbox absolute top-1 right-1 text-purple-500 focus:ring-purple-500 w-5 h-5"
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
            skinCount.textContent = `${totalSkinsCount} skins available`;
        } else {
            skinsSection.classList.add('hidden');
        }
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\src\xampp\htdocs\compro\Comput\resources\views/accounts/create.blade.php ENDPATH**/ ?>