<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $siswa = collect();

        if (request('kelas_id')) {
            $siswa = Siswa::where('kelas_id', request('kelas_id'))->get();
        }

        return view('absensi.index', compact('kelas', 'siswa'));
    }

    public function getSiswa($kelas_id)
    {
        $siswa = Siswa::where('kelas_id', $kelas_id)->get();

        return response()->json([
            'data' => $siswa,
            'kelas' => Kelas::find($kelas_id)
        ]);
    }
    public function getAbsensi($kelas_id, $tanggal)
    {
        $siswa = Siswa::where('kelas_id', $kelas_id)->get();
        $kelas = Kelas::find($kelas_id);

        $absensi = Absensi::where('kelas_id', $kelas_id)
            ->where('tanggal', $tanggal)
            ->get()
            ->keyBy('siswa_id');

        return response()->json([
            'siswa' => $siswa,
            'kelas' => $kelas,
            'absensi' => $absensi,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kelas_id' => 'required|exists:kelas,id',
            'absen' => 'required|array',
        ]);

        foreach ($request->absen as $siswa_id => $status) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'kelas_id' => $request->kelas_id,
                    'tanggal' => $request->tanggal,
                ],
                [
                    'hadir' => $status === 'hadir' ? 1 : 0,
                    'izin' => $status === 'izin' ? 1 : 0,
                    'alpa' => $status === 'alpa' ? 1 : 0,
                ]
            );
        }

        return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
    }

    public function chartKelas(Request $request, $kelas_id)
    {
        $hariIndo = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $hariMapping = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu'
        ];

        $startDate = now()->subDays(6)->startOfDay();
        $endDate = now()->endOfDay();

        $data = Absensi::where('kelas_id', $kelas_id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($item) use ($hariMapping) {
                $dayName = Carbon::parse($item->tanggal)->format('l'); // Monday, etc.
                return $hariMapping[$dayName] ?? $dayName;
            });

        $hadir = [];
        $tidakHadir = [];

        foreach ($hariIndo as $hari) {
            $hariData = $data[$hari] ?? collect();
            $hadir[] = $hariData->sum('hadir');
            $tidakHadir[] = $hariData->sum('izin') + $hariData->sum('alpa');
        }

        return response()->json([
            'labels' => $hariIndo,
            'hadir' => $hadir,
            'tidak_hadir' => $tidakHadir,
        ]);
    }

    public function absenHariIni(Request $request, $kelas_id)
    {
        $tanggal = now()->format('Y-m-d');
        $siswaKelas = Siswa::where('kelas_id', $kelas_id)->get();

        foreach ($siswaKelas as $siswa) {
            Absensi::updateOrCreate(
                [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelas_id,
                    'tanggal' => $tanggal
                ],
                [
                    'hadir' => 1,
                    'alpa' => 0,
                    'izin' => 0
                ]
            );
        }

        return back()->with('success', 'Absensi hari ini berhasil diperbarui.');
    }

    public function history(Request $request)
    {
        $kelas = Kelas::all();
        $query = Absensi::with(['siswa.kelas']);

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->tanggal) {
            $query->where('tanggal', $request->tanggal);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        return view('absensi.history', compact('kelas', 'data'));
    }
    public function export()
    {
        return Excel::download(new AbsensiExport, 'rekap-absensi.xlsx');
    }
    public function exportPdf(Request $request)
    {
        $query = Absensi::with(['siswa', 'kelas']);

        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->tanggal) {
            $query->where('tanggal', $request->tanggal);
        }

        $data = $query->orderBy('tanggal', 'desc')->get();

        $pdf = Pdf::loadView('absensi.export_pdf', ['data' => $data]);
        return $pdf->download('rekap-absensi.pdf');
    }
}
