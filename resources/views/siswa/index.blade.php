@extends('layouts')

@section('title', 'Siswa')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-users me-2"></i>Data Siswa</h3>
                <div>
                    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-2">
                        <i class="fas fa-user-plus me-1"></i> Tambah Siswa
                    </a>
                    <button class="btn btn-success mb-2" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="fas fa-file-import me-1"></i> Import
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-badge"></i> ID</th>
                        <th><i class="fas fa-user"></i> Nama Siswa</th>
                        <th><i class="fas fa-venus-mars"></i> Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Umur</th>
                        <th><i class="fas fa-chalkboard"></i> Kelas</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $sw)
                    <tr>
                        <td>{{ $sw->id }}</td>
                        <td>{{ $sw->nama_siswa }}</td>
                        <td>
                            {{ $sw->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </td>
                        <td>{{ $sw->tanggal_lahir ? \Carbon\Carbon::parse($sw->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $sw->umur ?? '-' }}</td>
                        <td>Kelas {{ $sw->kelas->nama_kelas }}</td>
                        <td>
                            <a href="{{ route('siswa.edit', $sw->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('siswa.destroy', $sw->id) }}" method="POST" class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="modalImport" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('siswa.import.store') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-import me-1"></i> Import Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i> <strong>Petunjuk Import:</strong>
                        <ul class="mb-0 mt-1 ps-4">
                            <li>Pilih <strong>Kelas</strong> terlebih dahulu.</li>
                            <li>Gunakan file CSV dengan format: <code>nama_siswa, jenis_kelamin(L/P), tanggal_lahir(YYYY-MM-DD)</code>.</li>
                            <li>Contoh: <code>Budi, L, 2016-05-12</code></li>
                        </ul>
                    </div>

                    <div class="mb-2">
                        <label class="form-label"> Pilih Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            @foreach($kelas as $kls)
                            <option value="{{ $kls->id }}">Kelas {{ $kls->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-2">
                        <label class="form-label"> File CSV</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection