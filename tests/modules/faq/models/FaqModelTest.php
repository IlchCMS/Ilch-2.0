<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Faq\Models;

use PHPUnit\Framework\TestCase;

class FaqModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new Faq();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new Faq();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setCatId() sets and returns the catId.
     */
    public function testSetCatId()
    {
        $model = new Faq();
        $model->setCatId(3);

        self::assertSame(3, $model->getCatId());
    }

    /**
     * Tests that setCatId() casts to int.
     */
    public function testSetCatIdCastsToInt()
    {
        $model = new Faq();
        $model->setCatId('7');

        self::assertSame(7, $model->getCatId());
        self::assertIsInt($model->getCatId());
    }

    /**
     * Tests that setQuestion() sets and returns the question.
     */
    public function testSetQuestion()
    {
        $model = new Faq();
        $model->setQuestion('What is Ilch?');

        self::assertSame('What is Ilch?', $model->getQuestion());
    }

    /**
     * Tests that setQuestion() casts to string.
     */
    public function testSetQuestionCastsToString()
    {
        $model = new Faq();
        $model->setQuestion(999);

        self::assertSame('999', $model->getQuestion());
        self::assertIsString($model->getQuestion());
    }

    /**
     * Tests that setAnswer() sets and returns the answer.
     */
    public function testSetAnswer()
    {
        $model = new Faq();
        $model->setAnswer('Ilch is a CMS.');

        self::assertSame('Ilch is a CMS.', $model->getAnswer());
    }

    /**
     * Tests that setAnswer() casts to string.
     */
    public function testSetAnswerCastsToString()
    {
        $model = new Faq();
        $model->setAnswer(12345);

        self::assertSame('12345', $model->getAnswer());
        self::assertIsString($model->getAnswer());
    }

    /**
     * Tests that setReadAccess() sets and returns the read access.
     */
    public function testSetReadAccess()
    {
        $model = new Faq();
        $model->setReadAccess('1,2,3');

        self::assertSame('1,2,3', $model->getReadAccess());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new Faq();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setCatId(2));
        self::assertSame($model, $model->setQuestion('Q'));
        self::assertSame($model, $model->setAnswer('A'));
        self::assertSame($model, $model->setReadAccess('all'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Faq())
            ->setId(1)
            ->setCatId(1)
            ->setQuestion('What is Ilch?')
            ->setAnswer('Ilch is a CMS.')
            ->setReadAccess('all');

        self::assertSame(1, $model->getId());
        self::assertSame(1, $model->getCatId());
        self::assertSame('What is Ilch?', $model->getQuestion());
        self::assertSame('Ilch is a CMS.', $model->getAnswer());
        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests that default values are 0 for id/catId and empty string for question/answer/readAccess.
     */
    public function testDefaultValues()
    {
        $model = new Faq();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getCatId());
        self::assertSame('', $model->getQuestion());
        self::assertSame('', $model->getAnswer());
        self::assertSame('', $model->getReadAccess());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid for new entries).
     */
    public function testSetIdZero()
    {
        $model = new Faq();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new Faq();
        $model->setId(1)->setCatId(1)->setQuestion('Old Q')->setAnswer('Old A');

        $model->setId(2)->setCatId(2)->setQuestion('New Q')->setAnswer('New A');

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getCatId());
        self::assertSame('New Q', $model->getQuestion());
        self::assertSame('New A', $model->getAnswer());
    }

    /**
     * Tests getArray() with id included.
     */
    public function testGetArrayWithId()
    {
        $model = new Faq();
        $model->setId(5)->setCatId(1)->setQuestion('Q?')->setAnswer('A.');

        $array = $model->getArray();

        self::assertSame([
            'id'       => 5,
            'cat_id'   => 1,
            'question' => 'Q?',
            'answer'   => 'A.',
        ], $array);
    }

    /**
     * Tests getArray() without id.
     */
    public function testGetArrayWithoutId()
    {
        $model = new Faq();
        $model->setId(5)->setCatId(1)->setQuestion('Q?')->setAnswer('A.');

        $array = $model->getArray(false);

        self::assertSame([
            'cat_id'   => 1,
            'question' => 'Q?',
            'answer'   => 'A.',
        ], $array);
    }

    /**
     * Tests setByArray() with standard fields.
     */
    public function testSetByArray()
    {
        $model = new Faq();
        $model->setByArray([
            'id'       => 4,
            'cat_id'   => 1,
            'question' => 'What is Ilch?',
            'answer'   => 'Ilch is a CMS.',
        ]);

        self::assertSame(4, $model->getId());
        self::assertSame(1, $model->getCatId());
        self::assertSame('What is Ilch?', $model->getQuestion());
        self::assertSame('Ilch is a CMS.', $model->getAnswer());
    }

    /**
     * Tests setByArray() with read_access_all = 1 sets readAccess to 'all'.
     */
    public function testSetByArrayWithReadAccessAll()
    {
        $model = new Faq();
        $model->setByArray([
            'id'              => 1,
            'cat_id'          => 1,
            'question'        => 'Q',
            'answer'          => 'A',
            'read_access_all' => 1,
        ]);

        self::assertSame('all', $model->getReadAccess());
    }

    /**
     * Tests setByArray() with read_access (from GROUP_CONCAT).
     */
    public function testSetByArrayWithReadAccess()
    {
        $model = new Faq();
        $model->setByArray([
            'id'          => 2,
            'cat_id'      => 2,
            'question'    => 'Q',
            'answer'      => 'A',
            'read_access' => '1,2',
        ]);

        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests setByArray() with read_access_all = 0 does NOT overwrite read_access.
     */
    public function testSetByArrayReadAccessAllZero()
    {
        $model = new Faq();
        $model->setByArray([
            'id'              => 2,
            'cat_id'          => 2,
            'question'        => 'Q',
            'answer'          => 'A',
            'read_access'     => '1,2',
            'read_access_all' => 0,
        ]);

        self::assertSame('1,2', $model->getReadAccess());
    }

    /**
     * Tests setByArray() is chainable.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Faq();
        $result = $model->setByArray(['id' => 1, 'question' => 'Q']);

        self::assertSame($model, $result);
    }
}
