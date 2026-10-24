<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Training\Models;

use PHPUnit\Framework\TestCase;
use Modules\Training\Models\Training as TrainingModel;

class TrainingModelTest extends TestCase
{
    /**
     * Tests that the default values are set as expected.
     */
    public function testDefaultValues()
    {
        $model = new TrainingModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getDate());
        self::assertSame('', $model->getEnd());
        self::assertSame(0, $model->getPeriodDay());
        self::assertSame('', $model->getPeriodType());
        self::assertSame('', $model->getRepeatUntil());
        self::assertSame('', $model->getPlace());
        self::assertSame(0, $model->getContact());
        self::assertFalse($model->getVoiceServer());
        self::assertSame('', $model->getVoiceServerIP());
        self::assertSame('', $model->getVoiceServerPW());
        self::assertFalse($model->getGameServer());
        self::assertSame('', $model->getGameServerIP());
        self::assertSame('', $model->getGameServerPW());
        self::assertSame('', $model->getText());
        self::assertFalse($model->getShow());
        self::assertSame('2,3', $model->getReadAccess());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new TrainingModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts a numeric string to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new TrainingModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new TrainingModel();
        $model->setTitle('Tag 1');

        self::assertSame('Tag 1', $model->getTitle());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new TrainingModel();
        $model->setDate('2024-01-15 05:00:00');

