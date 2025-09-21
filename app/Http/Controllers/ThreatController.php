<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;
use App\Models\Threat;

class ThreatController extends Controller
{
    // Show the create form
    public function create()
{
    $nests = Nest::all();
    $nestCount = Nest::count(); // Add this

    return view('threats.create', compact('nests', 'nestCount'));
}

    // Store the submitted threat report
    public function store(Request $request)
    {
        $data = $request->validate([
            'nest_id'     => 'required|exists:nests,id',
            'threat_type' => 'required|string',
            'photo'       => 'nullable|image',
            'notes'       => 'nullable|string',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('threat_photos', 'public');
        }

        Threat::create($data);

        return redirect()->route('dashboard')->with('success', 'Threat report saved.');
    }
}
