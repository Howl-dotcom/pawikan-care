<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Incubation extends Model
{
    use HasFactory;

    // Allow these fields for mass assignment
    protected $fillable = [
        'nest_id',      // foreign key for the nest
        'status',       // incubation status
        'notes',        // observation notes
        'observed_at',  // if you have a date field for the observation
    ];
}
