<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\PasanganCalon;
use App\Models\TipePemilihan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataSuaraPaslon extends Model
{
    use HasFactory;

    use HasUuids;
    protected $keyType = 'string';  // Gunakan string untuk UUID
    public $incrementing = false;

    protected $table = 'data_suara_paslon';

    protected $fillable = [
        'pasangan_calon_id',
        'tipe_pemilihan_id',
        'jumlah_suara',
        'user_id',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tipePemilihan()
    {
        return $this->belongsTo(TipePemilihan::class, 'tipe_pemilihan_id');
    }

    public function pasanganCalon()
    {
        return $this->belongsTo(PasanganCalon::class, 'pasangan_calon_id');
    }
}
