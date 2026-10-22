<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Training\Models;

use PHPUnit\Framework\TestCase;
use Modules\Training\Models\Entrants as EntrantsModel;

class EntrantsModelTest extends TestCase
{
    /**
     * Tests that the default values are null for trainId and userId and an empty string for note.
     */
    public function testDefaultValues()
    {
        $model = new EntrantsModel();

        self::assertNull($model->getTrainId());
        self::assertNull($model->getUserId());
        self::assertSame('', $model->getNote());
    }

    /**
     * Tests that setTrainId() sets and returns the trainId.
     */
    public function testSetTrainId()
    {
        $model = new EntrantsModel();
        $model->setTrainId(7);

        self::assertSame(7, $model->getTrainId());
    }

    /**
     * Tests that setTrainId() casts a numeric string to int.
     */
    public function testSetTrainIdCastsToInt()
    {
        $model = new EntrantsModel();
        $model->setTrainId('42');

        self::assertSame(42, $model->getTrainId());
        self::assertIsInt($model->getTrainId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new EntrantsModel();
        $model->setUserId(9);

        self::assertSame(9, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts a numeric string to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new EntrantsModel();
        $model->setUserId('11');

        self::assertSame(11, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setNote() sets and returns the note.
     */
    public function testSetNote()
    {
        $model = new EntrantsModel();
        $model->setNote('Present');

        self::assertSame('Present', $model->getNote());
    }

    /**
     * Tests that setNote() casts to string.
     */
    public function testSetNoteCastsToString()
    {
        $model = new EntrantsModel();
        $model->setNote(123);

        self::assertSame('123', $model->getNote());
        self::assertIsString($model->getNote());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new EntrantsModel();

        self::assertSame($model, $model->setTrainId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setNote('Note'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new EntrantsModel())
            ->setTrainId(3)
            ->setUserId(4)
            ->setNote('Late');

        self::assertSame(3, $model->getTrainId());
        self::assertSame(4, $model->getUserId());
        self::assertSame('Late', $model->getNote());
    }

    /**
     * Tests that setByArray() sets all known fields from a database row.
     */
    public function testSetByArray()
    {
        $model = new EntrantsModel();
        $model->setByArray(['train_id' => 5, 'user_id' => 6, 'note' => 'Note']);

        self::assertSame(5, $model->getTrainId());
        self::assertSame(6, $model->getUserId());
        self::assertSame('Note', $model->getNote());
    }

    /**
     * Tests that setByArray() only overrides fields that are present in the array.
     */
    public function testSetByArrayPartial()
    {
        $model = new EntrantsModel();
        $model->setByArray(['train_id' => 5]);

        self::assertSame(5, $model->getTrainId());
        self::assertNull($model->getUserId());
        self::assertSame('', $model->getNote());
    }

    /**
     * Tests that setByArray() returns $this for chaining.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new EntrantsModel();

        self::assertSame($model, $model->setByArray(['train_id' => 1]));
    }

    /**
     * Tests that getArray() returns all fields of the model.
     */
    public function testGetArray()
    {
        $model = new EntrantsModel();
        $model->setTrainId(5);
        $model->setUserId(6);
        $model->setNote('Note');

        self::assertSame(['train_id' => 5, 'user_id' => 6, 'note' => 'Note'], $model->getArray());
    }
}
