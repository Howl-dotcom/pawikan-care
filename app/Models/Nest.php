<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nest extends Model
{
    // Combine all fillable fields in one array
    protected $fillable = [
        'date',
        'species',
        'photo_path',
        'notes',
        'location',
        'egg_count',
        'discovery_date',
    ];

    // Relationships
    public function threats()
    {
        return $this->hasMany(Threat::class);
    }

    public function incubations()
    {
        return $this->hasMany(Incubation::class);
    }

    public function hatchReleases()
    {
        return $this->hasMany(HatchRelease::class);
    }
}
