<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Infos as InfosModel;

/**
 * Tests the Infos model class.
 *
 * @package ilch_phpunit
 */
class InfosModelTest extends TestCase
{
    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new InfosModel();
        $model->setKey('article');

        self::assertSame('article', $model->getKey());
    }

    /**
     * Tests that setFolder() sets and returns the folder.
     */
    public function testSetFolder()
    {
        $model = new InfosModel();
        $model->setFolder('modules');

        self::assertSame('modules', $model->getFolder());
    }

    /**
     * Tests that setExtension() sets and returns the extension.
     */
    public function testSetExtension()
    {
        $model = new InfosModel();
        $model->setExtension('tpl');

        self::assertSame('tpl', $model->getExtension());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new InfosModel();

        self::assertSame($model, $model->setKey('article'));
        self::assertSame($model, $model->setFolder('modules'));
        self::assertSame($model, $model->setExtension('tpl'));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new InfosModel())
            ->setKey('article')
            ->setFolder('modules')
            ->setExtension('php');

        self::assertSame('article', $model->getKey());
        self::assertSame('modules', $model->getFolder());
        self::assertSame('php', $model->getExtension());
    }

    /**
     * Tests that default values are null for key and empty string for folder/extension.
     */
    public function testDefaultValues()
    {
        $model = new InfosModel();

        self::assertNull($model->getKey());
        self::assertSame('', $model->getFolder());
        self::assertSame('', $model->getExtension());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new InfosModel();
        $model->setKey('oldKey');
        $model->setFolder('oldFolder');
        $model->setExtension('oldExtension');

        $model->setKey('newKey');
        $model->setFolder('newFolder');
        $model->setExtension('newExtension');

        self::assertSame('newKey', $model->getKey());
        self::assertSame('newFolder', $model->getFolder());
        self::assertSame('newExtension', $model->getExtension());
    }
}
