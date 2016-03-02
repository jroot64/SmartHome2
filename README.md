# SmartHome v.2.0

Die Webapplikation SmartHome soll nach und nach Smarthome Funktionen zu beherschen.


## Hardware Vorrausetzumg

(evtl. sind Änderungen zum Betrieb nötig wenn andere Hardware verwendet wird)
- Banana Pi oder ähnlichen Einplatinen Computer
- Sendemodul
- WiringPi
- Rasperry-Remote (mit binär implementation)


## Aktuelle Module

### Aktor

Ermöglicht das schalten von Aktoren.
Außerdem lassen sich Aktoren deaktivieren, Räumen zuordnen und in Gruppen organisieren.
Des weiteren ist es möglich die Aktoren bei eingeschalteter Option aa = true Aktoren hinzuzufügen und bei der Option da = true Aktoren zu löschen.
Hierbei werden ebenfalls sämtliche Einträge von Schaltungen im Aktorlog sowie sämtliche zuordnungen des Aktors zu Gruppen gelöscht.
Die Erstellung von Räumen und Gruppen wird jedoch über ein gesondertes Modul geregelt.


### Gruppe

Ermöglicht die Nutzung der Möglichkeit zur organisation von Aktoren in Gruppen.
Die Gruppen sind mit diesem Modul schaltbar. Außerdem können neue Gruppen erstellt werden und bestehende gelöscht werden.


## Erweiterbarkeit

Das SmartHome lässt sich durch Module erweitern. Dazu werden die Funktionalen php-Klassen mit jeweils einer com.php und
include.php in einem Ordner unter modul eingefügt. Die com.php dient als Handler für Rückmeldungen durch das Modul
vom Webinterface und steuert die Methoden zur Umsetzung der Aktionen an. Die include.php bindet
die Funktionalen Klassen ein die in dem Modul enthalten sind. Die include.php wird beim Seitenaufruf dynamisch eingebunden.


# Tasks
- [ ] add errorCodes