@extends('layouts')

@section('title', 'Siswa')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3>Data Siswa</h3>
                <div>
                    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-2">Tambah Siswa</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>JK</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswa as $sw)
                    <tr>
                        <td>{{ $sw->id }}</td>
                        <td>{{ $sw->nama_siswa }}</td>
                        <td>{{ $sw->jenis_kelamin }}</td>
                        <td>{{ $sw->kelas->nama_kelas }}</td>
                        <td>
                            <a href="{{ route('siswa.edit', $sw->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('siswa.destroy', $sw->id) }}" method="POST" class="d-inline delete-form">
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