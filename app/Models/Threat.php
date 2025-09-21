<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Threat extends Model
{
    use HasFactory;

    protected $fillable = [
        'nest_id',
        'threat_type',
        'photo_path',
        'notes',
    ];

    public function nest()
    {
        return $this->belongsTo(Nest::class);
    }
}