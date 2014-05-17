# PHPUnit in Ilch CMS 2.0

## Fragen bzgl. PHPUnit?
Hier gibts Antworten [PHPUnit](http://phpunit.de/manual/current/en/)


## Installation/Verwendung

1. **PHPUnit installieren.** Eine gute Anleitung für Xampp ist [hier](http://web-union.de/484) zu finden.
Für die Controller- bzw. Datenbanktests wird zusätzlich noch das DB-Modul von PHPUnit benötigt: "pear install phpunit/DbUnit"

2. **Konfiguration bereitstellen.**
    * DB-Einstellungen für die Datenbank- und Controllertests werden über die *config.php* geregelt.
        * Für Datenbanktests werden alle Configeinträge mit dem Suffix "Test" benötigt!

        ```php
$config["dbEngine"] = "Mysql";
$config["dbHost"] = "localhost";
$config["dbUser"] = "ilch2";
$config["dbPassword"] = "";
$config["dbName"] = "ilch2";
$config["dbPrefix"] = "";
$config["dbEngineTest"] = "Mysql"; // Config for tests
$config["dbHostTest"] = "localhost"; // Config for tests
$config["dbUserTest"] = "ilch2test"; // Config for tests
$config["dbPasswordTest"] = "ilch2test"; // Config for tests
$config["dbNameTest"] = "ilch2test"; // Config for tests
$config["dbPrefixTest"] = ""; // Config for tests
        ```

3. **xDebug auf dem Server aktivieren.**
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

4. **Im Ordner tests/ folgendes ausführen:**

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
class Libraries_Ilch_SomeObjectTest extends PHPUnit_Ilch_TestCase
{
    public function testSomeFunctionReturnsNull()
    {
        $someObj = new Some_Object();
        $this->assertNull($someObj->someFunction());
    }
}
```

### PHPUnit_Ilch_TestCase

Die PHPUnit_Ilch_TestCase ist die Testklasse für Tests welche z. B. ein Model/Mapper/Ilch_libraryclass betreffen.
Sie erweitert die Standard PHPUnit TestCase lediglich um die Funktion, Configparameter vor dem Testlauf anzupassen.

Ein Beispiel: Setzen der Zeitzone um Zeitvergleiche konsistent über alle Serverstandorte ausführen zu können.

```php
class Libraries_Ilch_SomeObjectTest extends PHPUnit_Ilch_TestCase
{
    protected $configData = array
    (
        'timezone' => 'Europe/Berlin' // Filling the timezone which the Ilch_Date object will use.
    );

    ...
}
```

### Datenbank Tests

Die Testklasse für Tests welche eine reelle Datenbank verwenden ist an der PHPUnit_Ilch_TestCase angelehnt.

Sie verfügt über dieselbe Funktionalität wie die PHPUnit_Ilch_TestCase, erweitert diese allerdings um die
Möglichkeit gegen eine reelle Datenbank zu testen. Dazu müssen in den Testklassen die Funktion getDataSet()
initialisiert werden.

```php
class Modules_User_Mappers_UserTest extends PHPUnit_Ilch_DatabaseTestCase
{
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet
        (
            __DIR__.'/_files/users.yml'
        );
    }

    public function testIndex()
    {
        // ...
    }
}
```

Das DataSet kann auf verschiedene Art und Weise generiert werden, in meinem Beispiel als YAML-Datei, dazu allerdings näheres auf der [PHPUnit Dokumentation](http://phpunit.de/manual/3.8/en/database.html#database.understanding-datasets-and-datatables).

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
