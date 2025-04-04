## Was ist Swagger?

Swagger ist ein Tool, um REST APIs zu dokumentieren. Für dieses Projekt verwende ich Swagger zur Dokumentation der REST API, um eine Darstellung der API-Endpoints im Browser zu haben und diese dort direkt zu testen.

## Setup in Laravel

### 1. Installation

```bash
composer require darkaonline/l5-swagger
```

### 2. Konfiguration ⚙️

Nach der Installation ist die Konfigurationsdatei unter `config/l5-swagger.php` verfügbar. Standardwerte sind für die meisten Anwendungsfälle ausreichend.

### 3. Controller-Dokumentation 📋

> [!example] Beispiel
> Beispiel für die Dokumentation der Funktion `index()` des `FilmController` mit OpenAPI-Annotationen:

```php
/**
 * @OA\Info(
 *     title="Ticket Manager API",
 *     version="1.0.0",
 *     description="API für das Ticket-Management-System"
 * )
 */
class FilmController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/filme",
     *     summary="Liste aller Filme oder Suche nach Film (max. 100)",
     *     description="Gibt eine Liste von Filmen zurück, optional gefiltert durch einen Suchbegriff.",
     *     tags={"Filme"},
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
     *         description="Liste von Filmen",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Film")
     *         )
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validierungsfehler",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object"
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        // Implementation...
    }
}
```

**Schema Definition:**

```php
/**
 * @OA\Schema(
 *     schema="Film",
 *     required={"name", "sprache_id"},
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="sprache_id", type="integer")
 * )
 */
```

### 4. Generierung der Dokumentation 🔄


```bash
php artisan l5-swagger:generate
```

### 5. Zugriff auf die Dokumentation

> [!tip] Nach der Generierung ist die Swagger-UI unter folgender URL erreichbar:
> 
> ```
> http://localhost:8000/api/documentation
> ```

## Wichtige Konzepte

- **Pfade (Paths)**: Definieren die API-Endpoints
- **Operationen**: HTTP-Methoden (GET, POST, PUT, DELETE)
- **Parameter**: URL, Query, Header und Body Parameter
- **Responses**: Mögliche Antworten mit Status-Codes
- **Schemas**: Datenmodelle für Request/Response
- **Tags**: Gruppierung verwandter Endpunkte

## Links 🔗

- [Offizielle Swagger-Dokumentation](https://swagger.io/docs/)
- [Laravel Swagger Package](https://github.com/DarkaOnLine/L5-Swagger)
- [OpenAPI Spezifikation](https://spec.openapis.org/oas/latest.html)