<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;
use App\Models\Einstellung;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Einstellungen",
 *     description="API-Endpunkte zum Verwalten von Einstellungen"
 * )
 */
class EinstellungController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/einstellungen",
     *     summary="Liste aller Einstellungen",
     *     tags={"Einstellungen"},
     *     @OA\Response(
     *         response="200",
     *         description="Liste von Einstellungen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Einstellung")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Einstellung::all());
    }

    /**
     * @OA\Post(
     *     path="/api/einstellungen",
     *     summary="Erstelle eine neue Einstellung",
     *     tags={"Einstellungen"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EinstellungInput")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Einstellung erfolgreich erstellt",
     *         @OA\JsonContent(ref="#/components/schemas/Einstellung")
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Schlüssel existiert bereits",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Eine Einstellung mit diesem Schlüssel existiert bereits."),
     *             @OA\Property(property="error", type="string", example="duplicate_key")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:einstellungen',
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $einstellung = new Einstellung();
        $einstellung->key = $validated['key'];
        $einstellung->value = $validated['value'];
        $einstellung->description = $validated['description'] ?? null;
        $einstellung->save();

        return response()->json($einstellung, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/einstellungen/{key}",
     *     summary="Zeige eine Einstellung",
     *     tags={"Einstellungen"},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         description="Schlüssel der Einstellung",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Einstellung",
     *         @OA\JsonContent(ref="#/components/schemas/Einstellung")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Einstellung nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Einstellung nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function show($key)
    {
        $einstellung = Einstellung::where('key', $key)->first();

        if (!$einstellung) {
            return response()->json([
                'message' => 'Einstellung nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json($einstellung);
    }

    /**
     * @OA\Put(
     *     path="/api/einstellungen/{key}",
     *     summary="Aktualisiere eine Einstellung",
     *     tags={"Einstellungen"},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         description="Schlüssel der Einstellung",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/EinstellungUpdateInput")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Einstellung aktualisiert",
     *         @OA\JsonContent(ref="#/components/schemas/Einstellung")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Einstellung nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Einstellung nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $key)
    {
        $einstellung = Einstellung::where('key', $key)->first();

        if (!$einstellung) {
            return response()->json([
                'message' => 'Einstellung nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $validated = $request->validate([
            'value' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $einstellung->value = $validated['value'];

        if (isset($validated['description'])) {
            $einstellung->description = $validated['description'];
        }

        $einstellung->save();

        return response()->json($einstellung);
    }

    /**
     * @OA\Delete(
     *     path="/api/einstellungen/{key}",
     *     summary="Lösche eine Einstellung",
     *     tags={"Einstellungen"},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         description="Schlüssel der Einstellung",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=204, description="Einstellung gelöscht"),
     *     @OA\Response(
     *         response=404,
     *         description="Einstellung nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Einstellung nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function destroy($key)
    {
        $einstellung = Einstellung::where('key', $key)->first();

        if (!$einstellung) {
            return response()->json([
                'message' => 'Einstellung nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        $einstellung->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/einstellungen/value/{key}",
     *     summary="Hole nur den Wert einer Einstellung",
     *     tags={"Einstellungen"},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         description="Schlüssel der Einstellung",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Wert der Einstellung",
     *         @OA\JsonContent(
     *             @OA\Property(property="value", type="string", example="10.50")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Einstellung nicht gefunden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Einstellung nicht gefunden."),
     *             @OA\Property(property="error", type="string", example="resource_not_found")
     *         )
     *     )
     * )
     */
    public function getValue($key)
    {
        $einstellung = Einstellung::where('key', $key)->first();

        if (!$einstellung) {
            return response()->json([
                'message' => 'Einstellung nicht gefunden.',
                'error' => 'resource_not_found'
            ], 404);
        }

        return response()->json(['value' => $einstellung->value]);
    }
}
