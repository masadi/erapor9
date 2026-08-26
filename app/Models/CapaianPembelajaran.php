<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CapaianPembelajaran extends Model
{
    public $incrementing = false;
	//public $timestamps = false;
	protected $table = 'ref.capaian_pembelajaran';
	protected $primaryKey = 'cp_id';
	protected $guarded = [];
    public function pembelajaran(): HasOne
    {
		return $this->hasOne(Pembelajaran::class, 'mata_pelajaran_id', 'mata_pelajaran_id');
	}
}
