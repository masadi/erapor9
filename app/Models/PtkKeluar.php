<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PtkKeluar extends Model
{
    use HasUuids;
    protected $table = 'ptk_keluar';
	protected $primaryKey = 'ptk_keluar_id';
	protected $guarded = [];
}
