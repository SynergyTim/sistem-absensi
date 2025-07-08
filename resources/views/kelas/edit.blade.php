@extends('layouts')

@section('title', 'Edit Kelas')
@section('content')
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h5 class="mb-0">Edit Kelas</h5>
        </div>

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Nama Kelas</label>
                    <select name="nama_kelas" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach([1,2,3,4,5,6] as $k)
                        <option value="{{ $k }}" {{ $kelas->nama_kelas == $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-success">Update</button>
                <a href="{{ route('kelas.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection