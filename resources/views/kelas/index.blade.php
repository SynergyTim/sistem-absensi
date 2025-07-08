@extends('layouts')

@section('title', 'Kelas')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Data Kelas</h3>
                <div>
                    <a href="{{ route('kelas.create') }}" class="btn btn-primary mb-2">Tambah Kelas</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelas as $kls)
                    <tr>
                        <td>{{ $kls->id }}</td>
                        <td>{{ $kls->nama_kelas }}</td>
                        <td>
                            <a href="{{ route('kelas.edit', $kls->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('kelas.destroy', $kls->id) }}" method="POST" class="d-inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection