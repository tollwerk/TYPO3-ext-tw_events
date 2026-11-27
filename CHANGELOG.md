# Changelog

Alle nennenswerten Änderungen an der Extension. Das Format folgt [Keep a Changelog](https://keepachangelog.com/de/1.1.0/), und das Projekt nutzt [semantische Versionen](https://semver.org/lang/de/); die Hauptversion folgt der von TYPO3.

## Unreleased

### Hinzugefügt

- Fluid-Komponenten unter `twc:event.*` mit der Priorität 40 und Beispielen für jede; die Templates der Plugins setzen sie zusammen, die Partials entfallen. Eine Website ersetzt das Markup jetzt je Komponente statt je Template.
- Löscht eine Person ihr Konto in `tw_accounts`, bleibt ihr Profil über `KeepProfileEvent`, wenn sie in einer Session spricht, eine Veranstaltung organisiert oder einen Bericht oder eine Notiz geschrieben hat; über `AccountDeletedEvent` verschwinden die Verweise einer gelöschten Person aus gelöschten Datensätzen.

### Geändert

- Neu geschrieben für TYPO3 14, bisher als Pfad-Paket in der Website des Accessibility Club entwickelt. Die Fassung davor liegt im Zweig `2.x`.
- Das Foto einer Person zeigt den Zuschnitt `square` aus `tw_accounts`, die ganze Fotografie nur ohne Zuschnitt.
