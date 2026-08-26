<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaRombel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'anggota_rombel';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'rombongan_belajar_id',
        'peserta_didik_id',
        'tahun_ajaran_id',
    ];

    public function rombonganBelajar()
    {
        return $this->belongsTo(RombonganBelajar::class, 'rombongan_belajar_id');
    }

    public function pesertaDidik()
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }
}