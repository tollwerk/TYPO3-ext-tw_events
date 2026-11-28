# Changelog

Alle nennenswerten Änderungen an der Extension. Das Format folgt [Keep a Changelog](https://keepachangelog.com/de/1.1.0/), und das Projekt nutzt [semantische Versionen](https://semver.org/lang/de/); die Hauptversion folgt der von TYPO3.

## Unreleased

### Hinzugefügt

- Fluid-Komponenten unter `twc:event.*` mit der Priorität 40 und Beispielen für jede; die Templates der Plugins setzen sie zusammen, die Partials entfallen. Eine Website ersetzt das Markup jetzt je Komponente statt je Template.
- Löscht eine Person ihr Konto in `tw_accounts`, bleibt ihr Profil über `KeepProfileEvent`, wenn sie in einer Session spricht, eine Veranstaltung organisiert oder einen Bericht oder eine Notiz geschrieben hat; über `AccountDeletedEvent` verschwinden die Verweise einer gelöschten Person aus gelöschten Datensätzen.

### Geändert

- Neu geschrieben für TYPO3 14, bisher als Pfad-Paket in der Website des Accessibility Club entwickelt. Die Fassung davor liegt im Zweig `2.x`.
- Das Foto einer Person zeigt den Zuschnitt `square` aus `tw_accounts`, die ganze Fotografie nur ohne Zuschnitt.
- Personen haben keine Mailadresse als Kontakt mehr: Die Adresse eines Kontos in `tw_accounts` ist die, mit der sich die Person anmeldet. Organisationen behalten sie.
- Die Regel für ein öffentliches Profil gilt an einer Stelle, `PersonRepository::hasPublicProfile()`: Profilseite, Links auf Profile und das JSON-LD folgen ihr. Ohne öffentliches Profil nennt das JSON-LD nur den Namen, und Karten verlinken nicht.
- Kontakte geben Adressen und Linkziele maskiert aus und verlinken Websites und Profile nur ins Web; Telefonnummern verlinken ihre Ziffern.
- Ein Bericht, dessen Link TYPO3 nicht auflösen kann, fehlt in der Liste wie im JSON-LD, statt als leerer Link zu erscheinen.
