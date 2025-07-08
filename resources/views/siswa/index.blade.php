@extends('layouts.main')

@section('content')
<div class="container">
    <h3>Data Siswa</h3>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-2">Tambah Siswa</a>

    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Nama</th><th>JK</th><th>Kelas</th><th>Aksi</th></tr>
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
                        <form action="{{ route('siswa.destroy', $sw->id) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
