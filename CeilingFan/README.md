# 🗂️ Deckenventilator (Ceiling Fan)

[![Version](https://img.shields.io/badge/Symcon-PHP--Modul-red.svg?style=flat-square)](https://www.symcon.de/service/dokumentation/entwicklerbereich/sdk-tools/sdk-php/)
[![Product](https://img.shields.io/badge/Symcon%20Version-8.1-blue.svg?style=flat-square)](https://www.symcon.de/produkt/)
[![Version](https://img.shields.io/badge/Modul%20Version-2.3.20261002-orange.svg?style=flat-square)](https://github.com/Wilkware/LocalTuya)
[![License](https://img.shields.io/badge/License-CC%20BY--NC--SA%204.0-green.svg?style=flat-square)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
[![Actions](https://img.shields.io/github/actions/workflow/status/wilkware/LocalTuya/ci.yml?branch=main&label=CI&style=flat-square)](https://github.com/Wilkware/LocalTuya/actions)

Das Modul bietet die Möglichkeit, mit einem kompatiblen Deckenventilator über das lokale Netzwerk zu kommunizieren.

## Inhaltverzeichnis

1. [Funktionsumfang](#user-content-1-funktionsumfang)
2. [Voraussetzungen](#user-content-2-voraussetzungen)
3. [Installation](#user-content-3-installation)
4. [Einrichtung](#user-content-4-einrichtung)
5. [Statusvariablen](#user-content-5-statusvariablen)
6. [Darstellungen](#user-content-6-darstellungen)
7. [Visualisierung](#user-content-7-visualisierung)
8. [Befehlsreferenz](#user-content-8-befehlsreferenz)
9. [Versionshistorie](#user-content-9-versionshistorie)

### 1. Funktionsumfang

Das Modul kommuniziert via MQTT mit dem Deckenventilator und bietet neben dem Auslesen aller Geräteinformationen auch das Steuern des Ventilators über die Statusvariablen.  
Eine genaue Beschreibung der für den Deckenventilator verfügbaren Befehlsumfang kann man im [Tuya Developer Portal](https://developer.tuya.com/en/) einsehen.

### 2. Voraussetzungen

* Symcon ab Version 8.1
* tuya2mqtt ab Version 1.5.0 (für Bridge-Status und den Abruf aller Werte beim Start)

Notwendige Voraussetzung ist eine funktionsfähige und laufende Installation von [Tuya2Mqtt](https://github.com/Wilkware/tuya2mqtt). Dessen Installation, Konfiguration und der Betrieb ist hier beschrieben: [README](https://github.com/Wilkware/tuya2mqtt/blob/main/README.md).  
Dort findet man ebenfalls die unterstützten Tuya Geräte.

Getestet mit meinem Deckenventilator WINDCALM von CREATE.

### 3. Installation

* Über den Modul Store die Bibliothek _LocalTuya_ installieren.
* Alternativ über das Modul Control folgende URL hinzufügen.  
`https://github.com/Wilkware/LocalTuya` oder `git://github.com/Wilkware/LocalTuya.git`

### 4. Einrichtung

* Unter 'Instanz hinzufügen' ist das _Deckenventilator_-Modul (Alias: _Tuya Deckenventilator_) unter dem Hersteller '(Geräte)' aufgeführt.

__Konfigurationsseite__:

Einstellungsbereich:

> 📳 Geräteinformationen ...

Name                        | Beschreibung
--------------------------- | ----------------------------------
MQTT Base Topic             | Ist das grundlegende Themenpräfix, unter dem alle spezifischen Subtopics für Nachrichten in einem MQTT-System organisiert werden. Standardmäßig ist der Präfix auf _'tuya2mqtt'_ vorbelegt. Er muss dem `topic` aus der `config.json` von tuya2mqtt entsprechen, darf mehrstufig sein (z. B. _'home/tuya'_) und ein abschließender `/` wird ignoriert.
MQTT Topic                  | Ist der eindeutige Geräte-Pfad, der zum Veröffentlichen und Abonnieren von Nachrichten verwendet wird. __HINWEIS:__ tuya2mqtt bildet den Pfad aus dem Gerätenamen in Kleinbuchstaben, Leerzeichen sowie die Zeichen `+`, `#` und `/` werden durch `_` ersetzt (z. B. _'Wohnzimmer Lampe'_ → _'wohnzimmer_lampe'_). Ohne Namen wird die Geräte-ID verwendet.


_Aktionsbereich:_

Aktion                  | Beschreibung
----------------------- | ---------------------------------
AKTUALISIEREN           | Löst eine Nachricht aus, welche versucht alle Status(Geräte)informationen vom Gerät abzurufen.

Da tuya2mqtt Werte nur noch bei Änderungen veröffentlicht, fordert das Modul beim Systemstart und nach dem Übernehmen der Konfiguration automatisch alle aktuellen Werte an (`get-states`).

### 5. Statusvariablen

Die Statusvariablen werden automatisch angelegt. Das Löschen einzelner kann hilfreich sein, z.B. wenn entsprechender Befehl/Status nicht vom Ventilator unterstützt wird.

Name                        | Typ     | Beschreibung
--------------------------- | ------- | ------------------------------
Status                      | String  | Verfügbarkeitsstatus des Gerätes
Licht                       | Boolean | Licht schalten
Farbtemperatur              | Integer | Farbtemperatur des Lichts
Ventilator                  | Boolean | Ventilator schalten
Geschwindigkeit             | Integer | Geschwindigkeitsstufe des Ventilators (schaltet den Ventilator bei Bedarf mit ein)
Richtung                    | String  | Drehrichtung des Ventilators
Verbleibende Zeit           | Integer | Restlaufzeit des eingestellten Timers
Piepton                     | Boolean | Bei jeder Schaltaktion einen Ton ausgeben

_Hinweis:_ Der Status wird zusätzlich über den Bridge-Status von tuya2mqtt (`<Base Topic>/bridge/status`) abgesichert: Meldet die Bridge _'offline'_ (z. B. per MQTT Last Will nach einem Absturz), wird der Status auf _'offline'_ gesetzt.

### 6. Darstellungen

Die Darstellungen werden direkt an den Statusvariablen hinterlegt, es werden keine Profile angelegt.

Variable                    | Darstellung   | Werte
--------------------------- | ------------- | ------------------------------
Status                      | Wertanzeige   | Online (online), Offline (offline), Undefiniert (undefine)
Licht                       | Schalter      | An / Aus
Farbtemperatur              | Schieberegler | 0 – 1000 (Schrittweite 500): Kühl (0), Neutral (500), Warm (1000)
Ventilator                  | Schalter      | An / Aus
Geschwindigkeit             | Schieberegler | Stufe 1 – 6 (Schrittweite 1)
Richtung                    | Aufzählung    | Vorwärts (forward), Rückwärts (reverse)
Verbleibende Zeit           | Schieberegler | 0 – 540 min (Schrittweite 1)
Piepton                     | Schalter      | An / Aus

### 7. Visualisierung

Man kann die Instanz bzw. Statusvariablen direkt in die Visualisierung verlinken.

### 8. Befehlsreferenz

Das Modul stellt keine direkten Funktionsaufrufe zur Verfügung.

### 9. Versionshistorie

v2.3.20261002

* _NEU_: Profile durch Darstellungen ersetzt
* _NEU_: Bridge-Status von tuya2mqtt wird ausgewertet (Status _'offline'_ bei Absturz der Bridge)
* _NEU_: Automatischer Abruf aller Werte beim Systemstart und nach Übernahme der Konfiguration (get-states)
* _NEU_: Mehrstufiges Base Topic möglich, abschließender `/` wird ignoriert
* _FIX_: Beim Ändern der Geschwindigkeit wird der Ventilator mit eingeschaltet, da das Gerät ihn sonst trotz Anlaufen weiter als aus meldet
* _FIX_: Empfangsfilter präzisiert (nur Geräte-Topics und Bridge-Status)
* _FIX_: Übersetzung der Darstellungen (Farbtemperatur, Richtung, Geschwindigkeit) korrigiert
* _FIX_: Dokumentation überarbeitet und Schreibfehler korrigiert

v2.2.20260319

* _FIX_: Kompatibilität für IPS größer 8.2 hergestellt

v2.1.20250926

* _FIX_: Abruf aller Daten korrigiert (get-states)
* _FIX_: Fehler bei Verarbeitung des Payloads durch Umstellung auf IPSModuleStrict korrigiert

v2.0.20250916

* _NEU_: Projektumstrukturierung hin zu einer globalen CI/CD-Pipeline
* _NEU_: Kompatibilität auf IPS 8.1 hoch gesetzt
* _NEU_: Umstellung auf IPSModuleStrict
* _FIX_: Bibliotheksfunktionen angeglichen

v1.1.20250802

* _NEU_: Konfigurationsformular überarbeitet
* _NEU_: Continuous Integration mit Check Style, Static Code Analysis und Unit Tests eingeführt
* _NEU_: Debugging Funktionen komplett überarbeitet
* _FIX_: Mqtt Topic test korrigiert
* _FIX_: Dokumentation für PHP Static Analysis komplett überarbeitet
* _FIX_: Bibliotheksfunktionen überarbeitet in Vorbereitung auf IPSModuleStrict

v1.0.20250125

* _NEU_: Initialversion

## Entwickler

Seit nunmehr über 10 Jahren fasziniert mich das Thema Haussteuerung. In den letzten Jahren betätige ich mich auch intensiv in der Symcon Community und steuere dort verschiedenste Skript und Module bei. Ihr findet mich dort unter dem Namen @pitti ;-)

[![GitHub](https://img.shields.io/badge/GitHub-@wilkware-181717.svg?style=for-the-badge&logo=github)](https://wilkware.github.io/)

## Spenden

Die Software ist für die nicht kommerzielle Nutzung kostenlos, über eine Spende bei Gefallen des Moduls würde ich mich freuen.

[![PayPal](https://img.shields.io/badge/PayPal-spenden-00457C.svg?style=for-the-badge&logo=paypal)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8816166)

## Lizenz

Namensnennung - Nicht-kommerziell - Weitergabe unter gleichen Bedingungen 4.0 International

[![Licence](https://img.shields.io/badge/License-CC_BY--NC--SA_4.0-EF9421.svg?style=for-the-badge&logo=creativecommons)](https://creativecommons.org/licenses/by-nc-sa/4.0/)
