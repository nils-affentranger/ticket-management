Damit man später im Frontend nach Filmtiteln, Kinos und mehr suchen kann, brauch es in der REST API eine Funktion zum Suchen. Diese habe ich wie folgt implementiert:
```php
public function search($suchBegriff, $limit, $allObjekte)
    {
	    // Suchbegriff normalisieren
        $suchBegriff = strtolower(trim($suchBegriff));
        $resultate = [];

        foreach ($allObjekte as $objekt) {
            $objektName = strtolower($objekt['name']);

            if (str_contains($objektName, $suchBegriff)) {
	            // Berechnen, wie stark das Objekt vom Suchbegriff abewicht
                $distance = levenshtein($objektName, $suchBegriff);

                // Filme, die mit dem Suchbegriff anfangen, priorisieren
                if (str_starts_with($filmTitel, $suchBegriff)) {
                    $distance -= 5;
                }
				// Distanz als Eigenschaft im Objekt speichern
                $objekt['distance'] = $distance;
                $resultate[] = $objekt;
            }
        }

        // Resultate nach Distanz sortieren
        usort($resultate, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

		// Limitieren, wie viele Resultate ausgegeben werden. Standardwert: 10
        $resultate = array_slice($resultate, 0, $limit ?? 10);

		// Die distance Eigenschaft wieder von jedem Objekt löschen
        foreach ($resultate as &$objekt) {
            unset($film['distance']);
        }

        return response()->json($resultate);
    }
```
Der dazugehörige Controller sieht so aus:
```php
public function index(Request $request)
    {
        $validated = $request->validate([
            'query' => 'sometimes|string|max:255',
            'limit' => 'sometimes|integer|min:1|max:100'
        ]);
        $query = $validated['query'] ?? null;
        $limit = $validated['limit'] ?? 10;

        $filme = Film::all()->toArray();
        if (!$query) {
            return response()->json(array_slice($filme, 0, $limit));
        }
        return $this->search($query, $limit, $filme);
    }
```