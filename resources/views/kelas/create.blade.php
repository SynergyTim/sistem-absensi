@extends('layouts')

@section('title', 'Create Kelas')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Tambah Kelas</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('kelas.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_kelas" class="form-label">Nama Kelas</label>
                    <select name="nama_kelas" id="nama_kelas" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach([1,2,3,4,5,6] as $kelas)
                            <option value="{{ $kelas }}" {{ old('nama_kelas') == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
