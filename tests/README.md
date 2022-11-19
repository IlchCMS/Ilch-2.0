# PHPUnit in Ilch CMS 2.0

## Fragen bzgl. PHPUnit?
Hier gibts Antworten [PHPUnit](http://phpunit.de/manual/current/en/)


## Installation/Verwendung

1. **PHPUnit installieren.**
    PHPUnit kann über die development/bin/setup.sh installiert werden, unter Windows muss Composer manuell installiert
    werden, mehr dazu siehe in der development/README.md.

2. **Konfiguration bereitstellen.**
    * DB-Einstellungen für die Datenbank- und Controllertests werden über die *tests/config.php* geregelt
        * Für Datenbanktests werden entsprechende Configeinträge benötigt!

        ```php
        <?php
        // Config for tests
        $config["dbEngine"] = "Mysql";
        $config["dbHost"] = "localhost";
        $config["dbUser"] = "root";
        $config["dbPassword"] = "root";
        $config["dbName"] = "ilch2test";
        $config["dbPrefix"] = "";
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

        ../development/bin/phpunit #Führt alle Tests aus
        # in der VM kann phpunit "direkt" verwendet werden
        phpunit #Führt alle Tests aus
        phpunit --coverage-text . #Führt alle Tests aus und zeigt nen Code-Coverage Report in der Konsole
        phpunit libraries\ilch\RequestTest.php #Führt nur diese Testklasse aus


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
namespace Ilch;

use PHPUnit\Ilch\TestCase;

class SomeObjectTest extends TestCase
{
    public function testSomeFunctionReturnsNull()
    {
        $someObj = new SomeObject();
        $this->assertNull($someObj->someFunction());
    }
}
```

### \PHPUnit\Ilch\TestCase

Die \PHPUnit\Ilch\TestCase ist die Testklasse für Tests welche z. B. ein Model/Mapper/Ilch_libraryclass betreffen.
Sie erweitert die Standard PHPUnit TestCase lediglich um die Funktion, Configparameter vor dem Testlauf anzupassen.

Ein Beispiel: Setzen der Zeitzone um Zeitvergleiche konsistent über alle Serverstandorte ausführen zu können.

```php
namespace Ilch;

use PHPUnit\Ilch\TestCase;

class SomeObjectTest extends TestCase
{
    protected $configData = array
    (
        'timezone' => 'Europe/Berlin' // Filling the timezone which the Ilch_Date object will use.
    );

    ...
}
```

### Datenbank Tests - \PHPUnit\Ilch\DatabaseTestCase

Die Testklasse für Tests, welche eine reelle Datenbank verwenden, ist an der \PHPUnit\Ilch\DatabaseTestCase angelehnt.

Sie verfügt über dieselbe Funktionalität wie die \PHPUnit\Ilch\TestCase, erweitert diese allerdings um die
Möglichkeit gegen eine reelle Datenbank zu testen. Dazu müssen in den Testklassen die Funktion getDataSet()
überschrieben werden und optional die getSchemaSQLQueries(), um eine Datenbankstruktur zu erzeugen.

```php
namespace User\Mapper;

use PHPUnit\Ilch\DatabaseTestCase;

class UserTest extends DatabaseTestCase
{
    public function getDataSet()
    {
        return new \PHPUnit\DbUnit\DataSet\YamlDataSet
        (
            __DIR__.'/_files/users.yml'
        );
    }

    public function getSchemaSQLQueries()
    {
        return 'CREATE TABLE ...; CREATE TABLE ...; ...'
    }

    public function testIndex()
    {
        // ...
    }
}
```

Das DataSet kann auf verschiedene Art und Weise generiert werden, in meinem Beispiel als YAML-Datei,
dazu allerdings näheres auf der [PHPUnit Dokumentation](http://phpunit.de/manual/current/en/database.html#database.implementing-getdataset).

#### Klassennamen

* Der Name von Testklassen endet mit "Test".
* Testklassen sollten von \PHPUnit\Ilch\TestCase (oder andere \PHPUnit\Ilch\*TestCase Klasse) ableiten (liegt im Ordner "tests/").
* Testklassen werden wie die Klasse, die sie Testen sollen benannt, mit "Test" am Ende.

        tests/libraries/ilch/RequestTest.php => \Ilch\RequestTest

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
