

<?php $__env->startSection('content'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lobby Jual Beli Akun Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: white;
            font-family: 'Poppins', sans-serif;
        }
        .navbar {
            background-color: #111827;
            box-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }
        .navbar-brand {
            font-weight: bold;
            color: #38bdf8 !important;
        }
        .hero-section {
            text-align: center;
            padding: 80px 20px 40px;
        }
        .hero-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: #f8fafc;
        }
        .hero-section p {
            color: #cbd5e1;
        }
        .card {
            background-color: #1e293b;
            border: none;
            border-radius: 12px;
            color: #f1f5f9;
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
            background-color: #334155;
        }
        .card img {
            border-radius: 12px 12px 0 0;
            height: 180px;
            object-fit: cover;
        }
        .price {
            font-weight: bold;
            color: #38bdf8;
        }
        footer {
            text-align: center;
            margin-top: 80px;
            padding: 20px;
            color: #94a3b8;
            border-top: 1px solid #475569;
        }
        .btn-primary {
            background-color: #38bdf8;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0284c7;
        }
        .btn-add-account {
            background-color: #10b981;
            border: none;
        }
        .btn-add-account:hover {
            background-color: #059669;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="#">JualBeliAkun</a>
    <div class="d-flex">
        <a href="<?php echo e(route('accounts.create')); ?>" class="btn btn-add-account">Add New Account</a>
    </div>
  </div>
</nav>

<section class="hero-section">
    <h1>Selamat Datang di Lobby Akun Game</h1>
    <p>Temukan akun impianmu dengan harga terbaik 🔥</p>
</section>

<div class="container">
    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-4 col-sm-6">
                <div class="card shadow-sm">
                    <img src="https://source.unsplash.com/600x400/?gaming,esports,<?php echo e($account->rank); ?>" alt="Game Account">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo e($account->username); ?></h5>
                        <p class="card-text">
                            Rank: <strong><?php echo e($account->rank); ?></strong><br>
                            Hero: <?php echo e($account->hero_count); ?> | Skin: <?php echo e($account->skin); ?>

                        </p>
                        <p class="price">Rp <?php echo e(number_format($account->price, 0, ',', '.')); ?></p>
                        <a href="#" class="btn btn-primary w-100">Beli Sekarang</a>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center text-muted py-5">
                <h5>Belum ada akun yang tersedia 😢</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; <?php echo e(date('Y')); ?> JualBeliAkun — All Rights Reserved</p>
</footer>

</body>
</html>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\KULIAH\SEMESTER 7\Computing Project\jualbeliakun-main\resources\views/home.blade.php ENDPATH**/ ?>