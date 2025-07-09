<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $jumlahSiswa = Siswa::count();
        $jumlahKelas = Kelas::count();
        $jumlahAbsensiHariIni = Absensi::whereDate('tanggal', today())->count();
        $jumlahHistoryAbsensi = Absensi::count();

        $siswa = Siswa::with('kelas')->get();

        // absensi harian diagram
        $absensiHarian = Absensi::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as total')
            ->whereDate('tanggal', '>=', now()->subDays(7))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labelsHarian = $absensiHarian->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray();
        $dataHarian = $absensiHarian->pluck('total')->toArray();

        // diagram data kelas
        $dataPerKelas = Siswa::selectRaw('kelas_id, COUNT(*) as total')
            ->groupBy('kelas_id')
            ->with('kelas')
            ->get()
            ->map(function ($item) {
                return [
                    'label' => 'Kelas ' . $item->kelas->nama_kelas,
                    'total' => $item->total,
                ];
            });

        $labelsKelas = $dataPerKelas->pluck('label');
        $dataKelas = $dataPerKelas->pluck('total');

        return view('dashboard.index', compact(
            'jumlahSiswa',
            'jumlahKelas',
            'jumlahAbsensiHariIni',
            'jumlahHistoryAbsensi',
            'siswa',
            'labelsHarian',
            'labelsKelas',
            'dataHarian',
            'dataKelas'
        ));
    }
    // filtering diagram absensi
    public function getChartAbsensi(Request $request)
    {
        $range = $request->query('range', 'harian');
        $query = Absensi::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as total');

        if ($range === 'harian') {
            $query->whereDate('tanggal', '>=', now()->subDays(7));
        } elseif ($range === 'bulanan') {
            $query->whereMonth('tanggal', now()->month);
        } elseif ($range === 'tahunan') {
            $query->whereYear('tanggal', now()->year);
        }

        $data = $query->groupBy('tanggal')->orderBy('tanggal')->get();

        $labels = $data->pluck('tanggal')->map(function ($tgl) {
            return \Carbon\Carbon::parse($tgl)->format('d M');
        });

        $jumlah = $data->pluck('total');

        return response()->json([
            'labels' => $labels,
            'data' => $jumlah,
        ]);
    }
}
