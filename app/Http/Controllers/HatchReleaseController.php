<?php

namespace App\Http\Controllers;

use App\Models\HatchRelease;
use Illuminate\Http\Request;

class HatchReleaseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nest_id' => 'required|exists:nests,id',
            'hatchdate' => 'required|date',
            'number' => 'required|integer|min:1',
            'survival_rate' => 'nullable|string',
        ]);

        HatchRelease::create($validated);

        return redirect()->route('dashboard')->with('success', 'Hatchling release recorded.');
    }
}
