<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Saal;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Säle",
 *     description="API-Endpunkte zum Verwalten von Kinosälen"
 * )
 */
class SaalController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/saele",
     *     summary="Liste aller Säle",
     *     tags={"Säle"},
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Sälen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Saal")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Saal::all());
    }

    /**
     * @OA\Post(
     *     path="/api/saele",
     *     summary="Erstelle einen neuen Saal",
     *     tags={"Säle"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SaalInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Saal erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Saal")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Der Saalname existiert bereits in diesem Kino."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                \Illuminate\Validation\Rule::unique('saele', 'name')->where('kino_id', $request->kino_id)
            ],
            'kino_id' => 'required|integer|exists:kinos,id',
        ]);

        $saal = new Saal();
        $saal->name = $validated['name'];
        $saal->kino_id = $validated['kino_id'];
        $saal->save();

        return response()->json($saal, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/saele/{id}",
     *     summary="Zeige einen Saal",
     *     tags={"Säle"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Saals",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Saal",
     *         @OA\JsonContent(ref="#/components/schemas/Saal")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Saal nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Saal nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $saal = Saal::find($id);

        if (!$saal) {
            return response()->json([
                'message' => 'Saal nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($saal);
    }

    /**
     * @OA\Put(
     *     path="/api/saele/{id}",
     *     summary="Aktualisiere einen Saal",
     *     tags={"Säle"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Saals",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/SaalInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Saal aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Saal")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Saal nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Saal nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Der Saalname existiert bereits in diesem Kino."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $saal = Saal::find($id);

        if (!$saal) {
            return response()->json([
                'message' => 'Saal nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:saele,name,' . $saal->id . ',id,kino_id,' . ($request->kino_id ?? $saal->kino_id),
            'kino_id' => 'sometimes|required|integer|exists:kinos,id',
        ]);

        $saal->fill($validated);
        $saal->save();

        return response()->json($saal);
    }

    /**
     * @OA\Delete(
     *     path="/api/saele/{id}",
     *     summary="Lösche einen Saal",
     *     tags={"Säle"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Saals",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Saal gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Saal nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Saal nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Saal wird noch verwendet",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Saal kann nicht gelöscht werden, da er von einem oder mehreren Besuchen verwendet wird."),
     *             @OA\Property(property="error", type="string", example="foreign_key_constraint_violation")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $saal = Saal::find($id);

        if (!$saal) {
            return response()->json([
                'message' => 'Saal nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }
        
        try {
            $saal->delete();
            return response()->json(null, 204); // 204 No Content
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return response()->json([
                    'message' => 'Saal kann nicht gelöscht werden, da er von einem oder mehreren Besuchen verwendet wird.',
                    'error' => 'foreign_key_constraint_violation'
                ], 409); // 409 Conflict
            }

            return response()->json([
                'message' => 'Ein Fehler ist beim Löschen des Saals aufgetreten.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
