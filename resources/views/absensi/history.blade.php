@extends('layouts')
@section('title', 'History Absensi')
@section('sub-title', 'Catatan kehadiran untuk mengelola absensi secara akurat')
@section('content')

<div class="container">
    <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Absensi</h5>
        </div>
        <div class="card-body">

            {{-- Filter Form --}}
            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-2">
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelas as $kls)
                        <option value="{{ $kls->id }}" {{ request('kelas_id') == $kls->id ? 'selected' : '' }}>
                            Kelas {{ $kls->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control">
                </div>
                {{-- Filter bulan pakai select --}}
                <div class="col-md-3">
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @php
                            $bulanIndo = [
                                1 => 'Januari',
                                2 => 'Februari',
                                3 => 'Maret',
                                4 => 'April',
                                5 => 'Mei',
                                6 => 'Juni',
                                7 => 'Juli',
                                8 => 'Agustus',
                                9 => 'September',
                                10 => 'Oktober',
                                11 => 'November',
                                12 => 'Desember',
                            ];
                        @endphp
                        @foreach($bulanIndo as $key => $namaBulan)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                    <a href="{{ route('absensi.export', [
    'kelas_id' => request('kelas_id'),
    'tanggal' => request('tanggal'),
    'bulan' => request('bulan'),
    'tahun' => request('tahun')
]) }}"
class="btn btn-success">
    <i class="fas fa-file-excel me-1"></i>Excel
</a>

                    <a href="{{ route('absensi.exportPdf') }}?kelas_id={{ request('kelas_id') }}&tanggal={{ request('tanggal') }}&bulan={{ request('bulan') }}" class="btn btn-danger"><i class="fas fa-file-pdf me-1"></i>PDF</a>
                </div>
            </form>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-bordered">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Tanggal Absensi</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Lahir</th>
                            <th>Umur</th>
                            <th>Kelas</th>
                            <th>Hadir</th>
                            <th>Izin</th>
                            <th>Alpa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr class="text-center">
                            <td>
                                {{ \Carbon\Carbon::parse($row->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="text-start">{{ $row->siswa->nama_siswa }}</td>
                            <td>
                                {{ $row->siswa->tanggal_lahir
                                    ? \Carbon\Carbon::parse($row->siswa->tanggal_lahir)->format('d-m-Y')
                                    : '-' }}
                            </td>
                            <td>
                                {{ $row->siswa->umur ?? '-' }}
                            </td>
                            <td>Kelas {{ $row->kelas->nama_kelas }}</td>
                            <td>
                                <input type="radio" disabled {{ $row->hadir ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="radio" disabled {{ $row->izin ? 'checked' : '' }}>
                            </td>
                            <td>
                                <input type="radio" disabled {{ $row->alpa ? 'checked' : '' }}>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Info --}}
            <div class="alert alert-info small">
                <i class="fas fa-info-circle me-1"></i>
                Gunakan filter untuk menampilkan data absensi berdasarkan kelas, tanggal, atau bulan. Anda juga dapat mencetak hasil dalam bentuk PDF dan Excel.
            </div>
        </div>
    </div>
</div>

@endsection
