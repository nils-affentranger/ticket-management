<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Einstellung",
 *     title="Einstellung",
 *     description="Einstellung Model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="key", type="string", example="ticket_basispreis"),
 *     @OA\Property(property="value", type="string", example="12.50"),
 *     @OA\Property(property="description", type="string", example="Basispreis für alle Tickets"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="EinstellungInput",
 *     title="EinstellungInput",
 *     description="Einstellung Input Schema",
 *     @OA\Property(property="key", type="string", example="ticket_basispreis"),
 *     @OA\Property(property="value", type="string", example="12.50"),
 *     @OA\Property(property="description", type="string", example="Basispreis für alle Tickets")
 * )
 * 
 * @OA\Schema(
 *     schema="EinstellungUpdateInput",
 *     title="EinstellungUpdateInput",
 *     description="Einstellung Update Input Schema",
 *     @OA\Property(property="value", type="string", example="12.50"),
 *     @OA\Property(property="description", type="string", example="Basispreis für alle Tickets")
 * )
 */
class Einstellung extends Model
{
    use HasFactory;

    protected $table = 'einstellungen';

    protected $fillable = [
        'key',
        'value',
        'description'
    ];
}
