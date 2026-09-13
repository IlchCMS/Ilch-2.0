<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Guestbook\Models;

use PHPUnit\Framework\TestCase;

class EntryModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Entry();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Entry();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setEmail() sets and returns the email.
     */
    public function testSetEmail()
    {
        $model = new Entry();
        $model->setEmail('alice@example.com');

        self::assertSame('alice@example.com', $model->getEmail());
    }

    /**
     * Tests that setEmail() casts to string.
     */
    public function testSetEmailCastsToString()
    {
        $model = new Entry();
        $model->setEmail(123);

        self::assertSame('123', $model->getEmail());
        self::assertIsString($model->getEmail());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new Entry();
        $model->setText('Hello world!');

        self::assertSame('Hello world!', $model->getText());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new Entry();
        $model->setText(456);

        self::assertSame('456', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new Entry();
        $model->setName('Alice');

        self::assertSame('Alice', $model->getName());
    }

    /**
     * Tests that setName() casts to string.
     */
    public function testSetNameCastsToString()
    {
        $model = new Entry();
        $model->setName(789);

        self::assertSame('789', $model->getName());
        self::assertIsString($model->getName());
    }

    /**
     * Tests that setHomepage() sets and returns the homepage.
     */
    public function testSetHomepage()
    {
        $model = new Entry();
        $model->setHomepage('https://alice.example.com');

        self::assertSame('https://alice.example.com', $model->getHomepage());
    }

    /**
     * Tests that setHomepage() casts to string.
     */
    public function testSetHomepageCastsToString()
    {
        $model = new Entry();
        $model->setHomepage(42);

        self::assertSame('42', $model->getHomepage());
        self::assertIsString($model->getHomepage());
    }

    /**
     * Tests that setDatetime() sets and returns the datetime.
     */
    public function testSetDatetime()
    {
        $model = new Entry();
        $model->setDatetime('2024-01-15 10:30:00');

        self::assertSame('2024-01-15 10:30:00', $model->getDatetime());
    }

    /**
     * Tests that setDatetime() casts to string.
     */
    public function testSetDatetimeCastsToString()
    {
        $model = new Entry();
        $model->setDatetime(20240115);

        self::assertSame('20240115', $model->getDatetime());
        self::assertIsString($model->getDatetime());
    }

    /**
     * Tests that setFree() sets and returns the setFree flag.
     */
    public function testSetFree()
    {
        $model = new Entry();
        $model->setFree(true);

        self::assertTrue($model->getFree());
    }

    /**
     * Tests that setFree() accepts 0/1 integers (cast to bool).
     */
    public function testSetFreeCastsToBool()
    {
        $model = new Entry();
        $model->setFree(1);

        self::assertTrue($model->getFree());

        $model->setFree(0);
        self::assertFalse($model->getFree());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Entry();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setEmail('a@b.c'));
        self::assertSame($model, $model->setText('Text'));
        self::assertSame($model, $model->setName('Name'));
        self::assertSame($model, $model->setHomepage('https://x.com'));
        self::assertSame($model, $model->setDatetime('2024-01-01 00:00:00'));
        self::assertSame($model, $model->setFree(true));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Entry())
            ->setId(3)
            ->setEmail('carol@example.com')
            ->setText('Nice site!')
            ->setName('Carol')
            ->setHomepage('https://carol.example.com')
            ->setDatetime('2024-03-10 08:00:00')
            ->setFree(true);

        self::assertSame(3, $model->getId());
        self::assertSame('carol@example.com', $model->getEmail());
        self::assertSame('Nice site!', $model->getText());
        self::assertSame('Carol', $model->getName());
        self::assertSame('https://carol.example.com', $model->getHomepage());
        self::assertSame('2024-03-10 08:00:00', $model->getDatetime());
        self::assertTrue($model->getFree());
    }

    /**
     * Tests that default values are 0 for id, empty strings, false for setFree.
     */
    public function testDefaultValues()
    {
        $model = new Entry();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getEmail());
        self::assertSame('', $model->getText());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getHomepage());
        self::assertSame('', $model->getDatetime());
        self::assertFalse($model->getFree());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid for new entries).
     */
    public function testSetIdZero()
    {
        $model = new Entry();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Entry();
        $model->setId(1)->setEmail('old@x.com')->setText('Old')->setName('Old Name');

        $model->setId(2)->setEmail('new@x.com')->setText('New')->setName('New Name');

        self::assertSame(2, $model->getId());
        self::assertSame('new@x.com', $model->getEmail());
        self::assertSame('New', $model->getText());
        self::assertSame('New Name', $model->getName());
    }

    /**
     * Tests getArray() with id included.
     */
    public function testGetArrayWithId()
    {
        $model = new Entry();
        $model->setId(5)
            ->setEmail('a@b.c')
            ->setText('Text')
            ->setName('Alice')
            ->setHomepage('https://x.com')
            ->setDatetime('2024-01-01 00:00:00')
            ->setFree(true);

        $array = $model->getArray();

        self::assertSame([
            'id'       => 5,
            'email'    => 'a@b.c',
            'text'     => 'Text',
            'datetime' => '2024-01-01 00:00:00',
            'homepage' => 'https://x.com',
            'name'     => 'Alice',
            'setfree'  => true,
        ], $array);
    }

    /**
     * Tests getArray() without id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Entry();
        $model->setId(5)
            ->setEmail('a@b.c')
            ->setText('Text')
            ->setName('Alice')
            ->setHomepage('https://x.com')
            ->setDatetime('2024-01-01 00:00:00')
            ->setFree(false);

        $array = $model->getArray(false);

        self::assertSame([
            'email'    => 'a@b.c',
            'text'     => 'Text',
            'datetime' => '2024-01-01 00:00:00',
            'homepage' => 'https://x.com',
            'name'     => 'Alice',
            'setfree'  => false,
        ], $array);
    }

    /**
     * Tests setByArray() with all fields.
     */
    public function testSetByArray()
    {
        $model = new Entry();
        $model->setByArray([
            'id'       => 4,
            'email'    => 'dave@example.com',
            'text'     => 'Nice!',
            'name'     => 'Dave',
            'homepage' => 'https://dave.example.com',
            'datetime' => '2024-05-20 12:00:00',
            'setfree'  => 1,
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('dave@example.com', $model->getEmail());
        self::assertSame('Nice!', $model->getText());
        self::assertSame('Dave', $model->getName());
        self::assertSame('https://dave.example.com', $model->getHomepage());
        self::assertSame('2024-05-20 12:00:00', $model->getDatetime());
        self::assertTrue($model->getFree());
    }

    /**
     * Tests setByArray() with partial fields (only id and text).
     */
    public function testSetByArrayPartial()
    {
        $model = new Entry();
        $model->setByArray([
            'id'   => 1,
            'text' => 'Only text',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Only text', $model->getText());
        self::assertSame('', $model->getEmail());
        self::assertSame('', $model->getName());
    }

    /**
     * Tests setByArray() with setfree = 0.
     */
    public function testSetByArraySetFreeZero()
    {
        $model = new Entry();
        $model->setByArray(['setfree' => 0]);

        self::assertFalse($model->getFree());
    }

    /**
     * Tests setByArray() is chainable.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Entry();
        $result = $model->setByArray(['id' => 1, 'name' => 'Test']);

        self::assertSame($model, $result);
    }
}
