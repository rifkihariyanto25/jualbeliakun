

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
                <label class="block text-sm mb-2">Select Heroes</label>
                <div class="grid grid-cols-3 gap-2 bg-[#181828] p-3 rounded-lg border border-gray-700">
                    <?php $__currentLoopData = $heroes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" name="heroes[]" value="<?php echo e($slug); ?>" class="text-purple-500 focus:ring-purple-500">
                            <span><?php echo e($name); ?></span>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <!-- Available Skins (auto updated by JS) -->
            <div id="skins-section" class="hidden">
                <div class="bg-[#1b263b] rounded-lg p-4 border border-purple-500">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-medium">Available Skins</h4>
                        <span id="skin-count" class="text-sm text-gray-400"></span>
                    </div>
                    <div id="skins-container" class="grid grid-cols-2 gap-2"></div>
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
    const skinsData = <?php echo json_encode($skins, 15, 512) ?>;

    const heroCheckboxes = document.querySelectorAll('input[name="heroes[]"]');
    const skinsSection = document.getElementById('skins-section');
    const skinsContainer = document.getElementById('skins-container');
    const skinCount = document.getElementById('skin-count');
    const summaryDiv = document.getElementById('summary');

    heroCheckboxes.forEach(cb => {
        cb.addEventListener('change', updateSummary);
    });

    function updateSummary() {
        const selectedHeroes = Array.from(heroCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);

        // Update summary
        if (selectedHeroes.length === 0) {
            summaryDiv.textContent = 'No heroes selected';
            skinsSection.classList.add('hidden');
            return;
        }

        summaryDiv.textContent = 'Heroes: ' + selectedHeroes.map(slug => heroesData[slug]).join(', ');

        // Show skins for first selected hero
        const hero = selectedHeroes[selectedHeroes.length - 1];
        const heroSkins = skinsData[hero] || [];

        if (heroSkins.length > 0) {
            skinsSection.classList.remove('hidden');
            skinsContainer.innerHTML = heroSkins.map(skin => `
                <label class="flex items-center space-x-2 bg-[#25253b] p-2 rounded-md border border-gray-700">
                    <input type="radio" name="skins[]" value="${skin.slug}">
                    <span>${skin.name} <small class="text-gray-400">(${skin.tier})</small></span>
                </label>
            `).join('');
            skinCount.textContent = `${heroSkins.length} skins`;
        } else {
            skinsSection.classList.add('hidden');
        }
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SEMESTER 7\Computing Project\jualbeliakun-main\resources\views/accounts/create.blade.php ENDPATH**/ ?>