## Mittwoch
```timeline
[line-3, body-2]
+ 8:45
+ [[Einfuehrung]]
+ Remo hat uns das Projekt mündlich vorgestellt. Ich machte Notizen und überlegte mir bereits, wie ich vorgehen sollte.

+ 9:00
+ [[ERD]]
+ In Gruppen von 3 überlegten wir uns, wie die Struktur der Datenbank aussehen sollte.

+ 9:30
+ [[Entwicklungsumgebung]]
+ Ich hatte schon PHP und Mysql über XAMPP eingerichtet. Jedoch wollte ich mal ausprobieren, eine eigene [[Entwicklungsumgebung#Docker Compose]] Umgebung zu erstellen.

+ 11:00
+ [Models]([[Eloquent#Models]])
+ Um das [[ERD]] im Projekt zu realisieren, generierte ich für jede Tabelle ein Model, inklusive Controller und Migration Script und füllte dies anschliessend mit den Eigenschaften des [[ERD]] aus.

+ 13:00
+ Migration Scripts
+ Im vorherigen Schritt habe ich mit den Models auch Migration Scripts generiert. Diese ergänzte ich, um dem ERD zu entsprechen. Ich hatte vorhin noch nie ein Migration script auf Laravel geschrieben, hatte aber schon Erfahrung von .NET mit Update Scripts.

+ 14:30
+ Controller
+ Die Boilerplate der Controller wurde schon in einem vorherigen Schritt generiert. Nun musste ich diese noch fertig stellen, damit sie mit meinem Model kommunizieren. Ich fing an mit einem eifachen `index()` Controller. Dieser holt einfach alle Einträge von der DB und gibt sie als JSON aus.

+ 15:00
+ `store()` Controller
+ Der `store()` Controller speichert neue Einträge in der DB und ist ein wenig komplizierter, weil die Eingaben validiert werden müssen.
```
### Donnerstag
```timeline
[line-3, body-2]
+ 8:30
+ Mehr Controller
+ Nachdem ich alle Controller für die Verwaltung der Filme gemacht habe, übertrug ich dieselbe Funktionalität auf die restlichen Controller, `Besuch`, `Typ`, `Saal`, `Kino` und `Sprache`. Dazu musste ich im Grunde nur beim Film Controller abschauen und auf die Values des Jeweiligen Models anpassen. Es war nicht per se kompliziert, aber es brauchte viel Zeit.

+ 11:00
+ Weitere Features suchen
+ Ich war an diesem Zeitpunkt mit den Anforderungen des Kunden fertig. Also überlegte ich mir, wie das Frontend später funktionieren sollte.

+ 12:45
+ [[Suchfunktion]]
+ Eine Wichtige Funktion, die ich unbedingt in meinem Backend einbauen wollte, ist eine Suchfunktion für alle Objekte. Als erstes habe ich eine Suche für Filme eingebaut.

+ 13:45
+ Dokumentation
+ Da ich noch wenig Zeit in die Dokumentation investiert habe, musste ich noch einiges nachholen.

+ 15:30
+ [[Swagger]]
+ Um die API-Endpoints zu dokumentieren, habe ich Swagger in meinem Projekt eingerichtet. 

+ 15:50
+ OpenAPI Dokumentation
+ Damit meine Endpoints auf Swagger sichtbar sind, musste ich über jedem Controller in meinem Backend detailliert beschreiben. Wenn ich das von Hand gemacht hätte, hätte ich viel zu viel Zeit gebraucht. Darum schrieb ich für den `FilmController` die Dokumentation und liess mir den Rest von Claude generieren. Natürlich musste ich noch einiges anpassen.
```
### Freitag
```timeline
[line-3, body-2]
+ 8:30
+ Dokumentation - Zeitstrahl
+ Da ich immer noch nicht sehr weit in der Dokumentation war, machte ich weiter mit diesem Zeitstrahl, in dem ich alle Schritte detailliert beschreibe

+ 9:45
+ Suchfunktion - Pt. 2
+ Ich hatte am Donnerstag nachmittag eine Suchfunktion für die Filme eingebaut. Jetzt wollte ich sie für die restlichen Objekte einbauen. Es war nicht sehr komplex, denn ich musste nur die suchfunktion vom `FilmController` Copy-Pasten und auf den Controller anpassen.

+ 10:30
+ REST API Struktur
+ Die Struktur der Requests und Responses der REST API liess noch ein wenig zu wünschen übrig. Deshalb habe ich die controller angepasst, sodass sie die Parameter richtig validieren und, vor allem beim `BesuchController`, dass die Response besser sortiert und aufgeräumt zurück kommt

+ 11:15
+ OpenAPI anpassen
+ Da ich die Struktur aller Endpoints komplett verändert habe, musste ich alle meine OpenAPI Kommentäre anpassen.

+ 13:00
+ Dokumentation
+ Weil ich die Demo und das Fachgespräch schon am selben Tag hatte, musste ich mich beeilen, die Dokumentation fertigzustellen

+ 14:25
+ Demo / Fachgespräch
+ Ich stellte das Projekt Remo vor, und er stellte mir ein paar fragen über das Projekt.

+ 15:10
+ Endspurt
+ Ich schrieb die Dokumentation zu Ende und bereitete das Projekt für die Abgabe vor
```

## Links
[[Einfuehrung]]
[[ERD]]
[[Entwicklungsumgebung]]
[[Suchfunktion]]
[[Swagger]]