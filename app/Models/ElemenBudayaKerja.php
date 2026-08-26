<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ElemenBudayaKerja extends Model
{
    public $incrementing = false;
	protected $table = 'ref.elemen_budaya_kerja';
	protected $primaryKey = 'elemen_id';
	protected $guarded = [];
    public function budaya_kerja(): BelongsTo
    {
        return $this->belongsTo(BudayaKerja::class, 'budaya_kerja_id', 'budaya_kerja_id');
    }
    public function nilai_budaya_kerja(): HasOne
    {
        return $this->hasOne(NilaiBudayaKerja::class, 'elemen_id', 'elemen_id');
    }
}
