@extends('layouts')

@section('title', 'Dashboard')
@section('content')
<div class="row">
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Data Siswa</div>
                        <div class="text-lg fw-bold">{{ $jumlahSiswa }}</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="users"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="{{ route('siswa.index') }}">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Data Kelas</div>
                        <div class="text-lg fw-bold">{{ $jumlahKelas }}</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="book"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="{{ route('kelas.index') }}">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">Absensi Hari Ini</div>
                        <div class="text-lg fw-bold">{{ $jumlahAbsensiHariIni }}</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="check-square"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="{{ route('absensi.index') }}">View Tasks</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-xl-3 mb-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="me-3">
                        <div class="text-white-75 small">History Absensi</div>
                        <div class="text-lg fw-bold">{{ $jumlahHistoryAbsensi }}</div>
                    </div>
                    <i class="feather-xl text-white-50" data-feather="activity"></i>
                </div>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between small">
                <a class="text-white stretched-link" href="{{ route('absensi.history') }}">View All</a>
                <div class="text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>
<!-- Example Charts for Dashboard Demo-->
<div class="row">
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                Diagram Absensi Siswa
                <div class="dropdown no-caret">
                    <button class="btn btn-transparent-dark btn-icon dropdown-toggle" id="areaChartDropdownExample" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-gray-500" data-feather="more-vertical"></i></button>
                    <div class="dropdown-menu dropdown-menu-end animated--fade-in-up" aria-labelledby="areaChartDropdownExample">
                        <a class="dropdown-item" href="#!">Hari ini</a>
                        <a class="dropdown-item" href="#!">Bulan ini</a>
                        <a class="dropdown-item" href="#!">Tahun ini</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-area"><canvas id="ChartAbsensi" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 mb-4">
        <div class="card card-header-actions h-100">
            <div class="card-header">
                Diagram Jumlah Siswa
            </div>
            <div class="card-body">
                <div class="chart-bar"><canvas id="ChartSiswa" width="100%" height="50"></canvas></div>
            </div>
        </div>
    </div>
</div>
<!-- Example DataTable for Dashboard Demo-->
<div class="card mb-4">
    <div class="card-header">Semua Data Siswa</div>
    <div class="card-body">
        <table id="datatablesSimple">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Umur</th>
                    <th>Kelas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($siswa as $index => $sw)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sw->nama_siswa }}</td>
                    <td>{{ $sw->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $sw->tanggal_lahir ? \Carbon\Carbon::parse($sw->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $sw->umur ?? '-' }}</td>
                    <td>Kelas {{ $sw->kelas->nama_kelas }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Area Chart: Absensi Harian
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById("ChartAbsensi").getContext("2d");
        let chartAbsensi;

        function fetchData(range = 'harian') {
            fetch(`/dashboard/chart-absensi?range=${range}`)
                .then(res => res.json())
                .then(data => {
                    if (chartAbsensi) chartAbsensi.destroy();
                    chartAbsensi = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Jumlah Absensi',
                                data: data.data,
                                borderColor: '#4e73df',
                                fill: true,
                                tension: 0.3
                            }]
                        },
                    });
                });
        }

        // default load harian
        fetchData('harian');

        // handle dropdown klik
        document.querySelectorAll(".dropdown-menu a").forEach(item => {
            item.addEventListener("click", function(e) {
                e.preventDefault();
                const range = this.textContent.toLowerCase().replace(" ", "");
                fetchData(range);
            });
        });
    });

    // Bar Chart: Siswa per Kelas
    const ctx2 = document.getElementById("ChartSiswa").getContext('2d');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: @json($labelsKelas),
            datasets: [{
                label: 'Jumlah Siswa',
                data: @json($dataKelas),
                backgroundColor: 'rgba(0, 89, 255, 0.7)',
                borderColor: 'rgba(0, 89, 255, 1)',
                borderWidth: 1
            }]
        }
    });
</script>
@endsection