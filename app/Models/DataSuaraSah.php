<?php

namespace App\Models;

use App\Models\PasanganCalon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class DataSuaraSah extends Model
{
    use HasUuids;
    protected $keyType = 'string';  // Gunakan string untuk UUID
    public $incrementing = false;


    protected $table = 'data_suara_sah';

    protected $fillable = [
        'pasangan_calon_id',
        'jumlah_suara_sah',
        'jumlah_suara_tidak_sah',
        'total_suara_sah_dan_tidak_sah',
    ];

    /**
     * Relationship with PasanganCalon model.
     */
    public function pasanganCalon()
    {
        return $this->belongsTo(PasanganCalon::class, 'pasangan_calon_id');
    }
}

