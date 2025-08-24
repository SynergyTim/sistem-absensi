<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswa';

    protected $fillable = ['nama_siswa', 'jenis_kelamin', 'kelas_id','tanggal_lahir'];
    protected $appends = ['umur'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }
    public function getUmurAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        $lahir = Carbon::parse($this->tanggal_lahir);
        $sekarang = Carbon::now();

        $tahun = $lahir->diffInYears($sekarang);
        $bulan = $lahir->diffInMonths($sekarang) % 12;

        return "$tahun tahun $bulan bulan";
    }
}
