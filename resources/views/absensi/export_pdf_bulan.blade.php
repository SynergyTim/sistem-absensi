<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Absensi Bulan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
    </style>
</head>
<body>
    <h3>Rekap Absensi Bulan {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</h3>

    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Tanggal Lahir</th>
                <th>Umur</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $siswa_id => $absensi)
                @php
                    $siswa = $absensi->first()->siswa;
                    $tanggalLahir = $siswa->tanggal_lahir
                        ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y')
                        : '-';
                    $umur = $siswa->tanggal_lahir
                        ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->age . ' tahun'
                        : '-';
                @endphp
                <tr>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td>{{ $tanggalLahir }}</td>
                    <td>{{ $umur }}</td>
                    <td>{{ $absensi->sum('hadir') }}</td>
                    <td>{{ $absensi->sum('izin') }}</td>
                    <td>{{ $absensi->sum('alpa') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
