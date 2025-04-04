<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Kino;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Kinos",
 *     description="API-Endpunkte zum Verwalten von Kinos"
 * )
 */
class KinoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/kinos",
     *     summary="Liste aller Kinos oder Suche nach Kino (max. 100)",
     *     description="Gibt eine Liste von Kinos zurück, optional gefiltert durch einen Suchbegriff. Maximal 100 Ergebnisse werden zurückgegeben.",
     *     tags={"Kinos"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Suchbegriff",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Anzahl der zurückzugebenden Ergebnisse (1-100)",
     *         required=false,
     *         @OA\Schema(type="integer", default=10, minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Kinos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Kino")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        // Implement validation to match the documentation
        $validated = $request->validate([
            'query' => 'sometimes|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:100'
        ]);

        $query = $validated['query'] ?? null;
        $limit = $validated['limit'] ?? 10;

        $kinos = Kino::all()->toArray();
        if (!$query) {
            return response()->json(array_slice($kinos, 0, $limit));
        }
        return $this->search($query, $limit, $kinos);
    }

    /**
     * @OA\Post(
     *     path="/api/kinos",
     *     summary="Erstelle ein neues Kino",
     *     tags={"Kinos"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/KinoInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kino erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Kino")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'ort' => 'required|string|max:255'
        ]);

        $kino = new Kino();
        $kino->name = $validated['name'];
        $kino->ort = $validated['ort'];
        $kino->save();

        return response()->json($kino, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/kinos/{id}",
     *     summary="Zeige ein Kino",
     *     tags={"Kinos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinos",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Kino",
     *         @OA\JsonContent(ref="#/components/schemas/Kino")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kino nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kino nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $kino = Kino::find($id);

        if (!$kino) {
            return response()->json([
                'message' => 'Kino nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($kino);
    }

    /**
     * @OA\Put(
     *     path="/api/kinos/{id}",
     *     summary="Aktualisiere ein Kino",
     *     tags={"Kinos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinos",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/KinoInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kino aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Kino")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kino nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kino nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $kino = Kino::find($id);

        if (!$kino) {
            return response()->json([
                'message' => 'Kino nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'ort' => 'sometimes|required|string|max:255',
        ]);

        $kino->fill($validated);
        $kino->save();

        return response()->json($kino);
    }

    /**
     * @OA\Delete(
     *     path="/api/kinos/{id}",
     *     summary="Lösche ein Kino",
     *     tags={"Kinos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinos",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Kino gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Kino nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kino nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Kino wird noch verwendet",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Kino kann nicht gelöscht werden, da es von einem oder mehreren Sälen verwendet wird."),
     *             @OA\Property(property="error", type="string", example="foreign_key_constraint_violation")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $kino = Kino::find($id);

        if (!$kino) {
            return response()->json([
                'message' => 'Kino nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        // Überprüfen, ob das Kino Säle enthält, die von einem oder mehreren Besuchen verwendet werden.
        $saeleWithBesuche = $kino->saele()->whereHas('besuche')->count();

        if ($saeleWithBesuche > 0) {
            return response()->json([
                'message' => 'Kino kann nicht gelöscht werden, da Säle dieses Kinos noch von Besuchen verwendet werden.',
                'error' => 'foreign_key_constraint_violation'
            ], 409); // 409 Conflict
        }

        // Wenn das Kino Säle enthält, die nicht verwendet werden, werden diese Säle zuerst gelöscht.
        try {
            // Alle Säle vom Kino löschen
            $kino->saele()->delete();

            $kino->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen des Kinos aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search($suchBegriff, $limit, $allKinos)
    {
        $suchBegriff = strtolower(trim($suchBegriff));
        $resultate = [];

        foreach ($allKinos as $kino) {
            $kinoName = strtolower($kino['name']);

            if (str_contains($kinoName, $suchBegriff)) {
                $distance = levenshtein($kinoName, $suchBegriff);

                // Kinos, die mit dem Suchbegriff anfangen, sollen weiter oben stehen.
                if (str_starts_with($kinoName, $suchBegriff)) {
                    $distance -= 5;
                }

                $kino['distance'] = $distance;
                $resultate[] = $kino;
            }
        }

        // Resultate nach Distanz sortieren
        usort($resultate, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        $resultate = array_slice($resultate, 0, $limit ?? 10);

        foreach ($resultate as &$kino) {
            unset($kino['distance']);
        }

        return response()->json($resultate);
    }
}
