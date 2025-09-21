<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HatchRelease extends Model
{
    use HasFactory;

    protected $fillable = [
        'nest_id',
        'hatchdate',
        'number',
        'survival_rate',
    ];
}
