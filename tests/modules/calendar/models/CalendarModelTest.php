<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Calendar\Models;

use PHPUnit\Framework\TestCase;

class CalendarModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Calendar();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Calendar();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new Calendar();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUid() sets and returns the uid.
     */
    public function testSetUid()
    {
        $model = new Calendar();
        $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc1');

        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc1', $model->getUid());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new Calendar();
        $model->setTitle('Training 1');

        self::assertSame('Training 1', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new Calendar();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setPlace() sets and returns the place.
     */
    public function testSetPlace()
    {
        $model = new Calendar();
        $model->setPlace('Room 1');

        self::assertSame('Room 1', $model->getPlace());
    }

    /**
     * Tests that setStart() sets and returns the start date.
     */
    public function testSetStart()
    {
        $model = new Calendar();
        $model->setStart('2021-05-09 08:10:38');

        self::assertSame('2021-05-09 08:10:38', $model->getStart());
    }

    /**
     * Tests that setEnd() sets and returns the end date.
     */
    public function testSetEnd()
    {
        $model = new Calendar();
        $model->setEnd('2021-05-09 21:00:00');

        self::assertSame('2021-05-09 21:00:00', $model->getEnd());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new Calendar();
        $model->setText('Some text');

        self::assertSame('Some text', $model->getText());
    }

    /**
     * Tests that setColor() sets and returns the color.
     */
    public function testSetColor()
    {
        $model = new Calendar();
        $model->setColor('#32333B');

        self::assertSame('#32333B', $model->getColor());
    }

    /**
     * Tests that setPeriodDay() sets and returns the period day.
     */
    public function testSetPeriodDay()
    {
        $model = new Calendar();
        $model->setPeriodDay(7);

        self::assertSame(7, $model->getPeriodDay());
    }

    /**
     * Tests that setPeriodDay() casts to int.
     */
    public function testSetPeriodDayCastsToInt()
    {
        $model = new Calendar();
        $model->setPeriodDay('7');

        self::assertSame(7, $model->getPeriodDay());
        self::assertIsInt($model->getPeriodDay());
    }

    /**
     * Tests that setPeriodType() sets and returns the period type.
     */
    public function testSetPeriodType()
    {
        $model = new Calendar();
        $model->setPeriodType('weekly');

        self::assertSame('weekly', $model->getPeriodType());
    }

    /**
     * Tests that setRepeatUntil() sets and returns the repeat-until date.
     */
    public function testSetRepeatUntil()
    {
        $model = new Calendar();
        $model->setRepeatUntil('2021-12-31 21:00:00');

        self::assertSame('2021-12-31 21:00:00', $model->getRepeatUntil());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access.
     */
    public function testSetReadAccess()
    {
        $model = new Calendar();
        $model->setReadAccess('all');

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Calendar();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc1'));
        self::assertSame($model, $model->setTitle('Training 1'));
        self::assertSame($model, $model->setPlace('Room 1'));
        self::assertSame($model, $model->setStart('2021-05-09 08:10:38'));
        self::assertSame($model, $model->setEnd('2021-05-09 08:10:38'));
        self::assertSame($model, $model->setText('Some text'));
        self::assertSame($model, $model->setColor('#32333B'));
        self::assertSame($model, $model->setPeriodDay(1));
        self::assertSame($model, $model->setReadAccess('all'));
        self::assertSame($model, $model->setPeriodType('weekly'));
        self::assertSame($model, $model->setRepeatUntil('2021-12-31 21:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Calendar())
            ->setId(4)
            ->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc4')
            ->setTitle('Training 4')
            ->setPlace('Room 1')
            ->setStart('2021-05-10 18:00:00')
            ->setEnd('2021-05-10 21:00:00')
            ->setText('Some text')
            ->setColor('#32333B')
            ->setPeriodDay(1)
            ->setReadAccess('all')
            ->setPeriodType('weekly')
            ->setRepeatUntil('2021-12-31 21:00:00');

        self::assertSame(4, $model->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc4', $model->getUid());
        self::assertSame('Training 4', $model->getTitle());
        self::assertSame('Room 1', $model->getPlace());
        self::assertSame('2021-05-10 18:00:00', $model->getStart());
        self::assertSame('2021-05-10 21:00:00', $model->getEnd());
        self::assertSame('Some text', $model->getText());
        self::assertSame('#32333B', $model->getColor());
        self::assertSame(1, $model->getPeriodDay());
        self::assertSame('all', $model->getReadAccess());
        self::assertSame('weekly', $model->getPeriodType());
        self::assertSame('2021-12-31 21:00:00', $model->getRepeatUntil());
    }

    /**
     * Tests that default values are 0 for id/periodDay and empty string for the rest.
     */
    public function testDefaultValues()
    {
        $model = new Calendar();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getUid());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getPlace());
        self::assertSame('', $model->getStart());
        self::assertSame('', $model->getEnd());
        self::assertSame('', $model->getText());
        self::assertSame('', $model->getColor());
        self::assertSame(0, $model->getPeriodDay());
        self::assertSame('', $model->getReadAccess());
        self::assertSame('', $model->getPeriodType());
        self::assertSame('', $model->getRepeatUntil());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = (new Calendar())
            ->setId(1)
            ->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc1')
            ->setTitle('Old Title')
            ->setStart('2021-05-09 08:10:38')
            ->setPeriodDay(0)
            ->setReadAccess('all');

        $model->setId(2)
            ->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc2')
            ->setTitle('New Title')
            ->setStart('2021-05-10 09:10:38')
            ->setPeriodDay(1)
            ->setReadAccess('1,2');

        self::assertSame(2, $model->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc2', $model->getUid());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('2021-05-10 09:10:38', $model->getStart());
        self::assertSame(1, $model->getPeriodDay());
        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests that getArray() returns all fields including the id.
     */
    public function testGetArray()
    {
        $model = (new Calendar())
            ->setId(4)
            ->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc4')
            ->setTitle('Training 4')
            ->setPlace('Room 1')
            ->setStart('2021-05-10 18:00:00')
            ->setEnd('2021-05-10 21:00:00')
            ->setText('Some text')
            ->setColor('#32333B')
            ->setPeriodDay(1)
            ->setPeriodType('weekly')
            ->setRepeatUntil('2021-12-31 21:00:00')
            ->setReadAccess('all');

        self::assertSame(
            [
                'id' => 4,
                'uid' => '39efcc37-43dd-43b7-923a-06c4a0d98fc4',
                'title' => 'Training 4',
                'place' => 'Room 1',
                'start' => '2021-05-10 18:00:00',
                'end' => '2021-05-10 21:00:00',
                'text' => 'Some text',
                'color' => '#32333B',
                'period_day' => 1,
                'period_type' => 'weekly',
                'repeat_until' => '2021-12-31 21:00:00',
                'read_access_all' => 1,
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray() returns default fields for a fresh model.
     */
    public function testGetArrayDefaults()
    {
        $model = new Calendar();

        self::assertSame(
            [
                'id' => 0,
                'uid' => '',
                'title' => '',
                'place' => '',
                'start' => '',
                'end' => '',
                'text' => '',
                'color' => '',
                'period_day' => 0,
                'period_type' => '',
                'repeat_until' => '',
                'read_access_all' => 0,
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new Calendar())->setId(1)->setTitle('Training 1');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('Training 1', $array['title']);
    }

    /**
     * Tests that read_access_all is 1 when read access is 'all'.
     */
    public function testGetArrayReadAccessAllIsOneForAll()
    {
        $model = (new Calendar())->setReadAccess('all');

        self::assertSame(1, $model->getArray()['read_access_all']);
    }

    /**
     * Tests that read_access_all is 0 for any other read access value.
     */
    public function testGetArrayReadAccessAllIsZeroOtherwise()
    {
        $model = new Calendar();
        self::assertSame(0, $model->getArray()['read_access_all']);

        $model->setReadAccess('1,2,3');
        self::assertSame(0, $model->getArray()['read_access_all']);
    }

    /**
     * Tests that setByArray() sets all fields from a complete array.
     */
    public function testSetByArray()
    {
        $model = (new Calendar())->setByArray(
            [
                'id' => 4,
                'uid' => '39efcc37-43dd-43b7-923a-06c4a0d98fc4',
                'title' => 'Training 4',
                'place' => 'Room 1',
                'start' => '2021-05-10 18:00:00',
                'end' => '2021-05-10 21:00:00',
                'text' => 'Some text',
                'color' => '#32333B',
                'period_day' => 1,
                'period_type' => 'weekly',
                'repeat_until' => '2021-12-31 21:00:00',
                'read_access_all' => 1,
            ]
        );

        self::assertSame(4, $model->getId());
        self::assertSame('39efcc37-43dd-43b7-923a-06c4a0d98fc4', $model->getUid());
        self::assertSame('Training 4', $model->getTitle());
        self::assertSame('Room 1', $model->getPlace());
        self::assertSame('2021-05-10 18:00:00', $model->getStart());
        self::assertSame('2021-05-10 21:00:00', $model->getEnd());
        self::assertSame('Some text', $model->getText());
        self::assertSame('#32333B', $model->getColor());
        self::assertSame(1, $model->getPeriodDay());
        self::assertSame('weekly', $model->getPeriodType());
        self::assertSame('2021-12-31 21:00:00', $model->getRepeatUntil());
        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that setByArray() only changes provided keys and keeps defaults.
     */
    public function testSetByArrayPartialKeepsDefaults()
    {
        $model = (new Calendar())->setByArray(['title' => 'Only Title']);

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getUid());
        self::assertSame('Only Title', $model->getTitle());
    }

    /**
     * Tests that setByArray() sets read access from the legacy read_access key.
     */
    public function testSetByArrayReadAccess()
    {
        $model = (new Calendar())->setByArray(['read_access' => '1,2']);

        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests that a truthy read_access_all wins over read_access.
     */
    public function testSetByArrayReadAccessAllOverridesReadAccess()
    {
        $model = (new Calendar())->setByArray(['read_access' => '1,2', 'read_access_all' => 1]);

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that a falsy read_access_all does not touch the read access.
     */
    public function testSetByArrayFalsyReadAccessAllKeepsReadAccess()
    {
        $model = (new Calendar())->setByArray(['read_access' => '1,2', 'read_access_all' => 0]);

        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests that setByArray(getArray()) round-trips the serializable state.
     */
    public function testGetArraySetByArrayRoundTrip()
    {
        $model = (new Calendar())
            ->setId(1)
            ->setUid('39efcc37-43dd-43b7-923a-06c4a0d98fc1')
            ->setTitle('Training 1')
            ->setStart('2021-05-09 08:10:38')
            ->setEnd('2021-05-09 08:10:38')
            ->setPeriodDay(0)
            ->setReadAccess('all');

        $restored = (new Calendar())->setByArray($model->getArray());

        self::assertSame($model->getArray(), $restored->getArray());
    }
}
