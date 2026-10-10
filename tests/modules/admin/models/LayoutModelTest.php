<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;
use Modules\Admin\Models\Layout as LayoutModel;

/**
 * Tests the Layout model class.
 *
 * @package ilch_phpunit
 */
class LayoutModelTest extends TestCase
{
    /**
     * Tests that setKey() sets and returns the key.
     */
    public function testSetKey()
    {
        $model = new LayoutModel();
        $model->setKey('default');

        self::assertSame('default', $model->getKey());
    }

    /**
     * Tests that setKey() casts to string.
     */
    public function testSetKeyCastsToString()
    {
        $model = new LayoutModel();
        $model->setKey(123);

        self::assertSame('123', $model->getKey());
        self::assertIsString($model->getKey());
    }

    /**
     * Tests that setName() sets and returns the name.
     */
    public function testSetName()
    {
        $model = new LayoutModel();
        $model->setName('Default Layout');

        self::assertSame('Default Layout', $model->getName());
    }

    /**
     * Tests that setVersion() sets and returns the version.
     */
    public function testSetVersion()
    {
        $model = new LayoutModel();
        $model->setVersion('1.0.0');

        self::assertSame('1.0.0', $model->getVersion());
    }

    /**
     * Tests that setVersion() casts to string.
     */
    public function testSetVersionCastsToString()
    {
        $model = new LayoutModel();
        $model->setVersion(2);

        self::assertSame('2', $model->getVersion());
        self::assertIsString($model->getVersion());
    }

    /**
     * Tests that setAuthor() sets and returns the author.
     */
    public function testSetAuthor()
    {
        $model = new LayoutModel();
        $model->setAuthor('Ilch Team');

        self::assertSame('Ilch Team', $model->getAuthor());
    }

    /**
     * Tests that setLink() sets and returns the link.
     */
    public function testSetLink()
    {
        $model = new LayoutModel();
        $model->setLink('https://www.ilch.de');

        self::assertSame('https://www.ilch.de', $model->getLink());
    }

    /**
     * Tests that setDesc() sets and returns the desc.
     */
    public function testSetDesc()
    {
        $model = new LayoutModel();
        $model->setDesc('A default layout.');

        self::assertSame('A default layout.', $model->getDesc());
    }

    /**
     * Tests that setModulekey() sets and returns the modulekey.
     */
    public function testSetModulekey()
    {
        $model = new LayoutModel();
        $model->setModulekey('admin');

        self::assertSame('admin', $model->getModulekey());
    }

    /**
     * Tests that setOfficial(true) stores true.
     */
    public function testSetOfficialTrue()
    {
        $model = new LayoutModel();
        $model->setOfficial(true);

        self::assertSame(true, $model->getOfficial());
        self::assertIsBool($model->getOfficial());
    }

    /**
     * Tests that setOfficial(0) stores false.
     */
    public function testSetOfficialFalse()
    {
        $model = new LayoutModel();
        $model->setOfficial(0);

        self::assertSame(false, $model->getOfficial());
        self::assertIsBool($model->getOfficial());
    }

    /**
     * Tests that setSettings() sets and returns the settings.
     */
    public function testSetSettings()
    {
        $model = new LayoutModel();
        $settings = ['sidebar' => 'left', 'columns' => '2'];
        $model->setSettings($settings);

        self::assertSame($settings, $model->getSettings());
    }

    /**
     * Tests that setIlchCore() sets and returns the required ilch version.
     */
    public function testSetIlchCore()
    {
        $model = new LayoutModel();
        $model->setIlchCore('2.1.32');

        self::assertSame('2.1.32', $model->getIlchCore());
    }

    /**
     * Tests that the fluent setters are chainable (return $this).
     * Only setSettings() and setIlchCore() return $this.
     */
    public function testFluentSettersReturnSelf()
    {
        $model = new LayoutModel();

        self::assertSame($model, $model->setSettings(['a' => 'b']));
        self::assertSame($model, $model->setIlchCore('2.1.32'));
    }

    /**
     * Tests that chaining the fluent setters builds a partial model.
     */
    public function testChainedFluentSetters()
    {
        $model = (new LayoutModel())
            ->setSettings(['sidebar' => 'left'])
            ->setIlchCore('2.1.32');

        self::assertSame(['sidebar' => 'left'], $model->getSettings());
        self::assertSame('2.1.32', $model->getIlchCore());
    }

    /**
     * Tests that default values are null.
     */
    public function testDefaultValues()
    {
        $model = new LayoutModel();

        self::assertNull($model->getKey());
        self::assertNull($model->getName());
        self::assertNull($model->getVersion());
        self::assertNull($model->getAuthor());
        self::assertNull($model->getLink());
        self::assertNull($model->getOfficial());
        self::assertNull($model->getDesc());
        self::assertNull($model->getModulekey());
        self::assertNull($model->getSettings());
        self::assertNull($model->getIlchCore());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new LayoutModel();
        $model->setKey('oldKey');
        $model->setName('Old Name');
        $model->setVersion('1.0.0');
        $model->setAuthor('Old Author');
        $model->setLink('https://www.old.de');
        $model->setOfficial(true);
        $model->setDesc('Old description');
        $model->setModulekey('oldModule');

        $model->setKey('newKey');
        $model->setName('New Name');
        $model->setVersion('2.0.0');
        $model->setAuthor('New Author');
        $model->setLink('https://www.new.de');
        $model->setOfficial(false);
        $model->setDesc('New description');
        $model->setModulekey('newModule');

        self::assertSame('newKey', $model->getKey());
        self::assertSame('New Name', $model->getName());
        self::assertSame('2.0.0', $model->getVersion());
        self::assertSame('New Author', $model->getAuthor());
        self::assertSame('https://www.new.de', $model->getLink());
        self::assertSame(false, $model->getOfficial());
        self::assertSame('New description', $model->getDesc());
        self::assertSame('newModule', $model->getModulekey());
    }
}
