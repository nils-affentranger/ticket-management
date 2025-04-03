<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Film::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'filmtitel' => 'required|string|max:255',
            'bild_url' => 'required|url',
        ]);

        $film = new Film();
        $film->filmtitel = $validated['filmtitel'];
        $film->bild_url = $validated['bild_url'];
        $film->save();

        return response()->json($film, 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     */
    public function show(Film $film)
    {
        return response()->json($film);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Film $film)
    {
        $validated = $request->validate([
            'filmtitel' => 'sometimes|required|string|max:255',
            'bild_url' => 'sometimes|required|url',
        ]);

        $film->fill($validated);
        $film->save();

        return response()->json($film);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Film $film)
    {
        $film->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
