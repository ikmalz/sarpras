<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Pengembalian</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f3f3f3;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center;">Laporan Pengembalian Barang</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $return)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $return->borrowing->user->name }}</td>
                <td>{{ $return->borrowing->item->name }}</td>
                <td>{{ $return->returned_quantity }}</td>
                <td>{{ $return->description ?? '-' }}</td>
                <td>{{ $return->is_confirmed ? 'Disetujui' : 'Menunggu' }}</td>
                <td>{{ $return->created_at->format('d-m-Y') }}</td>
                <td>
                    @if($return->image)
                    <a href="{{ asset('storage/' . $return->image) }}" target="_blank">
                        <img src="{{ public_path('storage/' . $return->image) }}" style="height: 50px;">
                    </a>
                    @else
                    <span>Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>