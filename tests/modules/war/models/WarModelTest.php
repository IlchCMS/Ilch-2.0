<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\War as WarModel;

class WarModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new WarModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new WarModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId() rejects non-numeric strings.
     */
    public function testSetIdRejectsNonNumericString()
    {
        $this->expectException(\TypeError::class);

        (new WarModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new WarModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setWarEnemy() sets and returns the enemy id.
     */
    public function testSetWarEnemy()
    {
        $model = new WarModel();
        $model->setWarEnemy(2);

        self::assertSame(2, $model->getWarEnemy());
    }

    /**
     * Tests that setWarEnemy() casts numeric strings to int.
     */
    public function testSetWarEnemyCastsToInt()
    {
        $model = new WarModel();
        $model->setWarEnemy('7');

        self::assertSame(7, $model->getWarEnemy());
        self::assertIsInt($model->getWarEnemy());
    }

    /**
     * Tests that setWarEnemyTag() sets and returns the enemy tag.
     */
    public function testSetWarEnemyTag()
    {
        $model = new WarModel();
        $model->setWarEnemyTag('DMS');

        self::assertSame('DMS', $model->getWarEnemyTag());
    }

    /**
     * Tests that setWarGroup() sets and returns the group id.
     */
    public function testSetWarGroup()
    {
        $model = new WarModel();
        $model->setWarGroup(3);

        self::assertSame(3, $model->getWarGroup());
    }

    /**
     * Tests that setWarGroupTag() sets and returns the group tag.
     */
    public function testSetWarGroupTag()
    {
        $model = new WarModel();
        $model->setWarGroupTag('FriendlyFire');

        self::assertSame('FriendlyFire', $model->getWarGroupTag());
    }

    /**
     * Tests that setWarTime() sets and returns the time.
     */
    public function testSetWarTime()
    {
        $model = new WarModel();
        $model->setWarTime('2024-06-01 20:00:00');

        self::assertSame('2024-06-01 20:00:00', $model->getWarTime());
    }

    /**
     * Tests that setWarTime() casts to string.
     */
    public function testSetWarTimeCastsToString()
    {
        $model = new WarModel();
        $model->setWarTime(1717200000);

        self::assertSame('1717200000', $model->getWarTime());
        self::assertIsString($model->getWarTime());
    }

    /**
     * Tests that setWarMaps() sets and returns the maps.
     */
    public function testSetWarMaps()
    {
        $model = new WarModel();
        $model->setWarMaps('1,2');

        self::assertSame('1,2', $model->getWarMaps());
    }

    /**
     * Tests that setWarServer() sets and returns the server.
     */
    public function testWarServer()
    {
        $model = new WarModel();
        $model->setWarServer('example.com:27015');

        self::assertSame('example.com:27015', $model->getWarServer());
    }

    /**
     * Tests that setWarPassword() sets and returns the password.
     */
    public function testSetWarPassword()
    {
        $model = new WarModel();
        $model->setWarPassword('secret');

        self::assertSame('secret', $model->getWarPassword());
    }

    /**
     * Tests that setWarXonx() sets and returns the xonx value.
     */
    public function testSetWarXonx()
    {
        $model = new WarModel();
        $model->setWarXonx('1v1');

        self::assertSame('1v1', $model->getWarXonx());
    }

    /**
     * Tests that setWarGame() sets and returns the game.
     */
    public function testSetWarGame()
    {
        $model = new WarModel();
        $model->setWarGame('cs2');

        self::assertSame('cs2', $model->getWarGame());
    }

    /**
     * Tests that setWarMatchtype() sets and returns the matchtype.
     */
    public function testSetWarMatchtype()
    {
        $model = new WarModel();
        $model->setWarMatchtype('5vs5');

        self::assertSame('5vs5', $model->getWarMatchtype());
    }

    /**
     * Tests that setWarReport() sets and returns the report.
     */
    public function testSetWarReport()
    {
        $model = new WarModel();
        $model->setWarReport('We won the first map.');

        self::assertSame('We won the first map.', $model->getWarReport());
    }

    /**
     * Tests that setWarReport() casts to string.
     */
    public function testSetWarReportCastsToString()
    {
        $model = new WarModel();
        $model->setWarReport(123);

        self::assertSame('123', $model->getWarReport());
        self::assertIsString($model->getWarReport());
    }

    /**
     * Tests that setWarStatus() sets and returns the status.
     */
    public function testSetWarStatus()
    {
        $model = new WarModel();
        $model->setWarStatus(0);

        self::assertSame(0, $model->getWarStatus());
    }

    /**
     * Tests that setShow() sets and returns the show flag.
     */
    public function testSetShow()
    {
        $model = new WarModel();
        $model->setShow(1);

        self::assertSame(1, $model->getShow());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access.
     */
    public function testSetReadAccess()
    {
        $model = new WarModel();
        $model->setReadAccess('all');

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setLastAcceptTime() sets and returns the last accept time.
     */
    public function testSetLastAcceptTime()
    {
        $model = new WarModel();
        $model->setLastAcceptTime(1717200000);

        self::assertSame(1717200000, $model->getLastAcceptTime());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new WarModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setWarEnemy(2));
        self::assertSame($model, $model->setWarEnemyTag('DMS'));
        self::assertSame($model, $model->setWarGroup(3));
        self::assertSame($model, $model->setWarGroupTag('FriendlyFire'));
        self::assertSame($model, $model->setWarTime('2024-06-01 20:00:00'));
        self::assertSame($model, $model->setWarMaps('1,2'));
        self::assertSame($model, $model->setWarServer('example.com:27015'));
        self::assertSame($model, $model->setWarPassword('secret'));
        self::assertSame($model, $model->setWarXonx('1v1'));
        self::assertSame($model, $model->setWarGame('cs2'));
        self::assertSame($model, $model->setWarMatchtype('5vs5'));
        self::assertSame($model, $model->setWarReport('Report'));
        self::assertSame($model, $model->setWarStatus(1));
        self::assertSame($model, $model->setShow(1));
        self::assertSame($model, $model->setReadAccess('all'));
        self::assertSame($model, $model->setLastAcceptTime(1717200000));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new WarModel())
            ->setId(15)
            ->setWarEnemy(2)
            ->setWarEnemyTag('DMS')
            ->setWarGroup(3)
            ->setWarGroupTag('FriendlyFire')
            ->setWarTime('2024-06-01 20:00:00')
            ->setWarMaps('1,2')
            ->setWarServer('example.com:27015')
            ->setWarPassword('secret')
            ->setWarXonx('1v1')
            ->setWarGame('cs2')
            ->setWarMatchtype('5vs5')
            ->setWarReport('We won the first map.')
            ->setWarStatus(1)
            ->setShow(1)
            ->setReadAccess('all')
            ->setLastAcceptTime(1717200000);

        self::assertSame(15, $model->getId());
        self::assertSame(2, $model->getWarEnemy());
        self::assertSame('DMS', $model->getWarEnemyTag());
        self::assertSame(3, $model->getWarGroup());
        self::assertSame('FriendlyFire', $model->getWarGroupTag());
        self::assertSame('2024-06-01 20:00:00', $model->getWarTime());
        self::assertSame('1,2', $model->getWarMaps());
        self::assertSame('example.com:27015', $model->getWarServer());
        self::assertSame('secret', $model->getWarPassword());
        self::assertSame('1v1', $model->getWarXonx());
        self::assertSame('cs2', $model->getWarGame());
        self::assertSame('5vs5', $model->getWarMatchtype());
        self::assertSame('We won the first map.', $model->getWarReport());
        self::assertSame(1, $model->getWarStatus());
        self::assertSame(1, $model->getShow());
        self::assertSame('all', $model->getReadAccess());
        self::assertSame(1717200000, $model->getLastAcceptTime());
    }

    /**
     * Tests the default values of a fresh model.
     */
    public function testDefaultValues()
    {
        $model = new WarModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarEnemy());
        self::assertSame('', $model->getWarEnemyTag());
        self::assertSame(0, $model->getWarGroup());
        self::assertSame('', $model->getWarGroupTag());
        self::assertSame('', $model->getWarTime());
        self::assertSame('', $model->getWarMaps());
        self::assertSame('', $model->getWarServer());
        self::assertSame('', $model->getWarPassword());
        self::assertSame('new', $model->getWarXonx());
        self::assertSame('new', $model->getWarGame());
        self::assertSame('new', $model->getWarMatchtype());
        self::assertSame('', $model->getWarReport());
        self::assertSame(1, $model->getWarStatus());
        self::assertSame(0, $model->getShow());
        self::assertSame('', $model->getReadAccess());
        self::assertSame(0, $model->getLastAcceptTime());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new WarModel();
        $model->setId(1)->setWarEnemy(1)->setWarGroup(1)->setWarTime('2024-01-01 00:00:00')->setWarStatus(0)->setShow(0)->setReadAccess('all');

        $model->setId(2)->setWarEnemy(2)->setWarGroup(2)->setWarTime('2024-06-01 20:00:00')->setWarStatus(1)->setShow(1)->setReadAccess('1,2');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getWarEnemy());
        self::assertSame(2, $model->getWarGroup());
        self::assertSame('2024-06-01 20:00:00', $model->getWarTime());
        self::assertSame(1, $model->getWarStatus());
        self::assertSame(1, $model->getShow());
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests setByArray() with all keys, including the joined tag columns.
     */
    public function testSetByArrayFull()
    {
        $model = new WarModel();
        $model->setByArray([
            'id' => 15,
            'enemy' => 2,
            'group' => 3,
            'time' => '2024-06-01 20:00:00',
            'maps' => '1,2',
            'server' => 'example.com:27015',
            'password' => 'secret',
            'xonx' => '1v1',
            'game' => 'cs2',
            'matchtype' => '5vs5',
            'report' => 'We won the first map.',
            'status' => 1,
            'show' => 1,
            'lastaccepttime' => 1717200000,
            'read_access_all' => 1,
            'war_groups' => 'FriendlyFire',
            'war_enemy' => 'DMS',
        ]);

        self::assertSame(15, $model->getId());
        self::assertSame(2, $model->getWarEnemy());
        self::assertSame(3, $model->getWarGroup());
        self::assertSame('2024-06-01 20:00:00', $model->getWarTime());
        self::assertSame('1,2', $model->getWarMaps());
        self::assertSame('example.com:27015', $model->getWarServer());
        self::assertSame('secret', $model->getWarPassword());
        self::assertSame('1v1', $model->getWarXonx());
        self::assertSame('cs2', $model->getWarGame());
        self::assertSame('5vs5', $model->getWarMatchtype());
        self::assertSame('We won the first map.', $model->getWarReport());
        self::assertSame(1, $model->getWarStatus());
        self::assertSame(1, $model->getShow());
        self::assertSame(1717200000, $model->getLastAcceptTime());
        self::assertSame('all', $model->getReadAccess());
        self::assertSame('FriendlyFire', $model->getWarGroupTag());
        self::assertSame('DMS', $model->getWarEnemyTag());
    }

    /**
     * Tests that setByArray() casts numeric string values (as returned by the DB).
     */
    public function testSetByArrayCastsValues()
    {
        $model = (new WarModel())->setByArray([
            'id' => '15',
            'enemy' => '2',
            'group' => '3',
            'status' => '1',
            'show' => '1',
            'lastaccepttime' => '1717200000',
        ]);

        self::assertSame(15, $model->getId());
        self::assertSame(2, $model->getWarEnemy());
        self::assertSame(3, $model->getWarGroup());
        self::assertSame(1, $model->getWarStatus());
        self::assertSame(1, $model->getShow());
        self::assertSame(1717200000, $model->getLastAcceptTime());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new WarModel())->setByArray(['time' => '2024-06-01 20:00:00']);

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getWarEnemy());
        self::assertSame('2024-06-01 20:00:00', $model->getWarTime());
        self::assertSame('new', $model->getWarXonx());
        self::assertSame(1, $model->getWarStatus());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new WarModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame(0, $model->getWarEnemy());
        self::assertSame('', $model->getWarReport());
    }

    /**
     * Tests that read_access_all = 1 maps to readAccess 'all'.
     */
    public function testSetByArrayReadAccessAllTrue()
    {
        $model = (new WarModel())->setByArray(['read_access_all' => 1]);

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Documents current behavior: read_access_all = 0 does NOT clear a previously set readAccess.
     */
    public function testSetByArrayReadAccessAllFalseDoesNotClear()
    {
        $model = (new WarModel())->setReadAccess('all');

        $model->setByArray(['read_access_all' => 0]);

        self::assertSame('all', $model->getReadAccess());
        self::assertSame(1, $model->getArray()['read_access_all']);
    }

    /**
     * Documents current behavior: a legacy comma list in read_access is stored but never exposed by getArray().
     */
    public function testGetArrayDoesNotExposeLegacyReadAccess()
    {
        $model = (new WarModel())->setByArray(['read_access' => '1,2,3']);

        self::assertSame('1,2,3', $model->getReadAccess());

        $array = $model->getArray();

        self::assertArrayNotHasKey('read_access', $array);
        self::assertSame(0, $array['read_access_all']);
    }

    /**
     * Tests that the joined tag columns are read-only view fields.
     */
    public function testGetArrayExcludesJoinedTags()
    {
        $model = (new WarModel())->setWarGroupTag('FriendlyFire')->setWarEnemyTag('DMS');

        $array = $model->getArray();

        self::assertArrayNotHasKey('war_groups', $array);
        self::assertArrayNotHasKey('war_enemy', $array);
    }

    /**
     * Tests that getArray() returns id and all persisted fields.
     */
    public function testGetArrayWithId()
    {
        $model = new WarModel();
        $model->setId(15)
            ->setWarEnemy(2)
            ->setWarGroup(3)
            ->setWarTime('2024-06-01 20:00:00')
            ->setWarMaps('1,2')
            ->setWarServer('example.com:27015')
            ->setWarPassword('secret')
            ->setWarXonx('1v1')
            ->setWarGame('cs2')
            ->setWarMatchtype('5vs5')
            ->setWarReport('We won the first map.')
            ->setWarStatus(1)
            ->setShow(1)
            ->setLastAcceptTime(1717200000)
            ->setReadAccess('all');

        self::assertSame(
            [
                'id' => 15,
                'enemy' => 2,
                'group' => 3,
                'time' => '2024-06-01 20:00:00',
                'maps' => '1,2',
                'server' => 'example.com:27015',
                'password' => 'secret',
                'xonx' => '1v1',
                'game' => 'cs2',
                'matchtype' => '5vs5',
                'report' => 'We won the first map.',
                'status' => 1,
                'show' => 1,
                'lastaccepttime' => 1717200000,
                'read_access_all' => 1,
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new WarModel();
        $model->setWarEnemy(2)
            ->setWarGroup(3)
            ->setWarTime('2024-06-01 20:00:00')
            ->setWarMaps('1,2')
            ->setWarServer('example.com:27015')
            ->setWarPassword('secret')
            ->setWarXonx('1v1')
            ->setWarGame('cs2')
            ->setWarMatchtype('5vs5')
            ->setWarReport('')
            ->setWarStatus(0)
            ->setShow(0)
            ->setLastAcceptTime(0);

        self::assertSame(
            [
                'enemy' => 2,
                'group' => 3,
                'time' => '2024-06-01 20:00:00',
                'maps' => '1,2',
                'server' => 'example.com:27015',
                'password' => 'secret',
                'xonx' => '1v1',
                'game' => 'cs2',
                'matchtype' => '5vs5',
                'report' => '',
                'status' => 0,
                'show' => 0,
                'lastaccepttime' => 0,
                'read_access_all' => 0,
            ],
            $model->getArray(false)
        );
    }

    /**
     * Tests the readAccess to read_access_all mapping in getArray().
     */
    public function testGetArrayReadAccessAllMapping()
    {
        $model = new WarModel();

        $model->setReadAccess('all');
        self::assertSame(1, $model->getArray()['read_access_all']);

        $model->setReadAccess('1,2');
        self::assertSame(0, $model->getArray()['read_access_all']);

        $model->setReadAccess('');
        self::assertSame(0, $model->getArray()['read_access_all']);
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations for persisted fields.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 15,
            'enemy' => 2,
            'group' => 3,
            'time' => '2024-06-01 20:00:00',
            'maps' => '1,2',
            'server' => 'example.com:27015',
            'password' => 'secret',
            'xonx' => '1v1',
            'game' => 'cs2',
            'matchtype' => '5vs5',
            'report' => '',
            'status' => 1,
            'show' => 1,
            'lastaccepttime' => 1717200000,
            'read_access_all' => 1,
        ];

        $model = (new WarModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
