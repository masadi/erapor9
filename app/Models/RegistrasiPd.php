<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrasiPd extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'registrasi_pd';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sekolah_id',
        'peserta_didik_id',
        'jenis_pendaftaran',
        'tanggal_keluar',
        'jenis_keluar_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function pesertaDidik()
    {
        return $this->belongsTo(PesertaDidik::class, 'peserta_didik_id');
    }

    public function jenisKeluar()
    {
        return $this->belongsTo(JenisKeluar::class, 'jenis_keluar_id');
    }
}