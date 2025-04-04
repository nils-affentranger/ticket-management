<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Typ;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Typen",
 *     description="API-Endpunkte zum Verwalten von Tickettypen"
 * )
 */
class TypController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/typen",
     *     summary="Liste aller Tickettypen",
     *     tags={"Typen"},
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Suchbegriff zum Filtern der Tickettypen",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=255)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Maximale Anzahl der zurückzugebenden Tickettypen",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100)
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Tickettypen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Typ")
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
        $typen = Typ::all()->toArray();

        if ($query) {
            return $this->search($query, $limit, $typen);
        }

        if ($limit) {
            $typen = array_slice($typen, 0, $limit);
        }

        return response()->json($typen);
    }

    /**
     * @OA\Post(
     *     path="/api/typen",
     *     summary="Erstelle einen neuen Tickettyp",
     *     tags={"Typen"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tickettyp erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Typ")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'zuschlag_chf' => 'sometimes|numeric|min:0|max:999.99',
        ]);

        $typ = new Typ();
        $typ->name = $validated['name'];
        $typ->zuschlag_chf = $validated['zuschlag_chf'];
        $typ->save();

        return response()->json($typ, 201); // 201 Created
    }

    /**
     * @OA\Get(
     *     path="/api/typen/{id}",
     *     summary="Zeige einen Tickettyp",
     *     tags={"Typen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Tickettyps",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Tickettyp",
     *         @OA\JsonContent(ref="#/components/schemas/Typ")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tickettyp nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Typ nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $typ = Typ::find($id);

        if (!$typ) {
            return response()->json([
                'message' => 'Typ nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($typ);
    }

    /**
     * @OA\Put(
     *     path="/api/typen/{id}",
     *     summary="Aktualisiere einen Tickettyp",
     *     tags={"Typen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Tickettyps",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tickettyp aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Typ")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tickettyp nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Typ nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $typ = Typ::find($id);

        if (!$typ) {
            return response()->json([
                'message' => 'Typ nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'zuschlag_chf' => 'sometimes|numeric|min:0|max:999.99',
        ]);

        $typ->fill($validated);
        $typ->save();

        return response()->json($typ);
    }

    /**
     * @OA\Delete(
     *     path="/api/typen/{id}",
     *     summary="Lösche einen Tickettyp",
     *     tags={"Typen"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Tickettyps",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Tickettyp gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Tickettyp nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Typ nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Tickettyp wird noch verwendet",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Typ kann nicht gelöscht werden, da er von einem oder mehreren Besuchen verwendet wird."),
     *             @OA\Property(property="error", type="string", example="foreign_key_constraint_violation")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $typ = Typ::find($id);

        if (!$typ) {
            return response()->json([
                'message' => 'Typ nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        try {
            $typ->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Typ kann nicht gelöscht werden, da er von einem oder mehreren Besuchen verwendet wird.',
                    'error' => 'foreign_key_constraint_violation'
                ], 409); // 409 Conflict
            }

            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen des Typs aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search($suchBegriff, $limit, $allTypen)
    {
        $suchBegriff = strtolower(trim($suchBegriff));
        $resultate = [];

        foreach ($allTypen as $typ) {
            $name = strtolower($typ['name']);

            if (str_contains($name, $suchBegriff)) {
                $distance = levenshtein($name, $suchBegriff);

                // Typen, die mit dem Suchbegriff anfangen, sollen weiter oben stehen.
                if (str_starts_with($name, $suchBegriff)) {
                    $distance -= 5;
                }

                $typ['distance'] = $distance;
                $resultate[] = $typ;
            }
        }

        // Resultate nach Distanz sortieren
        usort($resultate, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        $resultate = array_slice($resultate, 0, $limit ?? 10);

        foreach ($resultate as &$typ) {
            unset($typ['distance']);
        }

        return response()->json($resultate);
    }
}
