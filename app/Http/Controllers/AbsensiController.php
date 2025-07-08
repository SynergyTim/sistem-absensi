<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;

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
}
