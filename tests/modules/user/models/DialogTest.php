<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Models;

use PHPUnit\Ilch\TestCase;

/**
 * Tests the dialog model class.
 *
 * @package ilch_phpunit
 */
class DialogTest extends TestCase
{
    /**
     * Tests if the default values are set correctly.
     */
    public function testDefaultValues()
    {
        $dialog = new Dialog();

        self::assertNull($dialog->getId(), 'The default id is not null.');
        self::assertNull($dialog->getCId(), 'The default c_id is not null.');
        self::assertNull($dialog->getCrId(), 'The default cr_id is not null.');
        self::assertNull($dialog->getText(), 'The default text is not null.');
        self::assertNull($dialog->getUserOne(), 'The default user_one is not null.');
        self::assertNull($dialog->getUserTwo(), 'The default user_two is not null.');
        self::assertNull($dialog->getTime(), 'The default time is not null.');
        self::assertFalse($dialog->getHidden(), 'The default hidden value is not false.');
        self::assertNull($dialog->getName(), 'The default name is not null.');
        self::assertFalse($dialog->getRead(), 'The default read value is not false.');
        self::assertNull($dialog->getAvatar(), 'The default avatar is not null.');
    }

    /**
     * Tests if the id can be set and returned again.
     */
    public function testSetGetId()
    {
        $dialog = new Dialog();
        $dialog->setId(5);

        self::assertEquals(5, $dialog->getId(), 'The id was not saved or returned correctly.');
    }

    /**
     * Tests if the c_id can be set and returned again.
     */
    public function testSetGetCId()
    {
        $dialog = new Dialog();
        $dialog->setCId(10);

        self::assertEquals(10, $dialog->getCId(), 'The c_id was not saved or returned correctly.');
    }

    /**
     * Tests if the cr_id can be set and returned again.
     */
    public function testSetGetCrId()
    {
        $dialog = new Dialog();
        $dialog->setCrId(20);

        self::assertEquals(20, $dialog->getCrId(), 'The cr_id was not saved or returned correctly.');
    }

    /**
     * Tests if the text can be set and returned again.
     */
    public function testSetText()
    {
        $dialog = new Dialog();
        $dialog->setText('Hello');

        self::assertEquals('Hello', $dialog->getText(), 'The text was not saved or returned correctly.');
    }

    /**
     * Tests if the user_one can be set and returned again.
     */
    public function testSetGetUserOne()
    {
        $dialog = new Dialog();
        $dialog->setUserOne(1);

        self::assertEquals(1, $dialog->getUserOne(), 'The user_one was not saved or returned correctly.');
    }

    /**
     * Tests if the user_two can be set and returned again.
     */
    public function testSetGetUserTwo()
    {
        $dialog = new Dialog();
        $dialog->setUserTwo(2);

        self::assertEquals(2, $dialog->getUserTwo(), 'The user_two was not saved or returned correctly.');
    }

    /**
     * Tests if the time can be set and returned again.
     */
    public function testSetGetTime()
    {
        $dialog = new Dialog();
        $dialog->setTime('2014-01-01 12:12:12');

        self::assertEquals('2014-01-01 12:12:12', $dialog->getTime(), 'The time was not saved or returned correctly.');
    }

    /**
     * Tests if the hidden flag can be set and returned again.
     */
    public function testSetGetHidden()
    {
        $dialog = new Dialog();
        $dialog->setHidden(true);

        self::assertTrue($dialog->getHidden(), 'The hidden flag was not saved or returned correctly.');
    }

    /**
     * Tests if the name can be set and returned again.
     */
    public function testSetName()
    {
        $dialog = new Dialog();
        $dialog->setName('Testuser');

        self::assertEquals('Testuser', $dialog->getName(), 'The name was not saved or returned correctly.');
    }

    /**
     * Tests if the read status can be set and returned again.
     */
    public function testSetGetRead()
    {
        $dialog = new Dialog();
        $dialog->setRead(true);

        self::assertTrue($dialog->getRead(), 'The read status was not saved or returned correctly.');
    }

    /**
     * Tests if the avatar can be set and returned again.
     */
    public function testSetGetAvatar()
    {
        $dialog = new Dialog();
        $dialog->setAvatar('avatar.png');

        self::assertEquals('avatar.png', $dialog->getAvatar(), 'The avatar was not saved or returned correctly.');
    }

