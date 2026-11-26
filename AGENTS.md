# Anweisungen für KI-Agenten

Dieses Projekt folgt den [tollwerk guidelines](https://code.tollwerk.net/tollwerk/guidelines). Sie sind verbindlich für Menschen und KI-Agenten. Vor jeder Änderung lesen; in PHP-Projekten liegen sie nach `composer install` auch in `vendor/tollwerk/guidelines/guidelines/`.

## Profile

Dieses Projekt übernimmt die Basis der guidelines und diese Profile, wie in `guidelines.json` eingestellt:

- `typo3`

## Über dieses Projekt

`tw_events` ist der Baustein „Veranstaltungen“ des [Attendance Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit): Veranstaltungen mit ihrem Programm, den beteiligten Personen und Organisationen und ihrer Dokumentation, für TYPO3 14. Personen sind Website-Benutzer mit den Feldern von [`tw-accounts`](https://code.tollwerk.net/tollwerk/typo3/extensions/tw-accounts). Die Planung steht bei der Website des Accessibility Club in [tw-events.md](https://code.tollwerk.net/a11yclub/website/-/blob/main/docs/tw-events.md), das gemeinsame Konzept im [Konzept des Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit/-/blob/main/docs/konzept.md). Die Dokumentation ist Deutsch, Code, Kommentare und Commit-Nachrichten sind Englisch.

- **Prüfungen:** `composer check` führt `guidelines gate` aus, dieselben Prüfungen wie die Pipeline.
- **Entwicklung:** Websites binden diesen Klon während der Entwicklung ein; getestet wird die Extension für sich allein in einer leeren Instanz von TYPO3.
- **Ältere Fassungen:** Der Stand vor TYPO3 14 liegt im Zweig `2.x`.

## Regeln dieses Projekts

- **Docblocks:** `@category Tollwerk`, `@package Tollwerk\TwEvents`.
- **Geprüfte Ordner:** `Classes`, `Configuration` und `tests`, statt `packages` und `tests` einer TYPO3-Anwendung; eine Extension hat ihren Code an der Wurzel.
- **Personen sind `fe_users`** mit den Feldern von `tw-accounts`; `tw-events` setzt die Extension voraus, nie umgekehrt.
- **Barrierefreiheit:** Alles im Frontend erfüllt mindestens WCAG 2.2 AA und funktioniert vollständig ohne JavaScript.
- **Semantik im Markup:** Veranstaltungen, Sessions, Personen und Orte tragen Microformats und schema.org.

## Abweichungen von den guidelines

Keine. Eine Abweichung braucht die Freigabe des Maintainers und steht hier mit ihrem Grund.
