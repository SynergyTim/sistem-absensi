<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswa = Siswa::with('kelas')->get();
        $kelas = Kelas::all();
        return view('siswa.index', compact('siswa', 'kelas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa->update($request->all());

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }

    // import
    public function import()
    {
        $kelas = Kelas::all();
        return view('siswa.import', compact('kelas'));
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file, 0, ","); // delimiter koma

        $successCount = 0;
        $failCount = 0;

        while (($row = fgetcsv($file, 0, ",")) !== false) {
            if (count($row) < 2) {
                $failCount++;
                continue;
            }

            try {
                Siswa::create([
                    'nama_siswa' => trim($row[0]),
                    'jenis_kelamin' => strtoupper(trim($row[1])),
                    'kelas_id' => $request->kelas_id,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                logger('Gagal simpan: ' . $e->getMessage());
                $failCount++;
            }
        }

        fclose($file);

        return redirect()->route('siswa.index')->with('success', "Import selesai. Berhasil: $successCount, Gagal: $failCount");
    }
}
