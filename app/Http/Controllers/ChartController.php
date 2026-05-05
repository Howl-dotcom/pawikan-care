<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;

class ChartController extends Controller
{
    public function getYears()
    {
        return Nest::selectRaw('EXTRACT(YEAR FROM nest_date)::integer as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year');
    }

    public function getData(Request $request)
    {
        $year = $request->year;
        $month = $request->month;

        $query = Nest::query();

        if ($year) {
            $query->whereRaw('EXTRACT(YEAR FROM nest_date) = ?', [$year]);
        }

        if ($month) {
            $query->whereRaw('EXTRACT(MONTH FROM nest_date) = ?', [$month]);
        }

        return response()->json([
            'threat' => [
                'labels' => ['Threat Reports'],
                'values' => [$query->whereNotNull('threat_date')->count()],
            ],
            'eggs' => [
                'labels' => ['Egg Count'],
                'values' => [$query->sum('egg_count')],
            ],
            'hatch' => [
                'labels' => ['Hatched', 'Unhatched'],
                'hatched' => [$query->sum('egg_hatched')],
                'unhatched' => [$query->sum('egg_unhatched')],
            ],
            'nest' => [
                'labels' => ['Nest Count'],
                'values' => [$query->count()],
            ],
            'pie' => [
                'hatched' => $query->sum('egg_hatched'),
                'unhatched' => $query->sum('egg_unhatched'),
            ]
        ]);
    }
}