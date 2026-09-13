<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\History\Models;

use PHPUnit\Framework\TestCase;
use Modules\History\Models\History as HistoryModel;

class HistoryModelTest extends TestCase
{
    /**
     * Tests default values of a new History model.
     */
    public function testDefaultValues()
    {
        $model = new HistoryModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getDate());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getType());
        self::assertSame('#75ce66', $model->getColor());
        self::assertSame('', $model->getText());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new HistoryModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new HistoryModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setDate() sets and returns the date.
     */
    public function testSetDate()
    {
        $model = new HistoryModel();
        $model->setDate('2023-06-15');

        self::assertSame('2023-06-15', $model->getDate());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new HistoryModel();
        $model->setTitle('Founded');

        self::assertSame('Founded', $model->getTitle());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new HistoryModel();
        $model->setType('fas fa-globe');

        self::assertSame('fas fa-globe', $model->getType());
    }

    /**
     * Tests that setColor() sets and returns the color.
     */
    public function testSetColor()
    {
        $model = new HistoryModel();
        $model->setColor('#ff0000');

        self::assertSame('#ff0000', $model->getColor());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new HistoryModel();
        $model->setText('A long history text here.');

        self::assertSame('A long history text here.', $model->getText());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new HistoryModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setDate('2020-01-01'));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setType('fas fa-globe'));
        self::assertSame($model, $model->setColor('#75ce66'));
        self::assertSame($model, $model->setText('Some text'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new HistoryModel())
            ->setId(3)
            ->setDate('2022-03-10')
            ->setTitle('New Building')
            ->setType('fas fa-camera')
            ->setColor('#5bc0de')
            ->setText('Moved to new premises.');

        self::assertSame(3, $model->getId());
        self::assertSame('2022-03-10', $model->getDate());
        self::assertSame('New Building', $model->getTitle());
        self::assertSame('fas fa-camera', $model->getType());
        self::assertSame('#5bc0de', $model->getColor());
        self::assertSame('Moved to new premises.', $model->getText());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new HistoryModel();
        $model->setId(1)
            ->setDate('2020-01-01')
            ->setTitle('Old')
            ->setType('fas fa-globe')
            ->setColor('#75ce66')
            ->setText('Old text');

        $model->setId(2)
            ->setDate('2021-06-20')
            ->setTitle('New')
            ->setType('fas fa-video')
            ->setColor('#f0ad4e')
            ->setText('New text');

        self::assertSame(2, $model->getId());
        self::assertSame('2021-06-20', $model->getDate());
        self::assertSame('New', $model->getTitle());
        self::assertSame('fas fa-video', $model->getType());
        self::assertSame('#f0ad4e', $model->getColor());
        self::assertSame('New text', $model->getText());
    }

    /**
     * Tests that setByArray() populates all fields from an associative array.
     */
    public function testSetByArray()
    {
        $model = new HistoryModel();
        $model->setByArray([
            'id'    => 4,
            'date'  => '2023-05-20',
            'title' => 'Anniversary',
            'type'  => 'fas fa-globe',
            'color' => '#d9534f',
            'text'  => 'Ten years of history.',
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('2023-05-20', $model->getDate());
        self::assertSame('Anniversary', $model->getTitle());
        self::assertSame('fas fa-globe', $model->getType());
        self::assertSame('#d9534f', $model->getColor());
        self::assertSame('Ten years of history.', $model->getText());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new HistoryModel();

        self::assertSame($model, $model->setByArray(['id' => 1]));
    }

    /**
     * Tests that setByArray() only sets keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new HistoryModel();
        $model->setByArray([
            'title' => 'Partial',
            'date'  => '2020-01-01',
        ]);

        self::assertSame(0, $model->getId());
        self::assertSame('2020-01-01', $model->getDate());
        self::assertSame('Partial', $model->getTitle());
        self::assertSame('', $model->getType());
        self::assertSame('#75ce66', $model->getColor());
        self::assertSame('', $model->getText());
    }

    /**
     * Tests that getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = (new HistoryModel())
            ->setId(1)
            ->setDate('2020-01-15')
            ->setTitle('Founded')
            ->setType('fas fa-globe')
            ->setColor('#75ce66')
            ->setText('The club was founded.');

        $array = $model->getArray();

        self::assertIsArray($array);
        self::assertArrayHasKey('id', $array);
        self::assertEquals(1, $array['id']);
        self::assertEquals('2020-01-15', $array['date']);
        self::assertEquals('Founded', $array['title']);
        self::assertEquals('fas fa-globe', $array['type']);
        self::assertEquals('#75ce66', $array['color']);
        self::assertEquals('The club was founded.', $array['text']);
        self::assertCount(6, $array);
    }

    /**
     * Tests that getArray(false) excludes the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new HistoryModel())
            ->setId(1)
            ->setDate('2020-01-15')
            ->setTitle('Founded')
            ->setType('fas fa-globe')
            ->setColor('#75ce66')
            ->setText('The club was founded.');

        $array = $model->getArray(false);

        self::assertIsArray($array);
        self::assertArrayNotHasKey('id', $array);
        self::assertCount(5, $array);
        self::assertEquals('2020-01-15', $array['date']);
        self::assertEquals('Founded', $array['title']);
        self::assertEquals('fas fa-globe', $array['type']);
        self::assertEquals('#75ce66', $array['color']);
        self::assertEquals('The club was founded.', $array['text']);
    }
}
