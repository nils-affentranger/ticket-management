<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Sprache;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Sprachen",
 *     description="API-Endpunkte zum Verwalten von Sprachen"
 * )
 */
class SpracheController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/sprachen",
     *     summary="Liste aller Sprachen",
     *     tags={"Sprachen"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Suchbegriff zum Filtern der Sprachen",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximale Anzahl der zurückgegebenen Ergebnisse",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Sprachen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Sprache")
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'query' => 'sometimes|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:100'
        ]);

        $query = $validated['query'] ?? null;
        $limit = $validated['limit'] ?? null;

        $sprachen = Sprache::all()->toArray();

        if ($query) {
            return $this->search($query, $limit, $sprachen);
        }

        if ($limit) {
            $sprachen = array_slice($sprachen, 0, $limit);
        }

        return response()->json($sprachen);
    }

    /**
     * @OA\Post(
     *     path="/api/sprachen",
     *     summary="Erstelle eine neue Sprache",
     *     tags={"Sprachen"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SpracheInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sprache erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Sprache")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sprache = new Sprache();
        $sprache->name = $validated['name'];
        $sprache->save();

        return response()->json($sprache, 201); // 201 Created
    }

    /**
     * @OA\Get(
     *     path="/api/sprachen/{id}",
     *     summary="Zeige eine Sprache",
     *     tags={"Sprachen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID der Sprache",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Sprache",
     *         @OA\JsonContent(ref="#/components/schemas/Sprache")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sprache nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sprache nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $sprache = Sprache::find($id);

        if (!$sprache) {
            return response()->json([
                'message' => 'Sprache nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($sprache);
    }

    /**
     * @OA\Put(
     *     path="/api/sprachen/{id}",
     *     summary="Aktualisiere eine Sprache",
     *     tags={"Sprachen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID der Sprache",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SpracheInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sprache aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Sprache")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Sprache nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sprache nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $sprache = Sprache::find($id);

        if (!$sprache) {
            return response()->json([
                'message' => 'Sprache nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        $sprache->fill($validated);
        $sprache->save();

        return response()->json($sprache);
    }

    /**
     * @OA\Delete(
     *     path="/api/sprachen/{id}",
     *     summary="Lösche eine Sprache",
     *     tags={"Sprachen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID der Sprache",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Sprache gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Sprache nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sprache nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Sprache wird noch verwendet",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sprache kann nicht gelöscht werden, da sie von einem oder mehreren Besuchen verwendet wird."),
     *             @OA\Property(property="error", type="string", example="foreign_key_constraint_violation")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $sprache = Sprache::find($id);

        if (!$sprache) {
            return response()->json([
                'message' => 'Sprache nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        try {
            $sprache->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Sprache kann nicht gelöscht werden, da sie von einem oder mehreren Besuchen verwendet wird.',
                    'error' => 'foreign_key_constraint_violation'
                ], 409); // 409 Conflict
            }

            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen der Sprache aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search($suchBegriff, $limit, $allSprachen)
    {
        $suchBegriff = strtolower(trim($suchBegriff));
        $resultate = [];

        foreach ($allSprachen as $sprache) {
            $name = strtolower($sprache['name']);

            if (str_contains($name, $suchBegriff)) {
                $distance = levenshtein($name, $suchBegriff);

                // Sprachen, die mit dem Suchbegriff anfangen, sollen weiter oben stehen.
                if (str_starts_with($name, $suchBegriff)) {
                    $distance -= 5;
                }

                $sprache['distance'] = $distance;
                $resultate[] = $sprache;
            }
        }

        // Resultate nach Distanz sortieren
        usort($resultate, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        $resultate = array_slice($resultate, 0, $limit ?? 10);

        foreach ($resultate as &$sprache) {
            unset($sprache['distance']);
        }

        return response()->json($resultate);
    }
}
