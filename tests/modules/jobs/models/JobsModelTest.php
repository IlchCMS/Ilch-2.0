<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Jobs\Models;

use PHPUnit\Framework\TestCase;
use Modules\Jobs\Models\Jobs as JobsModel;

class JobsModelTest extends TestCase
{
    /**
     * Tests default values of a new Jobs model.
     */
    public function testDefaultValues()
    {
        $model = new JobsModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getText());
        self::assertSame('', $model->getEmail());
        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new JobsModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId(0) stores zero.
     */
    public function testSetIdZero()
    {
        $model = new JobsModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setTitle() sets and returns the title.
     */
    public function testSetTitle()
    {
        $model = new JobsModel();
        $model->setTitle('Web Developer');

        self::assertSame('Web Developer', $model->getTitle());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new JobsModel();
        $model->setText('Looking for an experienced developer.');

        self::assertSame('Looking for an experienced developer.', $model->getText());
    }

    /**
     * Tests that setEmail() sets and returns the email.
     */
    public function testSetEmail()
    {
        $model = new JobsModel();
        $model->setEmail('hr@example.com');

        self::assertSame('hr@example.com', $model->getEmail());
    }

    /**
     * Tests that setShow() sets and returns the show flag.
     */
    public function testSetShowTrue()
    {
        $model = new JobsModel();
        $model->setShow(true);

        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setShow(false) stores false.
     */
    public function testSetShowFalse()
    {
        $model = new JobsModel();
        $model->setShow(false);

        self::assertFalse($model->getShow());
    }

    /**
     * Tests that all setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new JobsModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setText('Some text'));
        self::assertSame($model, $model->setEmail('test@example.com'));
        self::assertSame($model, $model->setShow(true));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new JobsModel())
            ->setId(3)
            ->setTitle('UX Designer')
            ->setText('Creative soul for designing interfaces.')
            ->setEmail('design@example.com')
            ->setShow(false);

        self::assertSame(3, $model->getId());
        self::assertSame('UX Designer', $model->getTitle());
        self::assertSame('Creative soul for designing interfaces.', $model->getText());
        self::assertSame('design@example.com', $model->getEmail());
        self::assertFalse($model->getShow());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new JobsModel();
        $model->setId(1)
            ->setTitle('Old Title')
            ->setText('Old text')
            ->setEmail('old@example.com')
            ->setShow(true);

        $model->setId(2)
            ->setTitle('New Title')
            ->setText('New text')
            ->setEmail('new@example.com')
            ->setShow(false);

        self::assertSame(2, $model->getId());
        self::assertSame('New Title', $model->getTitle());
        self::assertSame('New text', $model->getText());
        self::assertSame('new@example.com', $model->getEmail());
        self::assertFalse($model->getShow());
    }

    /**
     * Tests that setByArray() populates all fields from an associative array.
     */
    public function testSetByArray()
    {
        $model = new JobsModel();
        $model->setByArray([
            'id'    => 4,
            'title' => 'DevOps Engineer',
            'text'  => 'Cloud infrastructure role.',
            'email' => 'devops@example.com',
            'show'  => true,
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame('DevOps Engineer', $model->getTitle());
        self::assertSame('Cloud infrastructure role.', $model->getText());
        self::assertSame('devops@example.com', $model->getEmail());
        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setByArray() handles show as integer (from DB).
     */
    public function testSetByArrayShowAsInt()
    {
        $model = new JobsModel();
        $model->setByArray([
            'id'    => 1,
            'title' => 'Web Developer',
            'text'  => 'Some description.',
            'email' => 'hr@example.com',
            'show'  => 1,
        ]);

        self::assertTrue($model->getShow());
    }

    /**
     * Tests that setByArray() handles show as zero integer (from DB).
     */
    public function testSetByArrayShowAsZero()
    {
        $model = new JobsModel();
        $model->setByArray([
            'id'    => 3,
            'title' => 'UX Designer',
            'text'  => 'Design role.',
            'email' => 'design@example.com',
            'show'  => 0,
        ]);

        self::assertFalse($model->getShow());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new JobsModel();

        self::assertSame($model, $model->setByArray(['id' => 1]));
    }

    /**
     * Tests that setByArray() only sets keys that are present.
     */
    public function testSetByArrayPartial()
    {
        $model = new JobsModel();
        $model->setByArray([
            'title' => 'Partial',
        ]);

        self::assertSame(0, $model->getId());
        self::assertSame('Partial', $model->getTitle());
        self::assertSame('', $model->getText());
        self::assertSame('', $model->getEmail());
        self::assertTrue($model->getShow());
    }

    /**
     * Tests that getArray() includes id by default.
     */
    public function testGetArrayWithId()
    {
        $model = (new JobsModel())
            ->setId(1)
            ->setTitle('Web Developer')
            ->setText('Looking for an experienced developer.')
            ->setEmail('hr@example.com')
            ->setShow(true);

        $array = $model->getArray();

        self::assertIsArray($array);
        self::assertArrayHasKey('id', $array);
        self::assertEquals(1, $array['id']);
        self::assertEquals('Web Developer', $array['title']);
        self::assertEquals('Looking for an experienced developer.', $array['text']);
        self::assertEquals('hr@example.com', $array['email']);
        self::assertTrue($array['show']);
        self::assertCount(5, $array);
    }

    /**
     * Tests that getArray(false) excludes the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = (new JobsModel())
            ->setId(1)
            ->setTitle('Web Developer')
            ->setText('Looking for an experienced developer.')
            ->setEmail('hr@example.com')
            ->setShow(false);

        $array = $model->getArray(false);

        self::assertIsArray($array);
        self::assertArrayNotHasKey('id', $array);
        self::assertCount(4, $array);
        self::assertEquals('Web Developer', $array['title']);
        self::assertEquals('Looking for an experienced developer.', $array['text']);
        self::assertEquals('hr@example.com', $array['email']);
        self::assertFalse($array['show']);
    }
}
