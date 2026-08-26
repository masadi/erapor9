<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RombonganBelajar extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'rombongan_belajar';
    protected $primaryKey = 'rombongan_belajar_id';
	protected $guarded = [];
    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function waliKelas()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }

    public function anggotaRombel()
    {
        return $this->hasMany(AnggotaRombel::class, 'rombongan_belajar_id');
    }

    public function pesertaDidik()
    {
        return $this->belongsToMany(PesertaDidik::class, 'anggota_rombel', 'rombongan_belajar_id', 'peserta_didik_id')
                    ->withPivot('id', 'tahun_ajaran_id')
                    ->withTimestamps();
}
}
