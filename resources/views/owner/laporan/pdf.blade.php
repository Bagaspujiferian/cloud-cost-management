<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris Onde-Onde</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #f97316; font-size: 24px; }
        .header p { margin: 5px 0 0 0; color: #666; }
        table { w-full; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-emerald { color: #059669; }
        .text-red { color: #dc2626; }
        .footer { position: fixed; bottom: -20px; left: 0; right: 0; text-align: center; font-size: 10px; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Onde-Onde Stock Manager</h1>
        <p>Laporan Riwayat Mutasi Inventaris</p>
        <p>Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal & Waktu</th>
                <th>Aktivitas</th>
                <th>Nama Barang</th>
                <th class="text-right">Masuk</th>
                <th class="text-right">Keluar</th>
                <th class="text-right">Stok Akhir</th>
                <th>Staf</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            <tr>
                <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $log->aktivitas }}</td>
                <td>{{ $log->nama_item }}</td>
                <td class="text-right {{ $log->masuk > 0 ? 'text-emerald' : '' }}">
                    {{ $log->masuk > 0 ? '+'.number_format($log->masuk, 0) : '-' }}
                </td>
                <td class="text-right {{ $log->keluar > 0 ? 'text-red' : '' }}">
                    {{ $log->keluar > 0 ? '-'.number_format($log->keluar, 0) : '-' }}
                </td>
                <td class="text-right"><strong>{{ number_format($log->sisa_stok, 0) }}</strong></td>
                <td>{{ $log->user->name ?? 'Sistem' }}</td>
            </tr>
            @endforeach
            @if($logs->count() == 0)
            <tr>
                <td colspan="7" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada {{ now()->format('d/m/Y H:i:s') }} oleh {{ auth()->check() ? auth()->user()->name : 'Sistem' }}
    </div>
</body>
</html>
