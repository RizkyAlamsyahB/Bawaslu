<?php

namespace App\Models;

use App\Models\User;
use App\Models\Kelurahan;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tps extends Model
{
    // Set primary key type to string (for UUID)
    protected $keyType = 'string';

    // Disable auto-incrementing
    public $incrementing = false;

    // Automatically generate UUID when creating a new Tps
    protected static function booted()
    {
        static::creating(function ($tps) {
            if (empty($tps->id)) {
                $tps->id = (string) Str::uuid(); // Generate UUID
            }
        });
    }

    // Mass assignment protection
    protected $fillable = ['no_tps', 'kelurahan_id'];

    // Relation with Kelurahan
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }


}
