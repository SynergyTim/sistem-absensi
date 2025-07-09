<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Absensi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3 style="text-align: center;">Rekap Data Absensi</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->siswa->nama_siswa }}</td>
                <td>{{ $row->kelas->nama_kelas }}</td>
                <td>{!! $row->hadir ? '✓' : '' !!}</td>
                <td>{!! $row->izin ? '✓' : '' !!}</td>
                <td>{!! $row->alpa ? '✓' : '' !!}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>