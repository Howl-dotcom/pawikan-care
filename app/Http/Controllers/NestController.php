<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nest;

class NestController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'date' => 'required|date',
        'species' => 'required|string',
        'egg_count' => 'required|integer|min:1',
        'photo' => 'nullable|image|max:2048',
        'notes' => 'nullable|string',
    ]);

    $path = null;
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('nest_photos', 'public');
    }

    Nest::create([
        'date'       => $validated['date'],
        'species'    => $validated['species'],
        'egg_count'  => $validated['egg_count'], // add this
        'photo_path' => $path,
        'notes'      => $validated['notes'] ?? null,
    ]);

    return redirect()->back()->with('success', 'Nest saved successfully.');
}


    public function index()
    {
        $nests = Nest::latest()->get();
        return view('nests.index', compact('nests'));
    }
}

