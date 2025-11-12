<!DOCTYPE html>
<html>
<head>
    <title>Account List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<div class="container">
    <h2 class="mb-4">Daftar Akun</h2>

    <a href="{{ route('accounts.create') }}" class="btn btn-primary mb-3">+ Add Account</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Rank</th>
                <th>Hero Count</th>
                <th>Skin</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td>{{ $account->id }}</td>
                    <td>{{ $account->username }}</td>
                    <td>{{ $account->rank }}</td>
                    <td>{{ $account->hero_count }}</td>
                    <td>{{ $account->skin }}</td>
                    <td>Rp {{ number_format($account->price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data akun.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
