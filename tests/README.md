# PHPUnit in Ilch CMS 2.0

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
class Libraries_Ilch_SomeObjectTest extends PHPUnit_Ilch_TestCase
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

### Controller Tests

Beim Testen von Controllern sollte die Klasse PHPUnit_Ilch_Controller_TestCase verwendet werden.

Diese initialiert eine Umgebung für den Controller mittels Ilch_Page und stößt eine Action an.
Dabei wird mittels setAction($action) die gewünschte Action spezifiziert (Standard ist "index").
Die Funktion load() stößt dann die Action an und füllt die Variablen für den Output, die View und den Request.

Eine Testklasse kann folgendermaßen aussehen:

```php
/**
 * Tests the userlist controller.
 *
 * @author <author>
 * @package ilch_phpunit
 */
class Modules_User_Controllers_UserlistTest extends PHPUnit_Ilch_Controller_TestCase
{
    /**
     * Tests if the userlist contains the correct user data.
     */
    public function testIndex()
    {
        $this->setRequestParam('selectedUser', '2');
        $this->setAction('index');

        $this->load();

        // Checking if a specific id exists.
        $this->_assertActionOutputExpr('userlist_edit_button'); // Checks the output of the action.

        $this->_assertViewParamExists('selectedUserId'); // Checks if a specific view parameter exists.
        $this->_assertViewParamEquals('userlist', array(1, 2, 3)); // Checks if the view parameter "userlist" contains all user ids.
        $this->_assertRequestParamExists('selectedUser'); // Checks if the request parameter "selectedUser" exists.
        $this->_assertRequestParamEquals('selectedUser', '2'); // Checks if the request parameter "selectedUser" exists.
    }
}
```

#### Klassennamen

* Der Name von Testklassen endet mit "Test".
* Testklassen sollten von PHPUnit_Ilch_TestCase ableiten (liegt im Ordner "tests/"). (Evtl. kommen in Zukunft noch mehr Basisklassen hinzu z. B. für Tests mit Datenbanken)
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
