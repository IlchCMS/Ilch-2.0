<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Emails as EmailsModel;

/**
 * Tests the Emails model class.
 *
 * @package ilch_phpunit
 */
class EmailsModelTest extends TestCase
{
    /**
     * Tests that setModuleKey() sets and returns the module key.
     */
    public function testSetModuleKey()
    {
        $model = new EmailsModel();
        $model->setModuleKey('article');

        self::assertSame('article', $model->getModuleKey());
    }

    /**
     * Tests that setModuleKey() casts to string.
     */
    public function testSetModuleKeyCastsToString()
    {
        $model = new EmailsModel();
        $model->setModuleKey(123);

        self::assertSame('123', $model->getModuleKey());
        self::assertIsString($model->getModuleKey());
    }

    /**
     * Tests that setType() sets and returns the type.
     */
    public function testSetType()
    {
        $model = new EmailsModel();
        $model->setType('newArticle');

        self::assertSame('newArticle', $model->getType());
    }

    /**
     * Tests that setType() casts to string.
     */
    public function testSetTypeCastsToString()
    {
        $model = new EmailsModel();
        $model->setType(456);

        self::assertSame('456', $model->getType());
        self::assertIsString($model->getType());
    }

    /**
     * Tests that setDesc() sets and returns the desc.
     */
    public function testSetDesc()
    {
        $model = new EmailsModel();
        $model->setDesc('Description of the email');

        self::assertSame('Description of the email', $model->getDesc());
    }

    /**
     * Tests that setDesc() casts to string.
     */
    public function testSetDescCastsToString()
    {
        $model = new EmailsModel();
        $model->setDesc(789);

        self::assertSame('789', $model->getDesc());
        self::assertIsString($model->getDesc());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new EmailsModel();
        $model->setText('Email text');

        self::assertSame('Email text', $model->getText());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new EmailsModel();
        $model->setText(45678);

        self::assertSame('45678', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setLocale() sets and returns the locale.
     */
    public function testSetLocale()
    {
        $model = new EmailsModel();
        $model->setLocale('de_DE');

        self::assertSame('de_DE', $model->getLocale());
    }

    /**
     * Tests that setLocale() casts to string.
     */
    public function testSetLocaleCastsToString()
    {
        $model = new EmailsModel();
        $model->setLocale(12345);

        self::assertSame('12345', $model->getLocale());
        self::assertIsString($model->getLocale());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new EmailsModel();

        self::assertSame($model, $model->setModuleKey('article'));
        self::assertSame($model, $model->setType('newArticle'));
        self::assertSame($model, $model->setDesc('Description'));
        self::assertSame($model, $model->setText('Text'));
        self::assertSame($model, $model->setLocale('de_DE'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new EmailsModel())
            ->setModuleKey('article')
            ->setType('newArticle')
            ->setDesc('A new article was created.')
            ->setText('Please check the article.')
            ->setLocale('en_EN');

        self::assertSame('article', $model->getModuleKey());
        self::assertSame('newArticle', $model->getType());
        self::assertSame('A new article was created.', $model->getDesc());
        self::assertSame('Please check the article.', $model->getText());
        self::assertSame('en_EN', $model->getLocale());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new EmailsModel();

        self::assertNull($model->getModuleKey());
        self::assertNull($model->getType());
        self::assertNull($model->getDesc());
        self::assertNull($model->getText());
        self::assertNull($model->getLocale());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new EmailsModel();
        $model->setModuleKey('article');
        $model->setType('oldType');
        $model->setDesc('Old description');
        $model->setText('Old text');
        $model->setLocale('de_DE');

        $model->setModuleKey('awards');
        $model->setType('newType');
        $model->setDesc('New description');
        $model->setText('New text');
        $model->setLocale('en_EN');

        self::assertSame('awards', $model->getModuleKey());
        self::assertSame('newType', $model->getType());
        self::assertSame('New description', $model->getDesc());
        self::assertSame('New text', $model->getText());
        self::assertSame('en_EN', $model->getLocale());
    }
}
