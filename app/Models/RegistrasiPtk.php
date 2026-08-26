<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrasiPtk extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'registrasi_ptk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sekolah_id',
        'ptk_id',
        'tahun_ajaran_id',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function ptk()
    {
        return $this->belongsTo(Ptk::class, 'ptk_id');
    }
}