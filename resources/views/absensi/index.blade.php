@extends('layouts')
@section('title', 'Absensi Siswa')
@section('sub-title', 'Catatan kehadiran untuk mengelola absensi secara akurat')
@section('content')

<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="container">
        <div class="card mb-4">
            @php
            \Carbon\Carbon::setLocale('id');
            $now = \Carbon\Carbon::now()->timezone('Asia/Jakarta');
            $hari = $now->translatedFormat('l');
            $tanggal = $now->translatedFormat('d F Y');
            $jam = $now->format('H:i');
            @endphp

            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-center">Absensi Siswa</h5>
                <small class="mb-0 text-black">{{ $hari }}, {{ $tanggal }} - {{ $jam }} WIB</small>
            </div>
            <div class="card-body">

                {{-- Form Pilih Kelas dan Tanggal --}}
                <div class="row mb-4">
                    <div class="col-md-5 mb-2">
                        <label for="kelas_id" class="form-label">Pilih Kelas</label>
                        <select id="kelas_id" class="form-control">
                            <option value="">-- Silahkan Pilih Kelas --</option>
                            @foreach($kelas as $kls)
                            <option value="{{ $kls->id }}">Kelas {{ $kls->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label for="tanggal" class="form-label">Pilih Tanggal</label>
                        <input type="date" id="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-md-3 mb-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" id="bukaModal">Isi Absensi</button>
                    </div>
                </div>
                {{-- Informasi Penting --}}
                <div class="alert alert-info mb-4 text-sm max-w-2xl mx-auto">
                    <strong>Info:</strong> Silakan pilih <strong>Kelas</strong> dan <strong>Tanggal</strong> terlebih dahulu, lalu klik tombol <em>Isi Absensi</em> untuk mulai mengisi daftar kehadiran siswa.
                    <br>Pastikan Anda hanya mengisi absensi satu kali per kelas dan tanggal.
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div class="modal fade" id="modalAbsensi" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('absensi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" id="form_kelas_id">
                    <input type="hidden" name="tanggal" id="form_tanggal">

                    <div class="modal-header text-center position-relative">
                        <div class="w-100">
                            <h4 class="modal-title mb-1">DAFTAR SISWA KELAS <span id="modal_kelas"></span></h4>
                            <h5 class="mb-0" id="modal_tanggal"></h5>
                        </div>
                        <button type="button" class="btn-close position-absolute end-0 top-0 m-3" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th class="text-center">Jenis Kelamin</th>
                                    <th class="text-center">Tanggal Lahir</th>
                                    <th class="text-center">Umur</th>
                                    <th class="text-center">Hadir</th>
                                    <th class="text-center">Izin</th>
                                    <th class="text-center">Alpa</th>
                                </tr>
                            </thead>
                            <tbody id="tabel-absensi">
                                {{-- Data akan diisi lewat JS --}}
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan Absensi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function formatTanggalIndonesia(tanggalString) {
            const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            const tanggal = new Date(tanggalString);
            const hariStr = hari[tanggal.getDay()];
            const tgl = tanggal.getDate();
            const bln = bulan[tanggal.getMonth()];
            const thn = tanggal.getFullYear();

            return `${hariStr}, ${tgl} ${bln} ${thn}`;
        }

        document.getElementById('bukaModal').addEventListener('click', function() {
            const kelasId = document.getElementById('kelas_id').value;
            const tanggal = document.getElementById('tanggal').value;

            if (!kelasId || !tanggal) {
                alert('Harap pilih kelas dan tanggal terlebih dahulu.');
                return;
            }

            fetch(`/absensi/get-data/${kelasId}/${tanggal}`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('form_kelas_id').value = kelasId;
                    document.getElementById('form_tanggal').value = tanggal;
                    document.getElementById('modal_kelas').innerText = data.kelas.nama_kelas;
                    document.getElementById('modal_tanggal').innerText = formatTanggalIndonesia(tanggal);

                    const tbody = document.getElementById('tabel-absensi');
                    tbody.innerHTML = '';

                    data.siswa.forEach((siswa, index) => {
                        const absen = data.absensi[siswa.id] || {};
                        const hadir = absen.hadir ? 'checked' : '';
                        const izin = absen.izin ? 'checked' : '';
                        const alpa = absen.alpa ? 'checked' : '';

                        let tglLahir = siswa.tanggal_lahir ?
                            new Date(siswa.tanggal_lahir).toLocaleDateString('id-ID') :
                            '-';

                        tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${siswa.nama_siswa}</td>
                        <td class="text-center">${siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</td>
                        <td class="text-center">${tglLahir}</td>
                        <td class="text-center">${siswa.umur ?? '-'}</td>
                        <td class="text-center">
                            <input type="radio" name="absen[${siswa.id}]" value="hadir" ${hadir} required>
                        </td>
                        <td class="text-center">
                            <input type="radio" name="absen[${siswa.id}]" value="izin" ${izin}>
                        </td>
                        <td class="text-center">
                            <input type="radio" name="absen[${siswa.id}]" value="alpa" ${alpa}>
                        </td>
                    </tr>
                `;
                    });

                    new bootstrap.Modal(document.getElementById('modalAbsensi')).show();
                });
        });
    </script>
    @endsection