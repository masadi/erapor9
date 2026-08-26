<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    public $incrementing = false;
	protected $table = 'ref.mata_pelajaran';
	protected $primaryKey = 'mata_pelajaran_id';
	protected $guarded = [];
	public function pembelajaran(): HasMany
	{
		return $this->hasMany(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function kompetensi_dasar(): HasMany
	{
		return $this->hasMany(KompetensiDasar::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
}
