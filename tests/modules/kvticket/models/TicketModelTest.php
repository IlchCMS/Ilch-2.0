<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Kvticket\Models;

use PHPUnit\Framework\TestCase;
use Modules\Kvticket\Models\Ticket as TicketModel;

class TicketModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new TicketModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new TicketModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new TicketModel();
        $model->setTitle('Login issue');

        self::assertSame('Login issue', $model->getTitle());
    }

    /**
     * Tests that setTitle() casts to string.
     */
    public function testSetTitleCastsToString()
    {
        $model = new TicketModel();
        $model->setTitle(123);

        self::assertSame('123', $model->getTitle());
        self::assertIsString($model->getTitle());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new TicketModel();
        $model->setText('Cannot login to the site');

        self::assertSame('Cannot login to the site', $model->getText());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new TicketModel();
        $model->setText(456);

        self::assertSame('456', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setStatus() sets and returns the status.
     */
    public function testSetStatus()
    {
        $model = new TicketModel();
        $model->setStatus(1);

        self::assertSame(1, $model->getStatus());
    }

    /**
     * Tests that setStatus() casts to int.
     */
    public function testSetStatusCastsToInt()
    {
        $model = new TicketModel();
        $model->setStatus('2');

        self::assertSame(2, $model->getStatus());
        self::assertIsInt($model->getStatus());
    }

    /**
     * Tests that setEditor() sets and returns the editor.
     */
    public function testSetEditor()
    {
        $model = new TicketModel();
        $model->setEditor(7);

        self::assertSame(7, $model->getEditor());
    }

    /**
     * Tests that setEditor() casts to int.
     */
    public function testSetEditorCastsToInt()
    {
        $model = new TicketModel();
        $model->setEditor('10');

        self::assertSame(10, $model->getEditor());
        self::assertIsInt($model->getEditor());
    }

    /**
     * Tests that setCreator() sets and returns the creator.
     */
    public function testSetCreator()
    {
        $model = new TicketModel();
        $model->setCreator(3);

        self::assertSame(3, $model->getCreator());
    }

    /**
     * Tests that setCreator() casts to int.
     */
    public function testSetCreatorCastsToInt()
    {
        $model = new TicketModel();
        $model->setCreator('9');

        self::assertSame(9, $model->getCreator());
        self::assertIsInt($model->getCreator());
    }

    /**
     * Tests that setCat() sets and returns the category.
     */
    public function testSetCat()
    {
        $model = new TicketModel();
        $model->setCat(1);

        self::assertSame(1, $model->getCat());
    }

    /**
     * Tests that setCat() casts to int.
     */
    public function testSetCatCastsToInt()
    {
        $model = new TicketModel();
        $model->setCat('2');

        self::assertSame(2, $model->getCat());
        self::assertIsInt($model->getCat());
    }

    /**
     * Tests that setCreatedAt() sets and returns the created_at.
     */
    public function testSetCreatedAt()
    {
        $model = new TicketModel();
        $model->setCreatedAt('2024-01-01 10:00:00');

        self::assertSame('2024-01-01 10:00:00', $model->getCreatedAt());
    }

    /**
     * Tests that setCreatedAt() casts to string.
     */
    public function testSetCreatedAtCastsToString()
    {
        $model = new TicketModel();
        $model->setCreatedAt(1704067200);

        self::assertSame('1704067200', $model->getCreatedAt());
        self::assertIsString($model->getCreatedAt());
    }

    /**
     * Tests that setUpdatedAt() sets and returns the updated_at.
     */
    public function testSetUpdatedAt()
    {
        $model = new TicketModel();
        $model->setUpdatedAt('2024-01-02 14:30:00');

        self::assertSame('2024-01-02 14:30:00', $model->getUpdatedAt());
    }

    /**
     * Tests that setUpdatedAt() casts to string.
     */
    public function testSetUpdatedAtCastsToString()
    {
        $model = new TicketModel();
        $model->setUpdatedAt(1704183000);

        self::assertSame('1704183000', $model->getUpdatedAt());
        self::assertIsString($model->getUpdatedAt());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new TicketModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setText('Body'));
        self::assertSame($model, $model->setStatus(0));
        self::assertSame($model, $model->setEditor(1));
        self::assertSame($model, $model->setCreator(2));
        self::assertSame($model, $model->setCat(1));
        self::assertSame($model, $model->setCreatedAt('2024-01-01 00:00:00'));
        self::assertSame($model, $model->setUpdatedAt('2024-01-01 00:00:00'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new TicketModel())
            ->setId(3)
            ->setTitle('Dark Mode')
            ->setText('Please add dark mode')
            ->setStatus(1)
            ->setEditor(2)
            ->setCreator(5)
            ->setCat(2)
            ->setCreatedAt('2024-01-03 08:00:00')
            ->setUpdatedAt('2024-01-03 08:00:00');

        self::assertSame(3, $model->getId());
        self::assertSame('Dark Mode', $model->getTitle());
        self::assertSame('Please add dark mode', $model->getText());
        self::assertSame(1, $model->getStatus());
        self::assertSame(2, $model->getEditor());
        self::assertSame(5, $model->getCreator());
        self::assertSame(2, $model->getCat());
        self::assertSame('2024-01-03 08:00:00', $model->getCreatedAt());
        self::assertSame('2024-01-03 08:00:00', $model->getUpdatedAt());
    }

    /**
     * Tests that default values are null/0/'' for the typed defaults.
     */
    public function testDefaultValues()
    {
        $model = new TicketModel();

        self::assertNull($model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getStatus());
        self::assertNull($model->getEditor());
        self::assertNull($model->getCreator());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new TicketModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setStatus(0) stores zero (falsy but valid).
     */
    public function testSetStatusZero()
    {
        $model = new TicketModel();
        $model->setStatus(0);

        self::assertSame(0, $model->getStatus());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new TicketModel();
        $model->setId(1)
            ->setTitle('Old Title')
            ->setText('Old text')
            ->setStatus(0)
            ->setEditor(1)
            ->setCreator(1)
            ->setCat(1)
            ->setCreatedAt('2024-01-01 00:00:00')
            ->setUpdatedAt('2024-01-01 00:00:00');

        $model->setId(2)
            ->setTitle('New Title')
            ->setText('New text')
            ->setStatus(1)
            ->setEditor(5)
            ->setCreator(7)
            ->setCat(2)
            ->setCreatedAt('2024-02-01 00:00:00')
            ->setUpdatedAt('2024-02-01 00:00:00');

        self::assertSame(2, $model->getId());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('New text', $model->getText());
        self::assertSame(1, $model->getStatus());
        self::assertSame(5, $model->getEditor());
        self::assertSame(7, $model->getCreator());
        self::assertSame(2, $model->getCat());
        self::assertSame('2024-02-01 00:00:00', $model->getCreatedAt());
        self::assertSame('2024-02-01 00:00:00', $model->getUpdatedAt());
    }
}