        self::assertSame('2024-01-15 05:00:00', $model->getDate());
    }

    /**
     * Tests that setEnd() sets and returns the end.
     */
    public function testSetEnd()
    {
        $model = new TrainingModel();
        $model->setEnd('2024-01-15 05:30:00');

        self::assertSame('2024-01-15 05:30:00', $model->getEnd());
    }

    /**
     * Tests that setPeriodType() sets and returns the period type.
     */
    public function testSetPeriodType()
    {
        $model = new TrainingModel();
        $model->setPeriodType('weekly');

        self::assertSame('weekly', $model->getPeriodType());
    }

    /**
     * Tests that setPeriodDay() sets and returns the period day.
     */
    public function testSetPeriodDay()
    {
        $model = new TrainingModel();
        $model->setPeriodDay(2);

        self::assertSame(2, $model->getPeriodDay());
    }

    /**
     * Tests that setPeriodDay() casts a numeric string to int.
     */
    public function testSetPeriodDayCastsToInt()
    {
        $model = new TrainingModel();
        $model->setPeriodDay('1');

        self::assertSame(1, $model->getPeriodDay());
        self::assertIsInt($model->getPeriodDay());
    }

    /**
     * Tests that setRepeatUntil() sets and returns the repeat until date.
     */
    public function testSetRepeatUntil()
    {
        $model = new TrainingModel();
        $model->setRepeatUntil('2024-12-31 23:59:59');

        self::assertSame('2024-12-31 23:59:59', $model->getRepeatUntil());
    }

    /**
     * Tests that setPlace() sets and returns the place.
     */
    public function testSetPlace()
    {
        $model = new TrainingModel();
        $model->setPlace('Stadion');

        self::assertSame('Stadion', $model->getPlace());
    }

    /**
     * Tests that setContact() sets and returns the contact.
     */
    public function testSetContact()
    {
        $model = new TrainingModel();
        $model->setContact(7);

        self::assertSame(7, $model->getContact());
    }

    /**
     * Tests that setContact() casts a numeric string to int.
     */
    public function testSetContactCastsToInt()
    {
        $model = new TrainingModel();
        $model->setContact('5');

        self::assertSame(5, $model->getContact());
        self::assertIsInt($model->getContact());
    }

    /**
     * Tests that setVoiceServer() sets and returns the voice server flag.
     */
    public function testSetVoiceServer()
    {
        $model = new TrainingModel();
        $model->setVoiceServer(true);

        self::assertTrue($model->getVoiceServer());

        $model->setVoiceServer(false);

        self::assertFalse($model->getVoiceServer());
    }

    /**
     * Tests that setVoiceServerIP() sets and returns the voice server IP.
     */
    public function testSetVoiceServerIP()
    {
        $model = new TrainingModel();
        $model->setVoiceServerIP('192.168.0.1');

        self::assertSame('192.168.0.1', $model->getVoiceServerIP());
    }

    /**
     * Tests that setVoiceServerPW() sets and returns the voice server password.
     */
    public function testSetVoiceServerPW()
    {
        $model = new TrainingModel();
        $model->setVoiceServerPW('secret');

        self::assertSame('secret', $model->getVoiceServerPW());
    }

    /**
     * Tests that setGameServer() sets and returns the game server flag.
     */
    public function testSetGameServer()
    {
        $model = new TrainingModel();
        $model->setGameServer(true);

        self::assertTrue($model->getGameServer());

        $model->setGameServer(false);

        self::assertFalse($model->getGameServer());
    }

    /**
     * Tests that setGameServerIP() sets and returns the game server IP.
     */
    public function testSetGameServerIP()
    {
        $model = new TrainingModel();
        $model->setGameServerIP('10.0.0.1');

        self::assertSame('10.0.0.1', $model->getGameServerIP());
    }

    /**
     * Tests that setGameServerPW() sets and returns the game server password.
     */
    public function testSetGameServerPW()
    {
        $model = new TrainingModel();
        $model->setGameServerPW('secret');

        self::assertSame('secret', $model->getGameServerPW());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new TrainingModel();
        $model->setText('Training text');

        self::assertSame('Training text', $model->getText());
    }

    /**
     * Tests that setShow() sets and returns the show flag.
     */
    public function testSetShow()
    {
        $model = new TrainingModel();
        $model->setShow(true);

        self::assertTrue($model->getShow());

        $model->setShow(false);

        self::assertFalse($model->getShow());
    }

    /**
     * Tests that the bool setters cast their input to bool.
     */
    public function testBoolSettersCastToBool()
    {
        $model = new TrainingModel();
        $model->setVoiceServer('1');
        $model->setGameServer(0);
        $model->setShow('1');

        self::assertTrue($model->getVoiceServer());
        self::assertFalse($model->getGameServer());
        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access, including 'all'.
     */
    public function testSetReadAccess()
    {
        $model = new TrainingModel();
        $model->setReadAccess('1,2');

        self::assertSame('1,2', $model->getReadAccess());

        $model->setReadAccess('all');

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new TrainingModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Title'));
        self::assertSame($model, $model->setDate('2024-01-15 05:00:00'));
        self::assertSame($model, $model->setEnd('2024-01-15 05:30:00'));
        self::assertSame($model, $model->setPeriodType('weekly'));
        self::assertSame($model, $model->setPeriodDay(1));
        self::assertSame($model, $model->setRepeatUntil('2024-12-31 23:59:59'));
        self::assertSame($model, $model->setPlace('Place'));
        self::assertSame($model, $model->setContact(2));
        self::assertSame($model, $model->setVoiceServer(true));
        self::assertSame($model, $model->setVoiceServerIP('ip'));
        self::assertSame($model, $model->setVoiceServerPW('pw'));
        self::assertSame($model, $model->setGameServer(true));
        self::assertSame($model, $model->setGameServerIP('ip'));
        self::assertSame($model, $model->setGameServerPW('pw'));
        self::assertSame($model, $model->setText('Text'));
        self::assertSame($model, $model->setShow(true));
        self::assertSame($model, $model->setReadAccess('1,2'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new TrainingModel())
            ->setId(3)
            ->setTitle('Title')
            ->setDate('2024-01-15 05:00:00')
            ->setEnd('2024-01-15 05:30:00')
            ->setPeriodType('weekly')
            ->setPeriodDay(1)
            ->setRepeatUntil('2024-12-31 23:59:59')
            ->setPlace('Place')
            ->setContact(1)
            ->setVoiceServer(true)
            ->setVoiceServerIP('192.168.0.1')
            ->setVoiceServerPW('pw')
            ->setGameServer(false)
            ->setGameServerIP('')
            ->setGameServerPW('')
            ->setText('Text')
            ->setShow(true)
            ->setReadAccess('all');

        self::assertSame(3, $model->getId());
        self::assertSame('Title', $model->getTitle());
        self::assertSame('2024-01-15 05:00:00', $model->getDate());
        self::assertSame('2024-01-15 05:30:00', $model->getEnd());
        self::assertSame('weekly', $model->getPeriodType());
        self::assertSame(1, $model->getPeriodDay());
        self::assertSame('2024-12-31 23:59:59', $model->getRepeatUntil());
        self::assertSame('Place', $model->getPlace());
        self::assertSame(1, $model->getContact());
        self::assertTrue($model->getVoiceServer());
        self::assertSame('192.168.0.1', $model->getVoiceServerIP());
        self::assertSame('pw', $model->getVoiceServerPW());
        self::assertFalse($model->getGameServer());
        self::assertSame('', $model->getGameServerIP());
        self::assertSame('', $model->getGameServerPW());
        self::assertSame('Text', $model->getText());
        self::assertTrue($model->getShow());
        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() maps a complete database row to the model.
     */
    public function testSetByArray()
    {
        $model = new TrainingModel();
        $model->setByArray([
            'id' => 5,
            'title' => 'Title',
            'date' => '2024-01-15 05:00:00',
            'end' => '2024-01-15 05:30:00',
            'period_type' => 'weekly',
            'period_day' => 2,
            'repeat_until' => '2024-12-31 23:59:59',
            'place' => 'Place',
            'contact' => 7,
            'voice_server' => 1,
            'voice_server_ip' => '192.168.0.1',
            'voice_server_pw' => 'pw',
            'game_server' => 0,
            'game_server_ip' => '',
            'game_server_pw' => '',
            'text' => 'Text',
            'show' => 1,
            'read_access' => '1,2',
            'access_all' => 0,
        ]);

        self::assertSame(5, $model->getId());
        self::assertSame('Title', $model->getTitle());
        self::assertSame('2024-01-15 05:00:00', $model->getDate());
        self::assertSame('2024-01-15 05:30:00', $model->getEnd());
        self::assertSame('weekly', $model->getPeriodType());
        self::assertSame(2, $model->getPeriodDay());
        self::assertSame('2024-12-31 23:59:59', $model->getRepeatUntil());
        self::assertSame('Place', $model->getPlace());
        self::assertSame(7, $model->getContact());
        self::assertTrue($model->getVoiceServer());
        self::assertSame('192.168.0.1', $model->getVoiceServerIP());
        self::assertSame('pw', $model->getVoiceServerPW());
        self::assertFalse($model->getGameServer());
        self::assertSame('', $model->getGameServerIP());
        self::assertSame('', $model->getGameServerPW());
        self::assertSame('Text', $model->getText());
        self::assertTrue($model->getShow());
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() sets readAccess to 'all' when access_all is set.
     */
    public function testSetByArrayAccessAll()
    {
        $model = new TrainingModel();
        $model->setByArray(['id' => 3, 'read_access' => '1', 'access_all' => 1]);

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() only overrides fields that are present in the array.
     */
    public function testSetByArrayPartial()
    {
        $model = new TrainingModel();
        $model->setByArray(['id' => 1, 'title' => 'Title']);

        self::assertSame(1, $model->getId());
        self::assertSame('Title', $model->getTitle());
        self::assertSame('', $model->getDate());
        self::assertSame('2,3', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new TrainingModel();

        self::assertSame($model, $model->setByArray(['id' => 1]));
    }

    /**
     * Tests that getArray() returns all fields including the id.
     */
    public function testGetArray()
    {
        $model = new TrainingModel();
        $model->setId(5);
        $model->setTitle('Title');
        $model->setDate('2024-01-15 05:00:00');
        $model->setEnd('2024-01-15 05:30:00');
        $model->setPeriodDay(1);
        $model->setPeriodType('weekly');
        $model->setRepeatUntil('2024-12-31 23:59:59');
        $model->setPlace('Place');
        $model->setContact(1);
        $model->setVoiceServer(false);
        $model->setVoiceServerIP('');
        $model->setVoiceServerPW('');
        $model->setGameServer(false);
        $model->setGameServerIP('');
        $model->setGameServerPW('');
        $model->setText('Text');
        $model->setShow(false);
        $model->setReadAccess('2,3');

        self::assertSame([
            'id' => 5,
            'title' => 'Title',
            'date' => '2024-01-15 05:00:00',
            'end' => '2024-01-15 05:30:00',
            'period_day' => 1,
            'period_type' => 'weekly',
            'repeat_until' => '2024-12-31 23:59:59',
            'place' => 'Place',
            'contact' => 1,
            'voice_server' => false,
            'voice_server_ip' => '',
            'voice_server_pw' => '',
            'game_server' => false,
            'game_server_ip' => '',
            'game_server_pw' => '',
            'text' => 'Text',
            'show' => false,
            'access_all' => 0,
        ], $model->getArray());
    }

    /**
     * Tests that getArray() can be called without the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new TrainingModel();
        $model->setId(5);

        self::assertArrayNotHasKey('id', $model->getArray(false));
        self::assertCount(17, $model->getArray(false));
    }

    /**
     * Tests that getArray() maps readAccess 'all' to access_all 1.
     */
    public function testGetArrayAccessAll()
    {
        $model = new TrainingModel();
        $model->setReadAccess('all');

        self::assertSame(1, $model->getArray()['access_all']);
    }
}
