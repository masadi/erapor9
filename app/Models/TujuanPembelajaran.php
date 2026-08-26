<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TujuanPembelajaran extends Model
{
    use HasUuids;
    protected $table = 'tujuan_pembelajaran';
	protected $primaryKey = 'tp_id';
	protected $guarded = [];
	
    public function cp(): BelongsTo
    {
        return $this->belongsTo(CapaianPembelajaran::class, 'cp_id', 'cp_id');
    }
    public function kd(): BelongsTo
    {
        return $this->belongsTo(KompetensiDasar::class, 'kd_id', 'kompetensi_dasar_id');
    }
    public function tp_mapel(): HasManyThrough
    {
        return $this->hasManyThrough(
            Pembelajaran::class,
            TpMapel::class,
            'tp_id',
            'pembelajaran_id',
            'tp_id',
            'pembelajaran_id'
        );
    }
    public function tp_pkl(): HasOne
    {
        return $this->hasOne(TpPkl::class, 'tp_id', 'tp_id');
    }
}
