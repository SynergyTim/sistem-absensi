@extends('layouts.main')

@section('content')
<div class="container">
    <h3>Data Kelas</h3>
    <a href="{{ route('kelas.create') }}" class="btn btn-primary mb-2">Tambah Kelas</a>

    <table class="table table-bordered">
        <thead>
            <tr><th>ID</th><th>Nama Kelas</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($kelas as $kls)
                <tr>
                    <td>{{ $kls->id }}</td>
                    <td>{{ $kls->nama_kelas }}</td>
                    <td>
                        <a href="{{ route('kelas.edit', $kls->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('kelas.destroy', $kls->id) }}" method="POST" style="display:inline;">
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
