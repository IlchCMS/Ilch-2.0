# PHPUnit in Ilch CMS 2.0

## Fragen bzgl. PHPUnit?
Hier gibts Antworten [PHPUnit](http://phpunit.de/manual/current/en/)


## Installation/Verwendung

1. **PHPUnit installieren.** Eine gute Anleitung für Xampp ist [hier](http://web-union.de/484) zu finden.
Für die Controller- bzw. Datenbanktests wird zusätzlich noch das DB-Modul von PHPUnit benötigt: "pear install phpunit/DbUnit"

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

### PHPUnit_Ilch_TestCase

Die PHPUnit_Ilch_TestCase ist die Testklasse für Tests welche z. B. ein Model/Mapper/Ilch_libraryclass betreffen.
Sie erweitert die Standard PHPUnit TestCase lediglich um die Funktion, Configparameter vor dem Testlauf anzupassen.

Ein Beispiel: Setzen der Zeitzone um Zeitvergleiche konsistent über alle Serverstandorte ausführen zu können.

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
     * Filling the timezone which the Ilch_Date object will use.
     *
     * @var Array
     */
    protected $_configData = array
    (
        'timezone' => 'Europe/Berlin'
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
/**
 * Tests the user index controller.
 *
 * @author <author>
 * @package ilch_phpunit
 */
class Modules_User_Controllers_Admin_IndexTest extends PHPUnit_Ilch_Controller_TestCase
{
    /**
     * Returns the dataset which fills user data into the tables users, groups and users_groups.
     *
     * @return PHPUnit_Extensions_Database_DataSet_YamlDataSet
     */
    public function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet
        (
            __DIR__.'/../_files/users.yml'
        );
    }

    /**
     * Checks if the index action collects all users.
     */
    public function testIndex()
    {
        $this->setRequestParam('test', 'teststr');
        $this->setAction('index');

        $this->load();

        $this->_assertTransUsed('menuUser');

        $this->_assertViewParamExists('userList');
        $users = $this->_getViewParam('userList');
        $this->assertCount(3, $users, 'Not correct number of users objects was provided.');
    }
}
```

Das DataSet kann auf verschiedene Art und Weise generiert werden, in meinem Beispiel als YAML-Datei, dazu allerdings näheres auf der [PHPUnit Dokumentation](http://phpunit.de/manual/3.8/en/database.html#database.understanding-datasets-and-datatables).

### Controller Tests

Beim Testen von Controllern sollte die Klasse PHPUnit_Ilch_Controller_TestCase verwendet werden.

Diese initialiert eine Umgebung für den Controller mittels Ilch_Page und stößt eine Action an.
Dabei wird mittels setAction($action) die gewünschte Action spezifiziert (Standard ist "index").
Die Funktion load() stößt dann die Action an und füllt die Variablen für den Output, die View und den Request.

Neben dessen wird bei der PHPUnit_Ilch_Controller_TestCase auch eine reelle Datenbank verwendet. Deshalb muss auch hier
getDataSet() initialisiert werden damit der Test erfolgreich durchlaufen kann.

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
