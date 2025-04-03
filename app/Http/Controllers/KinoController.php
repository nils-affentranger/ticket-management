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
     *     summary="Liste aller Kinos",
     *     tags={"Kinos"},
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
    public function index()
    {
        return response()->json(Kino::all());
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
        
        // Check if any of the kino's saele are used in besuche
        $saeleWithBesuche = $kino->saele()->whereHas('besuche')->count();
        
        if ($saeleWithBesuche > 0) {
            return response()->json([
                'message' => 'Kino kann nicht gelöscht werden, da Säle dieses Kinos noch von Besuchen verwendet werden.',
                'error' => 'foreign_key_constraint_violation'
            ], 409); // 409 Conflict
        }
        
        // If the kino has saele that are not used in besuche, we'll delete those saele first
        try {
            // Delete all saele associated with this kino
            $kino->saele()->delete();
            
            // Now delete the kino
            $kino->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen des Kinos aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

