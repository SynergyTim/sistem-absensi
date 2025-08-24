@extends('layouts')

@section('title', 'Kelas')
@section('content')
<div class="container">
    <!-- Grafik Absensi -->
    <div class="row">
        <div class="col-md-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Grafik Kehadiran kelas {{ $kelas->nama_kelas }}</span>
                    <!-- <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownFilter" data-bs-toggle="dropdown" aria-expanded="false">
                            Filter
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownFilter">
                            <li><a class="dropdown-item" href="?filter=minggu">Minggu Ini</a></li>
                            <li><a class="dropdown-item" href="?filter=bulan">Bulan Ini</a></li>
                            <li><a class="dropdown-item" href="?filter=tahun">Tahun Ini</a></li>
                        </ul>
                    </div> -->
                </div>

                <div class="card-body" style="height: 300px;">
                    <canvas id="chartAbsensiKelas"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">Grafik Jenis Kelamin Siswa</div>
                <div class="card-body d-flex justify-content-center" style="height: 300px;">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabel Siswa -->
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Daftar Siswa Kelas {{ $kelas->nama_kelas }}</h5>
            <a href="{{ route('absensi.index')}}" class="btn btn-primary">Absensi <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lhir</th>
                        <th>Umur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswa as $key => $s)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $s->nama_siswa }}</td>
                        <td>{{ $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>{{ $s->tanggal_lahir ? \Carbon\Carbon::parse($s->tanggal_lahir)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $s->umur ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">Tidak ada siswa di kelas ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    fetch("{{ route('siswa-kelas.chart', $kelas->id) }}")
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById('chartAbsensiKelas').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    datasets: [{
                            label: 'Hadir',
                            data: data.hadir,
                            backgroundColor: 'rgba(0, 89, 255, 0.7)'
                        },
                        {
                            label: 'Tidak Hadir',
                            data: data.tidak_hadir,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            min: 0,
                            max: 30,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + context.formattedValue + ' siswa';
                                }
                            }
                        }
                    }
                }
            });
        });
</script>
<script>
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{
                    {
                        $laki
                    }
                }, {
                    {
                        $perempuan
                    }
                }],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.formattedValue + ' siswa';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection