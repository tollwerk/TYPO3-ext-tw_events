# tw_events

`tw_events` ist der Baustein „Veranstaltungen“ des [Attendance Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit): Veranstaltungen mit ihrem Programm, den beteiligten Personen und Organisationen und ihrer Dokumentation, für TYPO3 14. Personen sind Website-Benutzer mit den Feldern von [`tw-accounts`](https://code.tollwerk.net/tollwerk/typo3/extensions/tw-accounts). Löscht eine Person ihr Konto, bleibt ihr Profil, wenn sie in einer Session spricht, eine Veranstaltung organisiert oder einen Bericht oder eine Notiz geschrieben hat, auch in versteckten Datensätzen; sonst entfernt `tw_events` die Verweise aus gelöschten Datensätzen. Die Planung steht bei der Website des Accessibility Club in [tw-events.md](https://code.tollwerk.net/a11yclub/website/-/blob/main/docs/tw-events.md), das gemeinsame Konzept im [Konzept des Kit](https://code.tollwerk.net/tollwerk/typo3/attendance-kit/-/blob/main/docs/konzept.md).

## Installation

Für TYPO3 14 im Composer-Modus:

```sh
composer require tollwerk/tw-events
```

Die Fassung für ältere Versionen von TYPO3 liegt im Zweig `2.x`.

## Komponenten

Die Oberfläche besteht aus Fluid-Komponenten, die `tw_events` unter `Tollwerk\Components` mit der **Priorität 40** anmeldet. Die Templates der Plugins setzen die strukturierten Daten und lesen die Details der Karten aus den Site-Einstellungen `twEvents.speakers`, `twEvents.hosts` und `twEvents.sponsors`; alles Markup steht in den Komponenten. Sie bekommen die Models von Extbase und bauen Links mit den ViewHelpers `events:uri.*`. Eine Website ändert das Markup, indem sie eine Komponente unter demselben Namen mit einer Priorität ab 100 ersetzt.

Zu jeder Komponente gehören Beispiele mit den Datensätzen aus den Fixtures der Tests; `ComponentExamplesTest` rendert jedes im Frontend einer Site.

| Komponente | Zweck |
| --- | --- |
| `twc:event.list` | Liste von Veranstaltungen, mit einem Text, wenn es keine gibt |
| `twc:event.teaser` | Veranstaltung in einer Liste |
| `twc:event.event` | Veranstaltung mit den gewählten Teilen |
| `twc:event.part.*` | Teile einer Veranstaltung: `basics`, `tickets`, `livestream`, `description`, `schedule`, `speakers`, `organizers`, `participations`, `location`, `downloads` und `coverage` |
| `twc:event.session` | Seite einer Session mit Sprechenden, Berichten und Notizen |
| `twc:event.session.item` | Session im Programm |
| `twc:event.person` | Profil einer Person |
| `twc:event.person.list` | Personen mit den gewählten Details, auf Wunsch mit Ankern |
| `twc:event.person.card` | Person mit den gewählten Details |
| `twc:event.organization` | Profil einer Organisation |
| `twc:event.organization.list` | Organisationen als Karten oder als Veranstaltungsorte |
| `twc:event.organization.card` | Organisation mit den gewählten Details |
| `twc:event.organization.venue` | Organisation als Veranstaltungsort |
| `twc:event.organization.address` | Adresse mit Link zur Karte |
| `twc:event.contacts` | Adressen einer Person oder Organisation in der gewählten Reihenfolge |
| `twc:event.coverage` | Berichte, Folien und Aufzeichnungen |
| `twc:event.dateRange` | Beginn und Ende in der Zeitzone der Veranstaltung |

## Entwicklung

```sh
composer install
composer check
```

`composer check` führt alle Prüfungen der [tollwerk guidelines](https://code.tollwerk.net/tollwerk/guidelines) aus, wie die Pipeline. Die Functional-Tests bauen eine leere Instanz von TYPO3 auf SQLite und brauchen dafür `pdo_sqlite`.

## Lizenz

GPL-2.0-or-later, siehe [LICENSE.md](LICENSE.md).
