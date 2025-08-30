<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;
use Carbon\Carbon;

class AbsensiBulananExport implements FromCollection, WithHeadings, Responsable
{
    use \Maatwebsite\Excel\Concerns\Exportable;

    private $bulan;
    private $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = Absensi::with('siswa')
            ->whereMonth('tanggal', $this->bulan)
            ->whereYear('tanggal', $this->tahun)
            ->get()
            ->groupBy('siswa_id');

        return $data->map(function ($absensi) {
            $siswa = $absensi->first()->siswa;

            $tanggalLahir = $siswa->tanggal_lahir
                ? Carbon::parse($siswa->tanggal_lahir)->format('d-m-Y')
                : '-';

            $umur = $siswa->tanggal_lahir
                ? Carbon::parse($siswa->tanggal_lahir)->age . ' tahun'
                : '-';

            return [
                'Nama Siswa'     => $siswa->nama_siswa,
                'Tanggal Lahir'  => $tanggalLahir,
                'Umur'           => $umur,
                'Hadir'          => $absensi->sum('hadir'),
                'Izin'           => $absensi->sum('izin'),
                'Alpa'           => $absensi->sum('alpa'),
            ];
        })->values();
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Tanggal Lahir',
            'Umur',
            'Hadir',
            'Izin',
            'Alpa'
        ];
    }
}
