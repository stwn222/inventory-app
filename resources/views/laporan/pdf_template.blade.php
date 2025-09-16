<!DOCTYPE html>
<html>
<head>
    <title>Laporan {{ ucfirst($tipe) }}</title>
    <style>
        body { font-family: sans-serif; margin: 25px; }
        h1, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #999; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; }
        .total-row td { background-color: #f2f2f2; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan {{ ucfirst($tipe) }}</h1>
    <h3>Periode: {{ date('d F Y', strtotime($dari)) }} s/d {{ date('d F Y', strtotime($sampai)) }}</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Catatan</th>
                <th>User</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ date('d-m-Y', strtotime($item->tanggal)) }}</td>
                <td>{{ $item->catatan }}</td>
                <td>{{ $item->user->name }}</td>
                <td class="text-right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="total">Total {{ ucfirst($tipe) }}</td>
                <td class="total text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>