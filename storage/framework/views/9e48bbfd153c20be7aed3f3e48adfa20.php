

<?php $__env->startSection('title', 'MLBB Heroes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-4 fw-bold text-center mb-4">Mobile Legends Heroes</h1>
            
            <!-- Search Form -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form action="<?php echo e(route('heroes.search')); ?>" method="GET" class="row g-3">
                        <div class="col-md-10">
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-lg" 
                                   placeholder="Cari hero..." 
                                   value="<?php echo e($search ?? ''); ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="bi bi-search"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <?php if(isset($search)): ?>
                <div class="alert alert-info">
                    Hasil pencarian untuk: <strong><?php echo e($search); ?></strong> - <?php echo e($heroes->total()); ?> hero ditemukan
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $heroes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hero): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="position-relative">
                    <img src="<?php echo e($hero->hero_image); ?>" 
                         class="card-img-top" 
                         alt="<?php echo e($hero->hero_name); ?>"
                         style="height: 200px; object-fit: cover;"
                         referrerpolicy="no-referrer"
                         crossorigin="anonymous"
                         loading="lazy"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?php echo e(urlencode($hero->hero_name)); ?>&size=200&background=random&color=fff&bold=true'">
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-primary rounded-pill">
                            <?php echo e($hero->total_skins); ?> Skins
                        </span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold text-center"><?php echo e($hero->hero_name); ?></h5>
                    <div class="mt-auto">
                        <a href="<?php echo e(route('heroes.show', $hero->id)); ?>" 
                           class="btn btn-primary w-100">
                            <i class="bi bi-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-12">
            <div class="alert alert-warning text-center">
                <i class="bi bi-exclamation-triangle"></i> 
                Tidak ada hero ditemukan.
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-5 d-flex justify-content-center">
        <?php echo e($heroes->links()); ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\src\xampp\htdocs\compro\Comput\resources\views/heroes/index.blade.php ENDPATH**/ ?>