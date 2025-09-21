<?php

namespace App\Http\Controllers;

use App\Models\Incubation;
use App\Models\Nest;
use Illuminate\Http\Request;

class IncubationController extends Controller
{
    public function create()
    {
        $nests = Nest::all();
        return view('incubation.create', compact('nests'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nest_id' => 'required|exists:nests,id',
            'status'  => 'required|string',
            'notes'   => 'nullable|string'
        ]);

        Incubation::create($data);

        return redirect()->route('dashboard')->with('success','Incubation data saved.');
    }
}