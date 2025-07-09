<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AbsensiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Absensi::with('siswa', 'kelas')->get()->map(function ($row) {
            return [
                'Tanggal' => $row->tanggal,
                'Nama Siswa' => $row->siswa->nama_siswa,
                'Kelas' => $row->kelas->nama_kelas,
                'Hadir' => $row->hadir ? '✓' : '',
                'Izin' => $row->izin ? '✓' : '',
                'Alpa' => $row->alpa ? '✓' : '',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Siswa', 'Kelas', 'Hadir', 'Izin', 'Alpa'];
    }
}
