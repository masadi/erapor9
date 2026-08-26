<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Ptk extends Model
{
    use HasUuids;
    protected $table = 'guru';
    protected $primaryKey = 'guru_id';
	protected $guarded = [];
	public function registrasiPtk(): HasMany
    {
        return $this->hasMany(RegistrasiPtk::class, 'guru_id');
    }

    public function sekolah(): BelongsToMany
    {
        return $this->belongsToMany(Sekolah::class, 'registrasi_ptk', 'guru_id', 'sekolah_id')
                    ->withPivot('tahun_ajaran_id')
                    ->withTimestamps();
    }
    public function ptk_keluar(): HasOne
    {
        return $this->hasOne(PtkKeluar::class, 'guru_id', 'guru_id');
    }
}
