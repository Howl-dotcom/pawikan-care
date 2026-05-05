<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;
use App\Models\HatchRelease;

class DashboardController extends Controller
{
    public function index()
    {
        // Total nests
        $totalNests = Nest::count();

        // Total eggs recorded
        $totalEggs = Nest::sum('egg_count');

        // Total hatched eggs (only sum if number > 0)
        $hatchedEggs = HatchRelease::whereIn('nest_id', Nest::pluck('id'))->sum('number');

        // Total unhatched eggs
        $unhatchedEggs = $totalEggs - $hatchedEggs;

        // Hatching rate
        $hatchingRate = $totalEggs > 0 ? round(($hatchedEggs / $totalEggs) * 100, 1) : 0;

        return view('dashboard', compact(
            'totalNests',
            'totalEggs',
            'hatchedEggs',
            'unhatchedEggs',
            'hatchingRate'
        ));
    }
}
