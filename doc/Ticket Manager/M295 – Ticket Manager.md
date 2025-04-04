## Projektübersicht
Dieses Projekt umfasst die Entwicklung des Backends eines Ticket-Management-Systems für Kinobesuche im Rahmen des Moduls M295.

## Projektziel
Entwicklung einer REST-API mit Laravel, mit der man Kinobesuche tracken, und Statistiken ansehen kann.

## Kernfunktionen
- Erfassung von Kinobesuchen (Ort, Film, Preis, Datum)
- Speicherung von Filmtypen (z.B. IMAX, 4DX)
- Unterstützung für mehrfaches Anschauen desselben Films
- Berechnung der Ersparnis durch ein Kino-Abonnement
- Berücksichtigung von unterschiedlichen Ticketpreisen

## Technische Basis
- **Backend**: Laravel REST API
- **Datenbank**: MySQL / MariaDB
- **Dokumentation**: Swagger/OpenAPI
- **Versionskontrolle**: Git

## Besondere Anforderungen
- System f�r einen einzelnen Benutzer ausgelegt
- Manuelle Eingabe der Kinobesuche vor Ort
- Integration eines speziellen Features f�r erh�hten Kundennutzen

## Dokumentation
- [Zeitplan](Zeitplan.md) - Projektmeilensteine
- [ERD](ERD.md) - Datenbankstruktur
- [Entwicklungsumgebung](Entwicklungsumgebung.md) - Setup
- [Swagger](Swagger.md) - API-Dokumentation
- [Eloquent](Eloquent.md) - ORM-Verwendung
- [Suchfunktion](Suchfunktion.md) - Implementierungsdetails