@extends('layouts')

@section('title', 'Edit Siswa')
@section('content')
<div class="container">
    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Edit Siswa</h5>
            </div>

            <div class="card-body">
                @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
                @endif

                <form action="{{ route('siswa.update', $siswa->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label>Nama Siswa</label>
                        <input type="text" name="nama_siswa" value="{{ $siswa->nama_siswa }}" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="L" {{ $siswa->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $siswa->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-control" required>
                            @foreach($kelas as $kls)
                            <option value="{{ $kls->id }}" {{ $siswa->kelas_id == $kls->id ? 'selected' : '' }}>
                                {{ $kls->nama_kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-warning">Update</button>
                <a href="{{ route('siswa.index') }}" class="btn btn-secondary">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection