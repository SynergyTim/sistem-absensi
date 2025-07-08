@extends('layouts')

@section('title', 'History')
@section('content')
<div class="container">
    <h3 class="text-light">Riwayat Absensi</h3>

    <form method="GET" class="mb-3">
        <select name="kelas_id" class="form-select">
            <option value="">Semua Kelas</option>
            @foreach($kelas as $kls)
            <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                Kelas {{ $kls->nama_kelas }}
            </option>
            @endforeach
        </select>

        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control my-2">
        <button class="btn btn-primary">Filter</button>
    </form>
    <a href="{{ route('absensi.export') }}" class="btn btn-success mb-2">Export Excel</a>
    <a href="{{ route('absensi.exportPdf') }}" class="btn btn-danger mb-2">Export PDF</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama</th>
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
                <td>{{ $row->hadir }}</td>
                <td>{{ $row->izin }}</td>
                <td>{{ $row->alpa }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
