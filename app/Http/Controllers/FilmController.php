<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Film;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Filme",
 *     description="API-Endpunkte zum Verwalten von Filmen"
 * )
 */
class FilmController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/filme",
     *     summary="Liste aller Filme oder Suche nach Film",
     *     tags={"Filme"},
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         description="Suchbegriff",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Filmen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Film")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query) {
            return response()->json(Film::all());
        }

        return $this->search($query);
    }

    /**
     * @OA\Post(
     *     path="/api/filme",
     *     summary="Erstelle einen neuen Film",
     *     tags={"Filme"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FilmInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Film erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Film")
     *     )
     * )
     */
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

        return response()->json($film, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/filme/{id}",
     *     summary="Zeige einen Film ",
     *     tags={"Filme"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Films",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Film",
     *         @OA\JsonContent(ref="#/components/schemas/Film")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json([
                'message' => 'Film nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($film);
    }

    /**
     * @OA\Put(
     *     path="/api/filme/{id}",
     *     summary="Aktualisiere einen Film",
     *     tags={"Filme"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Films",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FilmInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Film aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Film")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Film nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json([
                'message' => 'Film nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'filmtitel' => 'sometimes|required|string|max:255',
            'bild_url' => 'sometimes|required|url',
        ]);

        $film->fill($validated);
        $film->save();

        return response()->json($film);
    }

    /**
     * @OA\Delete(
     *     path="/api/filme/{id}",
     *     summary="Lösche einen Film",
     *     tags={"Filme"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Films",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Film gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Film nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Film wird noch verwendet",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Film kann nicht gelöscht werden, da er verwendet wird."),
     *             @OA\Property(property="error", type="string", example="foreign_key_constraint_violation")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $film = Film::find($id);

        if (!$film) {
            return response()->json([
                'message' => 'Film nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        try {
            $film->delete();
            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Film kann nicht gelöscht werden, da er von einem oder mehreren Besuchen verwendet wird.',
                    'error' => 'foreign_key_constraint_violation'
                ], 409);
            }

            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen des Films aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search($searchTerm)
    {
        $searchTerm = strtolower(trim($searchTerm));
        $allFilms = Film::all();

        $results = $allFilms
            ->filter(fn($film) => strpos(strtolower($film->filmtitel), $searchTerm) !== false)
            ->map(function ($film) use ($searchTerm) {
                $filmTitle = strtolower($film->filmtitel);
                $distance = levenshtein($filmTitle, $searchTerm);
                if (strpos($filmTitle, $searchTerm) === 0) {
                    $distance -= 5;
                }
                $film->distance = $distance;
                return $film;
            })
            ->sortBy('distance')
            ->take(10)
            ->map(function ($film) {
                unset($film->distance);
                return $film;
            })
            ->values();

        return response()->json($results);
    }
}
