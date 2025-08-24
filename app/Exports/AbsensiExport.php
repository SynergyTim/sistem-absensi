<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Absensi::with('siswa', 'kelas')->get()->map(function ($row) {
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
        return [
            'Tanggal Absensi',
            'Nama Siswa',
            'Tanggal Lahir',
            'Umur',
            'Kelas',
            'Hadir',
            'Izin',
            'Alpa'
        ];
    }
}
