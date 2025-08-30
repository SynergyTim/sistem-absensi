<?php

namespace App\Exports;

use App\Models\Absensi;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    protected $bulan;
    protected $tahun;
    protected $kelas_id;

    public function __construct($bulan = null, $tahun = null, $kelas_id = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun ?? now()->year;
        $this->kelas_id = $kelas_id;
    }

    public function collection()
    {
        $query = Absensi::with('siswa', 'kelas');

        if ($this->kelas_id) {
            $query->where('kelas_id', $this->kelas_id);
        }

        if ($this->bulan) {
            $query->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
        }

        $data = $query->get();

        if ($this->bulan) {
            $bulan = $this->bulan; // simpan ke variabel agar bisa dipakai di closure

            $data = $data->groupBy('siswa_id');

            return $data->map(function ($absensi) use ($bulan) {
                $siswa = $absensi->first()->siswa;

                $tanggalLahir = $siswa->tanggal_lahir
                    ? Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y')
                    : '-';
                $umur = $siswa->tanggal_lahir
                    ? Carbon::parse($siswa->tanggal_lahir)->age . ' tahun'
                    : '-';

                return [
                    'Bulan' => Carbon::create()->month($bulan)->translatedFormat('F'),
                    'Nama Siswa' => $siswa->nama_siswa,
                    'Tanggal Lahir' => $tanggalLahir,
                    'Umur' => $umur,
                    'Hadir' => $absensi->sum('hadir'),
                    'Izin' => $absensi->sum('izin'),
                    'Alpa' => $absensi->sum('alpa'),
                ];
            })->values();
        }

        // Default harian
        return $data->map(function ($row) {
            $tanggalLahir = $row->siswa->tanggal_lahir
                ? Carbon::parse($row->siswa->tanggal_lahir)->format('d-m-Y')
                : '-';
            $umur = $row->siswa->tanggal_lahir
                ? Carbon::parse($row->siswa->tanggal_lahir)->age . ' tahun'
                : '-';

            return [
                'Tanggal Absensi' => Carbon::parse($row->tanggal)->format('d-m-Y'),
                'Nama Siswa' => $row->siswa->nama_siswa,
                'Tanggal Lahir' => $tanggalLahir,
                'Umur' => $umur,
                'Kelas' => $row->kelas->nama_kelas,
                'Hadir' => $row->hadir ? '✓' : '',
                'Izin' => $row->izin ? '✓' : '',
                'Alpa' => $row->alpa ? '✓' : '',
            ];
        });
    }

    public function headings(): array
    {
        if ($this->bulan) {
            return [
                'Bulan',
                'Nama Siswa',
                'Tanggal Lahir',
                'Umur',
                'Hadir',
                'Izin',
                'Alpa',
            ];
        }

        return [
            'Tanggal Absensi',
            'Nama Siswa',
            'Tanggal Lahir',
            'Umur',
            'Kelas',
            'Hadir',
            'Izin',
            'Alpa',
        ];
    }
}