    /**
     * Tests if null values can be set and returned again.
     */
    public function testSetNullValues()
    {
        $dialog = new Dialog();
        $dialog->setId(1)->setCId(2)->setCrId(3)->setText('Hello')->setTime('2014-01-01 12:12:12');

        $dialog->setId(null)->setCId(null)->setCrId(null)->setText(null)->setTime(null);

        self::assertNull($dialog->getId(), 'The id was not reset to null.');
        self::assertNull($dialog->getCId(), 'The c_id was not reset to null.');
        self::assertNull($dialog->getCrId(), 'The cr_id was not reset to null.');
        self::assertNull($dialog->getText(), 'The text was not reset to null.');
        self::assertNull($dialog->getTime(), 'The time was not reset to null.');
    }

    /**
     * Tests if the setters are chainable and return the dialog.
     */
    public function testSettersReturnSelf()
    {
        $dialog = new Dialog();

        self::assertSame($dialog, $dialog->setId(1), 'setId() does not return the dialog.');
        self::assertSame($dialog, $dialog->setCId(1), 'setCId() does not return the dialog.');
        self::assertSame($dialog, $dialog->setCrId(1), 'setCrId() does not return the dialog.');
        self::assertSame($dialog, $dialog->setTime('2014-01-01 12:12:12'), 'setTime() does not return the dialog.');
        self::assertSame($dialog, $dialog->setHidden(true), 'setHidden() does not return the dialog.');
        self::assertSame($dialog, $dialog->setUserOne(1), 'setUserOne() does not return the dialog.');
        self::assertSame($dialog, $dialog->setUserTwo(2), 'setUserTwo() does not return the dialog.');
        self::assertSame($dialog, $dialog->setText('Hello'), 'setText() does not return the dialog.');
        self::assertSame($dialog, $dialog->setName('Testuser'), 'setName() does not return the dialog.');
        self::assertSame($dialog, $dialog->setRead(true), 'setRead() does not return the dialog.');
        self::assertSame($dialog, $dialog->setAvatar('avatar.png'), 'setAvatar() does not return the dialog.');
    }

    /**
     * Tests if chaining setters builds a complete dialog.
     */
    public function testChainedSetters()
    {
        $dialog = (new Dialog())
            ->setId(5)
            ->setCId(10)
            ->setCrId(20)
            ->setUserOne(1)
            ->setUserTwo(2)
            ->setText('Hello')
            ->setTime('2014-01-01 12:12:12')
            ->setHidden(false)
            ->setName('Testuser')
            ->setRead(true)
            ->setAvatar('avatar.png');

        self::assertEquals(5, $dialog->getId(), 'The id was not saved correctly.');
        self::assertEquals(10, $dialog->getCId(), 'The c_id was not saved correctly.');
        self::assertEquals(20, $dialog->getCrId(), 'The cr_id was not saved correctly.');
        self::assertEquals(1, $dialog->getUserOne(), 'The user_one was not saved correctly.');
        self::assertEquals(2, $dialog->getUserTwo(), 'The user_two was not saved correctly.');
        self::assertEquals('Hello', $dialog->getText(), 'The text was not saved correctly.');
        self::assertEquals('2014-01-01 12:12:12', $dialog->getTime(), 'The time was not saved correctly.');
        self::assertFalse($dialog->getHidden(), 'The hidden flag was not saved correctly.');
        self::assertEquals('Testuser', $dialog->getName(), 'The name was not saved correctly.');
        self::assertTrue($dialog->getRead(), 'The read status was not saved correctly.');
        self::assertEquals('avatar.png', $dialog->getAvatar(), 'The avatar was not saved correctly.');
    }

    /**
     * Tests if a previously set value can be overwritten.
     */
    public function testOverwriteValues()
    {
        $dialog = new Dialog();
        $dialog->setId(1)->setText('Old')->setHidden(true)->setRead(false);

        $dialog->setId(2)->setText('New')->setHidden(false)->setRead(true);

        self::assertEquals(2, $dialog->getId(), 'The id was not overwritten correctly.');
        self::assertEquals('New', $dialog->getText(), 'The text was not overwritten correctly.');
        self::assertFalse($dialog->getHidden(), 'The hidden flag was not overwritten correctly.');
        self::assertTrue($dialog->getRead(), 'The read status was not overwritten correctly.');
    }
}
