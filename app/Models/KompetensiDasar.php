<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class KompetensiDasar extends Model
{
    use SoftDeletes;
    public $incrementing = false;
	public $keyType = 'string';
	protected $table = 'ref.kompetensi_dasar';
	protected $primaryKey = 'kompetensi_dasar_id';
	protected $guarded = [];
	public function mata_pelajaran(): HasOne
    {
		return $this->hasOne(MataPelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
	public function pembelajaran(): HasOne
    {
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
}
