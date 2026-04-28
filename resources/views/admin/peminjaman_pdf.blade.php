<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Riwayat Peminjaman</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 5px; text-align: left; }
        th { background: #f0f0f0; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000099; padding-bottom: 10px; }
        .header h3 { margin: 0 0 5px 0; color: #000099; }
        .period { font-style: italic; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h3>📚 Riwayat Peminjaman Buku</h3>
        <p class="period">Periode: {{ $period }}</p>
        <p style="margin:3px 0; font-size:9px;">Dicetak: {{ date('d M Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>User</th>
                <th>Buku</th>
                <th>Status</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                <td>{{ $item->user->name ?? '-' }}<br><small>{{ $item->user->email ?? '' }}</small></td>
                <td>{{ $item->book->judul ?? '-' }}</td>
                <td>{{ strtoupper(str_replace('_', ' ', $item->status)) }}</td>
                <td>{{ $item->bukti ? '✓ Ada' : '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:15px;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>