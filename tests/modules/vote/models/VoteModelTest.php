<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Vote\Models;

use PHPUnit\Framework\TestCase;

/**
 * Tests for the Vote model of the Vote module
 *
 * @package ilch_phpunit
 */
class VoteModelTest extends TestCase
{
    /**
     * Test the default values of a new model
     *
     * @return void
     */
    public function testDefaultValues()
    {
        $model = new Vote();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getQuestion());
        self::assertSame('', $model->getKey());
        self::assertSame('', $model->getGroups());
        self::assertSame('2,3', $model->getReadAccess());
        self::assertFalse($model->getStatus());
        self::assertFalse($model->getMultipleReply());
    }

    /**
     * Test that setId() stores the given value
     *
     * @return void
     */
    public function testSetId()
    {
        $model = new Vote();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Test that setId() casts the given value to int
     *
     * @return void
     */
    public function testSetIdCastsToInt()
    {
        $model = new Vote();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Test that setId() accepts zero
     *
     * @return void
     */
    public function testSetIdZero()
    {
        $model = new Vote();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Test that setQuestion() stores the given value
     *
     * @return void
     */
    public function testSetQuestion()
    {
        $model = new Vote();
        $model->setQuestion('Frage 1');

        self::assertSame('Frage 1', $model->getQuestion());
    }

    /**
     * Test that setQuestion() casts the given value to string
     *
     * @return void
     */
    public function testSetQuestionCastsToString()
    {
        $model = new Vote();
        $model->setQuestion(123);

        self::assertSame('123', $model->getQuestion());
        self::assertIsString($model->getQuestion());
    }

    /**
     * Test that setKey() stores the given value
     *
     * @return void
     */
    public function testSetKey()
    {
        $model = new Vote();
        $model->setKey('frage1');

        self::assertSame('frage1', $model->getKey());
    }

    /**
     * Test that setGroups() stores the given value
     *
     * @return void
     */
    public function testSetGroups()
    {
        $model = new Vote();
        $model->setGroups('1,2,3');

        self::assertSame('1,2,3', $model->getGroups());
    }

    /**
     * Test that setReadAccess() stores the given value
     *
     * @return void
     */
    public function testSetReadAccess()
    {
        $model = new Vote();
        $model->setReadAccess('all');

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Test that setStatus() stores the given value as a boolean
     *
     * @return void
     */
    public function testSetStatus()
    {
        $model = new Vote();
        $model->setStatus(true);

        self::assertTrue($model->getStatus());
    }

    /**
     * Test that setStatus() casts the given value to bool
     *
     * @return void
     */
    public function testSetStatusCastsToBool()
    {
        $model = new Vote();
        $model->setStatus(1);

        self::assertTrue($model->getStatus());

        $model->setStatus(0);

        self::assertFalse($model->getStatus());
    }

    /**
     * Test that setMultipleReply() stores the given value as a boolean
     *
     * @return void
     */
    public function testSetMultipleReply()
    {
        $model = new Vote();
        $model->setMultipleReply(true);

        self::assertTrue($model->getMultipleReply());
    }

    /**
     * Test that setMultipleReply() casts the given value to bool
     *
     * @return void
     */
    public function testSetMultipleReplyCastsToBool()
    {
        $model = new Vote();
        $model->setMultipleReply('1');

        self::assertTrue($model->getMultipleReply());
    }

    /**
     * Test that all setters return the model itself for chaining
     *
     * @return void
     */
    public function testSettersReturnSelf()
    {
        $model = new Vote();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setQuestion('Frage'));
        self::assertSame($model, $model->setKey('key'));
        self::assertSame($model, $model->setGroups('1'));
        self::assertSame($model, $model->setReadAccess('1,2'));
        self::assertSame($model, $model->setStatus(true));
        self::assertSame($model, $model->setMultipleReply(true));
    }

    /**
     * Test that the setters can be chained
     *
     * @return void
     */
    public function testChainedSetters()
    {
        $model = (new Vote())
            ->setId(1)
            ->setQuestion('Frage 1')
            ->setKey('frage1')
            ->setGroups('2')
            ->setReadAccess('1,2,3')
            ->setStatus(true)
            ->setMultipleReply(false);

        self::assertSame(1, $model->getId());
        self::assertSame('Frage 1', $model->getQuestion());
        self::assertSame('frage1', $model->getKey());
        self::assertSame('2', $model->getGroups());
        self::assertSame('1,2,3', $model->getReadAccess());
        self::assertTrue($model->getStatus());
        self::assertFalse($model->getMultipleReply());
    }

    /**
     * Test that setByArray() sets all supported fields
     *
     * @return void
     */
    public function testSetByArray()
    {
        $model = new Vote();
        $result = $model->setByArray([
            'id'             => 1,
            'question'       => 'Frage 1',
            'key'            => 'frage1',
            'groups'         => '2',
            'status'         => 1,
            'multiple_reply' => 1,
            'read_access'    => '1,2,3',
            'read_access_all' => 0,
        ]);

        self::assertSame($model, $result);
        self::assertSame(1, $model->getId());
        self::assertSame('Frage 1', $model->getQuestion());
        self::assertSame('frage1', $model->getKey());
        self::assertSame('2', $model->getGroups());
        self::assertTrue($model->getStatus());
        self::assertTrue($model->getMultipleReply());
        self::assertSame('1,2,3', $model->getReadAccess());
    }

    /**
     * Test that a read_access_all value of 1 overwrites the read access with 'all'
     *
     * @return void
     */
    public function testSetByArrayReadAccessAllOverwritesReadAccess()
    {
        $model = new Vote();
        $model->setByArray([
            'id'             => 3,
            'question'       => 'Frage 3',
            'read_access'    => '1,2,3',
            'read_access_all' => 1,
        ]);

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Test that setByArray() only sets the given fields and keeps the defaults
     *
     * @return void
     */
    public function testSetByArrayPartial()
    {
        $model = new Vote();
        $model->setByArray(['id' => 4]);

        self::assertSame(4, $model->getId());
        self::assertSame('', $model->getQuestion());
        self::assertSame('', $model->getKey());
        self::assertSame('2,3', $model->getReadAccess());
        self::assertFalse($model->getStatus());
    }

    /**
     * Test that getArray() returns all fields as an array,
     * converting 'all' read access to read_access_all = 1
     *
     * @return void
     */
    public function testGetArray()
    {
        $model = new Vote();
        $model->setId(1)
            ->setQuestion('Frage')
            ->setKey('key')
            ->setGroups('1')
            ->setReadAccess('all')
            ->setStatus(true)
            ->setMultipleReply(true);

        self::assertSame([
            'id'              => 1,
            'question'        => 'Frage',
            'key'             => 'key',
            'status'          => true,
            'groups'          => '1',
            'read_access_all' => 1,
            'multiple_reply'  => true,
        ], $model->getArray());
    }

    /**
     * Test that getArray() can be called without including the id
     *
     * @return void
     */
    public function testGetArrayWithoutId()
    {
        $model = new Vote();
        $model->setId(1)->setQuestion('Frage')->setReadAccess('1,2');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('Frage', $array['question']);
        self::assertSame(0, $array['read_access_all']);
    }

    /**
     * Test that values can be overwritten by calling the setters again
     *
     * @return void
     */
    public function testOverwriteValues()
    {
        $model = new Vote();
        $model->setId(1)->setQuestion('Old')->setGroups('1');

        $model->setId(2)->setQuestion('New')->setGroups('2');

        self::assertSame(2, $model->getId());
        self::assertSame('New', $model->getQuestion());
        self::assertSame('2', $model->getGroups());
    }
}
