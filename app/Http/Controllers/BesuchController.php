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
     * path="/api/besuche",
     * summary="Liste aller Kinobesuche",
     * tags={"Besuche"},
     * @OA\Parameter(
     * name="datum",
     * in="query",
     * description="Filtert nach Datum",
     * required=false,
     * @OA\Schema(type="string", format="date")
     * ),
     * @OA\Parameter(
     * name="typ_id",
     * in="query",
     * description="Filtert nach Typ ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="film_id",
     * in="query",
     * description="Filtert nach Film ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="kino_id",
     * in="query",
     * description="Filtert nach Kino ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="saal_id",
     * in="query",
     * description="Filtert nach Saal ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Parameter(
     * name="sprache_id",
     * in="query",
     * description="Filtert nach Sprache ID",
     * required=false,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response="200",
     * description="Liste von Kinobesuchen",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/Besuch")
     * )
     * )
     * )
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'datum' => 'sometimes|date',
            'typ_id' => 'sometimes|integer|exists:typen,id',
            'film_id' => 'sometimes|integer|exists:filme,id',
            'kino_id' => 'sometimes|integer|exists:kinos,id',
            'saal_id' => 'sometimes|integer|exists:saele,id',
            'sprache_id' => 'sometimes|integer|exists:sprachen,id',
        ]);

        // Start with a base query with eager loading
        $query = Besuch::with([
            'film:id,filmtitel',
            'typ:id,name',
            'sprache:id,name',
            'saal:id,name,kino_id',
            'saal.kino:id,name'
        ]);

        // Apply each filter condition if present
        if (isset($validated['datum'])) {
            $query->whereDate('anfang', $validated['datum']);
        }

        if (isset($validated['film_id'])) {
            $query->where('film_id', $validated['film_id']);
        }

        if (isset($validated['typ_id'])) {
            $query->where('typ_id', $validated['typ_id']);
        }

        if (isset($validated['saal_id'])) {
            $query->where('saal_id', $validated['saal_id']);
        }

        if (isset($validated['sprache_id'])) {
            $query->where('sprache_id', $validated['sprache_id']);
        }

        if (isset($validated['kino_id'])) {
            $query->whereHas('saal', function ($q) use ($validated) {
                $q->where('kino_id', $validated['kino_id']);
            });
        }

        $besuche = $query->get();

        // Transform the data to match the desired output format
        $result = $besuche->map(function ($besuch) {
            // Get the kino data from the saal relationship
            $kino = $besuch->saal->kino;

            // Create the desired output structure
            $transformed = [
                'id' => $besuch->id,
                'anfang' => $besuch->anfang,
                'ende' => $besuch->ende,
                'reihe' => $besuch->reihe,
                'platz' => $besuch->platz,
                'untertitel' => $besuch->untertitel,
                'snackzuschlag_chf' => $besuch->snackzuschlag_chf,
                'film' => [
                    'id' => $besuch->film->id,
                    'titel' => $besuch->film->filmtitel
                ],
                'typ' => [
                    'id' => $besuch->typ->id,
                    'name' => $besuch->typ->name
                ],
                'sprache' => [
                    'id' => $besuch->sprache->id,
                    'name' => $besuch->sprache->name
                ],
                'saal' => [
                    'id' => $besuch->saal->id,
                    'name' => $besuch->saal->name
                ],
                'kino' => [
                    'id' => $kino->id,
                    'name' => $kino->name
                ],
                'created_at' => $besuch->created_at,
                'updated_at' => $besuch->updated_at
            ];

            return $transformed;
        });

        return response()->json($result);
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
        $besuch->anfang = date('Y-m-d H:i:s', strtotime($validated['anfang']));
        $besuch->ende = date('Y-m-d H:i:s', strtotime($validated['ende']));
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

        if (isset($validated['anfang'])) {
            $validated['anfang'] = date('Y-m-d H:i:s', strtotime($validated['anfang']));
        }

        if (isset($validated['ende'])) {
            $validated['ende'] = date('Y-m-d H:i:s', strtotime($validated['ende']));
        }

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
