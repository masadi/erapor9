<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StatusPenilaian extends Model
{
    use HasUuids;
    protected $table = 'status_penilaian';
	protected $primaryKey = 'status_penilaian_id';
	protected $guarded = [];
}
