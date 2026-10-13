<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\War\Models;

use PHPUnit\Framework\TestCase;
use Modules\War\Models\Enemy as EnemyModel;

class EnemyModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new EnemyModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts numeric strings to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new EnemyModel();
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

        (new EnemyModel())->setId('abc');
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new EnemyModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setEnemyName() sets and returns the name.
     */
    public function testSetEnemyName()
    {
        $model = new EnemyModel();
        $model->setEnemyName('Dead Mice');

        self::assertSame('Dead Mice', $model->getEnemyName());
    }

    /**
     * Tests that setEnemyName() casts to string.
     */
    public function testSetEnemyNameCastsToString()
    {
        $model = new EnemyModel();
        $model->setEnemyName(123);

        self::assertSame('123', $model->getEnemyName());
        self::assertIsString($model->getEnemyName());
    }

    /**
     * Tests that setEnemyTag() sets and returns the tag.
     */
    public function testSetEnemyTag()
    {
        $model = new EnemyModel();
        $model->setEnemyTag('DMS');

        self::assertSame('DMS', $model->getEnemyTag());
    }

    /**
     * Tests that setEnemyHomepage() sets and returns the homepage.
     */
    public function testSetEnemyHomepage()
    {
        $model = new EnemyModel();
        $model->setEnemyHomepage('https://deadmice.example');

        self::assertSame('https://deadmice.example', $model->getEnemyHomepage());
    }

    /**
     * Tests that setEnemyImage() sets and returns the image.
     */
    public function testSetEnemyImage()
    {
        $model = new EnemyModel();
        $model->setEnemyImage('deadmice.png');

        self::assertSame('deadmice.png', $model->getEnemyImage());
    }

    /**
     * Tests that setEnemyContactName() sets and returns the contact name.
     */
    public function testSetEnemyContactName()
    {
        $model = new EnemyModel();
        $model->setEnemyContactName('John Doe');

        self::assertSame('John Doe', $model->getEnemyContactName());
    }

    /**
     * Tests that setEnemyContactEmail() sets and returns the contact email.
     */
    public function testSetEnemyContactEmail()
    {
        $model = new EnemyModel();
        $model->setEnemyContactEmail('contact@deadmice.example');

        self::assertSame('contact@deadmice.example', $model->getEnemyContactEmail());
    }

    /**
     * Tests that setEnemyContactEmail() casts to string.
     */
    public function testSetEnemyContactEmailCastsToString()
    {
        $model = new EnemyModel();
        $model->setEnemyContactEmail(456);

        self::assertSame('456', $model->getEnemyContactEmail());
        self::assertIsString($model->getEnemyContactEmail());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new EnemyModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setEnemyName('Test'));
        self::assertSame($model, $model->setEnemyTag('TST'));
        self::assertSame($model, $model->setEnemyHomepage('https://test.example'));
        self::assertSame($model, $model->setEnemyImage('test.png'));
        self::assertSame($model, $model->setEnemyContactName('Contact Person'));
        self::assertSame($model, $model->setEnemyContactEmail('contact@example.com'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new EnemyModel())
            ->setId(3)
            ->setEnemyName('Dead Mice')
            ->setEnemyTag('DMS')
            ->setEnemyHomepage('https://deadmice.example')
            ->setEnemyImage('deadmice.png')
            ->setEnemyContactName('John Doe')
            ->setEnemyContactEmail('contact@deadmice.example');

        self::assertSame(3, $model->getId());
        self::assertSame('Dead Mice', $model->getEnemyName());
        self::assertSame('DMS', $model->getEnemyTag());
        self::assertSame('https://deadmice.example', $model->getEnemyHomepage());
        self::assertSame('deadmice.png', $model->getEnemyImage());
        self::assertSame('John Doe', $model->getEnemyContactName());
        self::assertSame('contact@deadmice.example', $model->getEnemyContactEmail());
    }

    /**
     * Tests that default values are 0 for id and empty string for all text fields.
     */
    public function testDefaultValues()
    {
        $model = new EnemyModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getEnemyName());
        self::assertSame('', $model->getEnemyTag());
        self::assertSame('', $model->getEnemyHomepage());
        self::assertSame('', $model->getEnemyImage());
        self::assertSame('', $model->getEnemyContactName());
        self::assertSame('', $model->getEnemyContactEmail());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new EnemyModel();
        $model->setId(1)->setEnemyName('Old')->setEnemyContactEmail('old@example.com');

        $model->setId(2)->setEnemyName('New')->setEnemyContactEmail('new@example.com');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getEnemyName());
        self::assertSame('new@example.com', $model->getEnemyContactEmail());
    }

    /**
     * Tests setByArray() with all supported keys.
     */
    public function testSetByArrayFull()
    {
        $model = new EnemyModel();
        $model->setByArray([
            'id' => 1,
            'name' => 'Dead Mice',
            'tag' => 'DMS',
            'image' => 'deadmice.png',
            'homepage' => 'https://deadmice.example',
            'contact_name' => 'John Doe',
            'contact_email' => 'contact@deadmice.example',
        ]);

        self::assertSame(1, $model->getId());
        self::assertSame('Dead Mice', $model->getEnemyName());
        self::assertSame('DMS', $model->getEnemyTag());
        self::assertSame('deadmice.png', $model->getEnemyImage());
        self::assertSame('https://deadmice.example', $model->getEnemyHomepage());
        self::assertSame('John Doe', $model->getEnemyContactName());
        self::assertSame('contact@deadmice.example', $model->getEnemyContactEmail());
    }

    /**
     * Tests setByArray() with a partial array, missing keys keep defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = (new EnemyModel())->setByArray(['name' => 'New Enemies']);

        self::assertSame(0, $model->getId());
        self::assertSame('New Enemies', $model->getEnemyName());
        self::assertSame('', $model->getEnemyTag());
        self::assertSame('', $model->getEnemyContactEmail());
    }

    /**
     * Tests that setByArray() ignores unknown keys.
     */
    public function testSetByArrayIgnoresUnknownKeys()
    {
        $model = (new EnemyModel())->setByArray(['id' => 9, 'foo' => 'bar']);

        self::assertSame(9, $model->getId());
        self::assertSame('', $model->getEnemyName());
    }

    /**
     * Tests that getArray() returns id and all persisted fields.
     */
    public function testGetArrayWithId()
    {
        $model = new EnemyModel();
        $model->setId(3)
            ->setEnemyName('Dead Mice')
            ->setEnemyTag('DMS')
            ->setEnemyImage('deadmice.png')
            ->setEnemyHomepage('https://deadmice.example')
            ->setEnemyContactName('John Doe')
            ->setEnemyContactEmail('contact@deadmice.example');

        self::assertSame(
            [
                'id' => 3,
                'name' => 'Dead Mice',
                'tag' => 'DMS',
                'image' => 'deadmice.png',
                'homepage' => 'https://deadmice.example',
                'contact_name' => 'John Doe',
                'contact_email' => 'contact@deadmice.example',
            ],
            $model->getArray()
        );
    }

    /**
     * Tests that getArray(false) omits the id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new EnemyModel();
        $model->setEnemyName('Dead Mice')
            ->setEnemyTag('DMS')
            ->setEnemyImage('deadmice.png')
            ->setEnemyHomepage('https://deadmice.example')
            ->setEnemyContactName('John Doe')
            ->setEnemyContactEmail('contact@deadmice.example');

        self::assertSame(
            [
                'name' => 'Dead Mice',
                'tag' => 'DMS',
                'image' => 'deadmice.png',
                'homepage' => 'https://deadmice.example',
                'contact_name' => 'John Doe',
                'contact_email' => 'contact@deadmice.example',
            ],
            $model->getArray(false)
        );
    }

    /**
     * Tests that setByArray() and getArray() are inverse operations.
     */
    public function testSetByArrayGetArrayRoundTrip()
    {
        $source = [
            'id' => 1,
            'name' => 'Dead Mice',
            'tag' => 'DMS',
            'image' => 'deadmice.png',
            'homepage' => 'https://deadmice.example',
            'contact_name' => 'John Doe',
            'contact_email' => 'contact@deadmice.example',
        ];

        $model = (new EnemyModel())->setByArray($source);

        self::assertSame($source, $model->getArray());
    }
}
