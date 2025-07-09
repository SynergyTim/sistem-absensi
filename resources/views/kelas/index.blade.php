@extends('layouts')

@section('title', 'Kelas')
@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-chalkboard me-2"></i>Data Kelas</h3>
                <div>
                    <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Kelas
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th><i class="fas fa-id-badge"></i> ID Kelas</th>
                        <th><i class="fas fa-layer-group"></i> Nama Kelas</th>
                        <th><i class="fas fa-cogs"></i> Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kelas as $kls)
                    <tr>
                        <td>{{ $kls->id }}</td>
                        <td>Kelas {{ $kls->nama_kelas }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning edit-btn"
                                data-id="{{ $kls->id }}"
                                data-nama="{{ $kls->nama_kelas }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('kelas.destroy', $kls->id) }}" method="POST" class="d-inline delete-form">
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

    <!-- Modal Create Kelas -->
    <div class="modal fade" id="modalCreate" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('kelas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <select name="nama_kelas" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach([1,2,3,4,5,6] as $k)
                            <option value="{{ $k }}">Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kelas -->
    <div class="modal fade" id="modalEdit" tabindex="-1">
        <div class="modal-dialog">
            <form id="editForm" method="POST" class="modal-content">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <select name="nama_kelas" id="edit_nama_kelas" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            @foreach([1,2,3,4,5,6] as $k)
                            <option value="{{ $k }}">Kelas {{ $k }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning">Update Kelas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script JS --}}
<script>
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nama = this.dataset.nama;

            const form = document.getElementById('editForm');
            const select = document.getElementById('edit_nama_kelas');

            // Set action form sesuai ID kelas
            form.action = `/kelas/${id}`;

            // Set value select sesuai nama_kelas
            [...select.options].forEach(opt => {
                opt.selected = opt.value === nama;
            });
        });
    });
</script>
@endsection