<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Sekolah extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'sekolah';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'sekolah_id';
    protected $guarded = [];
    public function kepala_sekolah(): HasOneThrough
    {
		return $this->hasOneThrough(
            Ptk::class,
            Kasek::class,
            'sekolah_id', // Foreign key on the cars table...
            'guru_id', // Foreign key on the owners table...
            'sekolah_id', // Local key on the mechanics table...
            'guru_id' // Local key on the cars table...
        );
	}
    public function ptk()
    {
        return $this->hasMany(Ptk::class, 'sekolah_id');
    }

    public function rombonganBelajar()
    {
        return $this->hasMany(RombonganBelajar::class, 'sekolah_id');
    }
    public function bentukPendidikan()
    {
        return $this->belongsTo(BentukPendidikan::class, 'bentuk_pendidikan_id');
    }
    public function registrasiPd()
    {
        return $this->hasMany(RegistrasiPd::class, 'sekolah_id');
    }

    public function pesertaDidik()
    {
        return $this->belongsToMany(PesertaDidik::class, 'registrasi_pd', 'sekolah_id', 'peserta_didik_id')
                    ->withPivot('id', 'jenis_pendaftaran', 'tanggal_keluar', 'jenis_keluar_id')
                    ->withTimestamps();
    }
    public function pd_aktif(): HasManyThrough
    {
		return $this->hasManyThrough(
            AnggotaRombel::class,
            PesertaDidik::class,
            'sekolah_id',
            'peserta_didik_id',
            'sekolah_id',
            'peserta_didik_id'
        );
	}
    public function nilai_akhir(): HasMany
    {
		return $this->hasMany(NilaiAkhir::class, 'sekolah_id', 'sekolah_id');
	}
    public function cp(): HasManyThrough
    {
		return $this->hasManyThrough(
            CapaianPembelajaran::class,
            Pembelajaran::class,
            'sekolah_id',
            'mata_pelajaran_id',
            'sekolah_id',
            'mata_pelajaran_id'
        );
	}
    public function nilai_projek(){
		return $this->hasMany(CatatanBudayaKerja::class, 'sekolah_id', 'sekolah_id');
	}
}
