# tw_events

`tw_events` ist der Baustein „Veranstaltungen“ des [Attendance Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit): Veranstaltungen mit ihrem Programm, den beteiligten Personen und Organisationen und ihrer Dokumentation, für TYPO3 14. Personen sind Website-Benutzer mit den Feldern von [`tw-accounts`](https://code.tollwerk.net/tollwerk/typo3/extensions/tw-accounts). Die Planung steht bei der Website des Accessibility Club in [tw-events.md](https://code.tollwerk.net/a11yclub/website/-/blob/main/docs/tw-events.md), das gemeinsame Konzept im [Konzept des Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit/-/blob/main/docs/konzept.md).

## Installation

Für TYPO3 14 im Composer-Modus:

```sh
composer require tollwerk/tw-events
```

Die Fassung für ältere Versionen von TYPO3 liegt im Zweig `2.x`.

## Entwicklung

```sh
composer install
composer check
```

`composer check` führt alle Prüfungen der [tollwerk guidelines](https://code.tollwerk.net/tollwerk/guidelines) aus, wie die Pipeline. Die Functional-Tests bauen eine leere Instanz von TYPO3 auf SQLite und brauchen dafür `pdo_sqlite`.

## Lizenz

GPL-2.0-or-later, siehe [LICENSE.md](LICENSE.md).
