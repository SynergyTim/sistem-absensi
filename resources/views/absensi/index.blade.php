{{-- resources/views/absensi/index.blade.php --}}
@extends('layouts.main')

@section('content')
<div class="container">
    <h3 class="mb-4">Input Absensi</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('absensi.index') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="kelas_id">Pilih Kelas</label>
            <select name="kelas_id" id="kelas_id" class="form-control" onchange="this.form.submit()">
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelas as $kls)
                    <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                        Kelas {{ $kls->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>

    @if(request('kelas_id'))
        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="kelas_id" value="{{ request('kelas_id') }}">

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Absensi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $sw)
                        <tr>
                            <td>{{ $sw->nama_siswa }}</td>
                            <td>{{ $sw->jenis_kelamin }}</td>
                            <td>
                                <label><input type="radio" name="absen[{{ $sw->id }}]" value="hadir" required> Hadir</label>
                                <label><input type="radio" name="absen[{{ $sw->id }}]" value="izin"> Izin</label>
                                <label><input type="radio" name="absen[{{ $sw->id }}]" value="alpa"> Alpa</label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Simpan Absensi</button>
        </form>
    @endif
</div>
@endsection
