<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Besuch;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Besuche",
 *     description="API-Endpunkte zum Verwalten von Kinobesuchen"
 * )
 */
class BesuchController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/besuche",
     *     summary="Liste aller Kinobesuche",
     *     tags={"Besuche"},
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Kinobesuchen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Besuch")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Besuch::all());
    }

    /**
     * @OA\Post(
     *     path="/api/besuche",
     *     summary="Erstelle einen neuen Kinobesuch",
     *     tags={"Besuche"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BesuchInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kinobesuch erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Besuch")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Die angegebenen Daten sind ungültig."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anfang' => 'required|date',
            'ende' => 'required|date',
            'reihe' => 'required|string|max:1',
            'platz' => 'required|integer|min:1|max:255',
            'untertitel' => 'required|boolean',
            'snackzuschlag_chf' => 'numeric|min:0|max:999.99',
            'film_id' => 'required|exists:filme,id',
            'typ_id' => 'required|exists:typen,id',
            'sprache_id' => 'required|exists:sprachen,id',
            'saal_id' => 'required|exists:saele,id',
        ]);

        $besuch = new Besuch();
        $besuch->anfang = $validated['anfang'];
        $besuch->ende = $validated['ende'];
        $besuch->reihe = $validated['reihe'];
        $besuch->platz = $validated['platz'];
        $besuch->untertitel = $validated['untertitel'];
        $besuch->snackzuschlag_chf = $validated['snackzuschlag_chf'];
        $besuch->film_id = $validated['film_id'];
        $besuch->typ_id = $validated['typ_id'];
        $besuch->sprache_id = $validated['sprache_id'];
        $besuch->saal_id = $validated['saal_id'];
        $besuch->save();

        return response()->json($besuch, 201); // 201 Created
    }

    /**
     * @OA\Get(
     *     path="/api/besuche/{id}",
     *     summary="Zeige einen Kinobesuch",
     *     tags={"Besuche"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinobesuchs",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Kinobesuch",
     *         @OA\JsonContent(ref="#/components/schemas/Besuch")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kinobesuch nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Besuch nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $besuch = Besuch::find($id);

        if (!$besuch) {
            return response()->json([
                'message' => 'Besuch nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($besuch);
    }

    /**
     * @OA\Put(
     *     path="/api/besuche/{id}",
     *     summary="Aktualisiere einen Kinobesuch",
     *     tags={"Besuche"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinobesuchs",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/BesuchInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kinobesuch aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Besuch")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Kinobesuch nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Besuch nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Die angegebenen Daten sind ungültig."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $besuch = Besuch::find($id);

        if (!$besuch) {
            return response()->json([
                'message' => 'Besuch nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'anfang' => 'sometimes|required|date',
            'ende' => 'sometimes|required|date',
            'reihe' => 'sometimes|required|string|max:1',
            'platz' => 'sometimes|required|integer|min:1|max:255',
            'untertitel' => 'sometimes|required|boolean',
            'snackzuschlag_chf' => 'sometimes|numeric|min:0|max:999.99',
            'film_id' => 'sometimes|required|exists:filme,id',
            'typ_id' => 'sometimes|required|exists:typen,id',
            'sprache_id' => 'sometimes|required|exists:sprachen,id',
            'saal_id' => 'sometimes|required|exists:saele,id',
        ]);

        $besuch->fill($validated);
        $besuch->save();

        return response()->json($besuch);
    }

    /**
     * @OA\Delete(
     *     path="/api/besuche/{id}",
     *     summary="Lösche einen Kinobesuch",
     *     tags={"Besuche"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID des Kinobesuchs",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Kinobesuch gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Kinobesuch nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Besuch nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $besuch = Besuch::find($id);

        if (!$besuch) {
            return response()->json([
                'message' => 'Besuch nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $besuch->delete();

        return response()->json(null, 204); // 204 No Content
    }
}
