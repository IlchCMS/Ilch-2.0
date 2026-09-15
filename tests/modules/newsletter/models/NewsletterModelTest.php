<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Newsletter\Models;

use PHPUnit\Framework\TestCase;
use Modules\Newsletter\Models\Newsletter as NewsletterModel;

class NewsletterModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new NewsletterModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new NewsletterModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new NewsletterModel();
        $model->setUserId(7);

        self::assertSame(7, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new NewsletterModel();
        $model->setUserId('10');

        self::assertSame(10, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setDateCreated() sets and returns the dateCreated.
     */
    public function testSetDateCreated()
    {
        $model = new NewsletterModel();
        $model->setDateCreated('2024-06-15 12:00:00');

        self::assertSame('2024-06-15 12:00:00', $model->getDateCreated());
    }

    /**
     * Tests that setDateCreated() casts to string.
     */
    public function testSetDateCreatedCastsToString()
    {
        $model = new NewsletterModel();
        $model->setDateCreated(1718438400);

        self::assertSame('1718438400', $model->getDateCreated());
        self::assertIsString($model->getDateCreated());
    }

    /**
     * Tests that setSubject() sets and returns the subject.
     */
    public function testSetSubject()
    {
        $model = new NewsletterModel();
        $model->setSubject('Monthly Update');

        self::assertSame('Monthly Update', $model->getSubject());
    }

    /**
     * Tests that setSubject() casts to string.
     */
    public function testSetSubjectCastsToString()
    {
        $model = new NewsletterModel();
        $model->setSubject(999);

        self::assertSame('999', $model->getSubject());
        self::assertIsString($model->getSubject());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new NewsletterModel();
        $model->setText('Hello, subscribers!');

        self::assertSame('Hello, subscribers!', $model->getText());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new NewsletterModel();
        $model->setText(12345);

        self::assertSame('12345', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new NewsletterModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(2));
        self::assertSame($model, $model->setDateCreated('2024-01-01 00:00:00'));
        self::assertSame($model, $model->setSubject('Test'));
        self::assertSame($model, $model->setText('Body'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new NewsletterModel())
            ->setId(3)
            ->setUserId(5)
            ->setDateCreated('2024-03-10 09:00:00')
            ->setSubject('Q1 Report')
            ->setText('Here is the quarterly report.');

        self::assertSame(3, $model->getId());
        self::assertSame(5, $model->getUserId());
        self::assertSame('2024-03-10 09:00:00', $model->getDateCreated());
        self::assertSame('Q1 Report', $model->getSubject());
        self::assertSame('Here is the quarterly report.', $model->getText());
    }

    /**
     * Tests that default value for id is null.
     */
    public function testDefaultValues()
    {
        $model = new NewsletterModel();

        self::assertNull($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new NewsletterModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new NewsletterModel();
        $model->setId(1)
            ->setUserId(1)
            ->setDateCreated('2024-01-01 00:00:00')
            ->setSubject('Old')
            ->setText('Old text');

        $model->setId(2)
            ->setUserId(9)
            ->setDateCreated('2024-06-01 12:00:00')
            ->setSubject('New')
            ->setText('New text');

        self::assertSame(2, $model->getId());
        self::assertSame(9, $model->getUserId());
        self::assertSame('2024-06-01 12:00:00', $model->getDateCreated());
        self::assertSame('New', $model->getSubject());
        self::assertSame('New text', $model->getText());
    }
}
