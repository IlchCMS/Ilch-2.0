# PHPUnit in Ilch2.0

## Fragen bzgl. PHPUnit?
Hier gibts Antworten [PHPUnit](http://phpunit.de/manual/current/en/)


## Installation/Verwendung

1. **PHPUnit installieren.** Eine gute Anleitung für Xampp ist [hier](http://web-union.de/484) zu finden.

2. **xDebug auf dem Server aktivieren.**
In Xampp muss man dafür, je nach Installation, nur die Kommentare in folgendem Teil in der php.ini rausnehmen bzw. den Teil einfügen:

        [XDebug]
        zend_extension = "C:\xampp\php\ext\php_xdebug.dll"
        xdebug.profiler_append = 0
        xdebug.profiler_enable = 1
        xdebug.profiler_enable_trigger = 0
        xdebug.profiler_output_dir = "C:\xampp\tmp"
        xdebug.profiler_output_name = "cachegrind.out.%t-%s"
        xdebug.remote_enable = 0
        xdebug.remote_handler = "dbgp"
        xdebug.remote_host = "127.0.0.1"
        xdebug.trace_output_dir = "C:\xampp\tmp"

3. **Im Ordner tests/ folgendes ausführen:**

        phpunit . #Führt alle Tests aus
        phpunit --coverage-text . #Führt alle Tests aus und zeigt nen Code-Coverage Report in der Konsole
        phpunit Libraries_Ilch_RequestTest #Führt nur Tests einer Klasse aus


## Tests

### Ordnerstruktur

* Unterhalb von "tests/" sollte die Ordnerstruktur aussehen wie ab "application/".
* Die Ordnerstruktur sieht dann wie folgt aus:

        tests/libraries/ilch
        tests/libraries/ilch/database
        tests/libraries/modules
        tests/libraries/plugins

### Die Testklasse

Hier ein Beispiel wie eine Testklasse aussieht.
Weiter unten folgt dann eine Zusammenfassung unter welchen Regeln die Klasse und die Testfunktion erstellt wurde.

```php
/**
 * Tests some object.
 *
 * @author <author>
 * @package ilch_phpunit
 */
class Libraries_Ilch_SomeObjectTest extends IlchTestCase
{
    /**
     * Tests if some function from some object returns null.
     */
    public function testSomeFunctionReturnsNull()
    {
        $someObj = new Some_Object();
        $this->assertNull($someObj->someFunction());
    }
}
```

#### Klassennamen

* Der Name von Testklassen endet mit "Test".
* Testklassen sollten von IlchTestCase ableiten (liegt im Ordner "tests/"). (Evtl. kommen in Zukunft noch mehr Basisklassen hinzu z. B. für Tests mit Datenbanken)
* Testklassen werden nach deren Pfad ab "tests/" benannt.
* Im Klassennamen Trennung der Ordner durch Unterstriche.
* Klassenname fängt groß an, nach jedem Unterstrich wieder groß.
* Bei "Test" wird auch der erste Buchstabe groß geschrieben.
* Der Pfad verglichen mit dem Klassennamen sieht wie folgt aus:

        tests/libraries/ilch/RequestTest.php => Libraries_Ilch_RequestTest

#### Testfunktionen

* Testfunktionen sind public.
* Testfunktionen starten mit "test".

```php
public function testSomeFunction()
```

### Best Practices

* Jeder Tests sollte einen Fall prüfen. Bei zuvielen Assertions im Tests ist schwerer nachzuvollziehen was wieso fehlschlägt.
* Grenzwerte Testen: Nimmt eine Funktion Integers von 0-100 an, macht es Sinn Tests mit 0, 1, -1, 99, 100, 101 zu schreiben da sich an solchen Stellen leicht Fehler einschleichen.
* Auch den Fehlerfall testen: Soll eine Funktion in einem bestimmten Fall eine Exception werfen kann man überprüfen ob dies zutrifft.
