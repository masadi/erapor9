<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Semester extends Model
{
    public $incrementing = false;
	protected $table = 'ref.semester';
	protected $primaryKey = 'semester_id';
	protected $guarded = [];
	public function tahun_ajaran(): HasOne
	{
		return $this->hasOne(TahunAjaran::class, 'tahun_ajaran_id', 'tahun_ajaran_id');
	}
}
