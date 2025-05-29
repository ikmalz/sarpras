<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $i => $b)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $b->user->name }}</td>
                    <td>{{ $b->item->name }}</td>
                    <td>{{ $b->quantity }}</td>
                    <td>{{ ucfirst($b->status) }}</td>
                    <td>{{ $b->created_at->format('d-m-Y') }}</td>
                    <td>{{ $b->due ? $b->due->format('d-m-Y') : '-' }}</td>
                    <td>{{ $b->approver->name ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
