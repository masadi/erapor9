<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesertaDidik extends Model
{
    use HasUuids;
    protected $table = 'peserta_didik';
	protected $guarded = [];
    /**
     * Relasi ke Tabel Pivot AnggotaRombel
     */
    public function anggotaRombel()
    {
        return $this->hasMany(AnggotaRombel::class, 'peserta_didik_id');
    }

    /**
     * Relasi Banyak-ke-Banyak ke RombonganBelajar
     */
    public function rombonganBelajar()
    {
        return $this->belongsToMany(RombonganBelajar::class, 'anggota_rombel', 'peserta_didik_id', 'rombongan_belajar_id')
                    ->withPivot('id', 'tahun_ajaran_id')
                    ->withTimestamps();
    }
    public function registrasiPd()
    {
        return $this->hasOne(RegistrasiPd::class, 'peserta_didik_id');
    }

    public function sekolah()
    {
        return $this->belongsToMany(Sekolah::class, 'registrasi_pd', 'peserta_didik_id', 'sekolah_id')
                    ->withPivot('id', 'jenis_pendaftaran', 'tanggal_keluar', 'jenis_keluar_id')
                    ->withTimestamps();
    }
}
