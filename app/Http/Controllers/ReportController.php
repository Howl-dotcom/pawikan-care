<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function generatePdf(Request $request)
    {
        $nest = Nest::with(['threats','incubations','hatchReleases'])
            ->findOrFail($request->nest_id);

        $pdf = Pdf::loadView('reports.nest', compact('nest'));

        // Force download
        return $pdf->download('nest_report_'.$nest->id.'.pdf');
    }
}
