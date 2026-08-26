<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    public $incrementing = false;
	protected $table = 'ref.tahun_ajaran';
	protected $primaryKey = 'tahun_ajaran_id';
	protected $guarded = [];
    protected static function booted()
    {
        // Otomatis buat Laratrust Team saat Semester baru dibuat
        static::created(function ($data) {
            Team::create([
                'name' => $data->tahun_ajaran_id, // Menyimpan semester_id ke kolom name
                'display_name' => $data->nama,
                'description' => 'Akses Untuk TA. ' . $data->nama,
            ]);
        });
    }
    /**
     * Relasi ke Laratrust Team berdasarkan kolom name
     */
    public function team()
    {
        return $this->hasOne(Team::class, 'name', 'tahun_ajaran_id');
    }
}
