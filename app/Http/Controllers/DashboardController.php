<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;
use App\Models\HatchRelease;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNests = Nest::count();
        $totalEggs = Nest::sum('egg_count');
        $hatchedEggs = HatchRelease::whereIn('nest_id', Nest::pluck('id'))->sum('number');
        $unhatchedEggs = $totalEggs - $hatchedEggs;
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