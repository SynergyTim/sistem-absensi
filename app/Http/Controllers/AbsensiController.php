<?php

namespace App\Http\Controllers;

use App\Exports\AbsensiExport;
use App\Models\Absensi;
use App\Models\Kelas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AbsensiController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        return view('absensi.index', compact('kelas'));
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
