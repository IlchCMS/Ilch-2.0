<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Models;

use PHPUnit\Framework\TestCase;

class ModuleModelTest extends TestCase
{
    /**
     * Tests the default values of a fresh model.
     */
    public function testDefaultValues()
    {
        $model = new Module();

        self::assertNull($model->getKey());
        self::assertSame('', $model->getIconSmall());
        self::assertFalse($model->getSystemModule());
        self::assertFalse($model->getLayoutModule());
        self::assertFalse($model->getHideMenu());
        self::assertSame('', $model->getAuthor());
        self::assertSame('', $model->getName());
        self::assertSame('', $model->getVersion());
        self::assertSame('', $model->getLink());
        self::assertFalse($model->getOfficial());
        self::assertSame('', $model->getIlchCore());
        self::assertSame('', $model->getPHPVersion());
        self::assertSame([], $model->getPHPExtension());
        self::assertSame([], $model->getDepends());
        self::assertSame([], $model->getCheckDepends());
        self::assertSame([], $model->getFolderRights());
        self::assertSame([], $model->getContent());
    }

    /**
     * Tests that all setters/adders return $this and are chainable.
     */
    public function testSettersReturnSelf()
    {
        $model = new Module();

        self::assertSame($model, $model->setKey('contact'));
        self::assertSame($model, $model->setAuthor('Author'));
        self::assertSame($model, $model->setIconSmall('icon.svg'));
        self::assertSame($model, $model->setSystemModule(true));
        self::assertSame($model, $model->setLayoutModule(true));
        self::assertSame($model, $model->setHideMenu(true));
        self::assertSame($model, $model->setName('Contact'));
        self::assertSame($model, $model->setVersion('1.0.0'));
        self::assertSame($model, $model->setLink('https://example.com'));
        self::assertSame($model, $model->setOfficial(true));
        self::assertSame($model, $model->setIlchCore('2.2.8'));
        self::assertSame($model, $model->setPHPVersion('8.1'));
        self::assertSame($model, $model->setPHPExtension(['pdo']));
        self::assertSame($model, $model->setDepends(['news' => '1.0']));
        self::assertSame($model, $model->setCheckDepends(['news' => true]));
        self::assertSame($model, $model->setFolderRights(['uploads']));
        self::assertSame($model, $model->addContent('en', 'Contact'));
        self::assertSame($model, $model->addPHPExtension('pdo', true));
        self::assertSame($model, $model->addCheckDepends('news', true));
        self::assertSame($model, $model->addFolderRight('uploads', true));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new Module())
            ->setKey('contact')
            ->setName('Contact')
            ->setVersion('1.0.0')
            ->setAuthor('Ilch Team')
            ->setSystemModule(true)
            ->setOfficial(true);

        self::assertSame('contact', $model->getKey());
        self::assertSame('Contact', $model->getName());
        self::assertSame('1.0.0', $model->getVersion());
        self::assertSame('Ilch Team', $model->getAuthor());
        self::assertTrue($model->getSystemModule());
        self::assertTrue($model->getOfficial());
    }

    /**
     * Tests that addContent() stores content per language.
     */
    public function testAddContent()
    {
        $model = new Module();
        $model->addContent('en', 'Contact');
        $model->addContent('de', 'Kontakt');

        self::assertSame(['en' => 'Contact', 'de' => 'Kontakt'], $model->getContent());
    }

    /**
     * Tests that addContent() overwrites an existing language entry.
     */
    public function testAddContentOverwrite()
    {
        $model = new Module();
        $model->addContent('en', 'Old');
        $model->addContent('en', 'New');

        self::assertSame(['en' => 'New'], $model->getContent());
        self::assertSame('New', $model->getContentForLocale('en'));
    }

    /**
     * Tests that getContentForLocale() returns null for a missing locale.
     */
    public function testGetContentForLocaleMissing()
    {
        $model = new Module();

        self::assertNull($model->getContentForLocale('en'));
    }

    /**
     * Tests that addPHPExtension() defaults the state to false.
     */
    public function testAddPHPExtensionDefaultState()
    {
        $model = new Module();
        $model->addPHPExtension('pdo_mysql');

        self::assertSame(['pdo_mysql' => false], $model->getPHPExtension());
    }

    /**
     * Tests that addPHPExtension() accepts an explicit state.
     */
    public function testAddPHPExtensionWithState()
    {
        $model = new Module();
        $model->addPHPExtension('pdo_mysql', true);

        self::assertSame(['pdo_mysql' => true], $model->getPHPExtension());
    }

    /**
     * Tests that setPHPExtension() replaces the whole list.
     */
    public function testSetPHPExtension()
    {
        $model = new Module();
        $model->setPHPExtension(['gd' => false, 'curl' => true]);

        self::assertSame(['gd' => false, 'curl' => true], $model->getPHPExtension());
    }

    /**
     * Tests that addCheckDepends() stores the state per key.
     */
    public function testAddCheckDepends()
    {
        $model = new Module();
        $model->addCheckDepends('news', true);
        $model->addCheckDepends('gallery', false);

        self::assertSame(['news' => true, 'gallery' => false], $model->getCheckDepends());
    }

    /**
     * Tests that setCheckDepends() replaces the whole list.
     */
    public function testSetCheckDepends()
    {
        $model = new Module();
        $model->setCheckDepends(['news' => true]);

        self::assertSame(['news' => true], $model->getCheckDepends());
    }

    /**
     * Tests that addFolderRight() defaults the state to false.
     */
    public function testAddFolderRightDefaultState()
    {
        $model = new Module();
        $model->addFolderRight('uploads/contact');

        self::assertSame(['uploads/contact' => false], $model->getFolderRights());
    }

    /**
     * Tests that setFolderRights() replaces the whole list.
     */
    public function testSetFolderRights()
    {
        $model = new Module();
        $model->setFolderRights(['uploads/contact' => true]);

        self::assertSame(['uploads/contact' => true], $model->getFolderRights());
    }

    /**
     * Tests that setDepends() stores the dependencies.
     */
    public function testSetDepends()
    {
        $model = new Module();
        $model->setDepends(['news' => '1.0']);

        self::assertSame(['news' => '1.0'], $model->getDepends());
    }

    /**
     * Tests that getArray() returns all fields as plain values.
     */
    public function testGetArray()
    {
        $model = (new Module())
            ->setKey('contact')
            ->setSystemModule(true)
            ->setLayoutModule(true)
            ->setHideMenu(true)
            ->setIconSmall('contact.svg')
            ->setVersion('1.0.0')
            ->setLink('https://www.ilch.de')
            ->setAuthor('Ilch Team');

        self::assertSame([
            'key' => 'contact',
            'system' => 1,
            'layout' => 1,
            'hide_menu' => 1,
            'icon_small' => 'contact.svg',
            'version' => '1.0.0',
            'link' => 'https://www.ilch.de',
            'author' => 'Ilch Team',
        ], $model->getArray());
    }

    /**
     * Tests that getArray() contains defaults on a fresh model.
     */
    public function testGetArrayDefaults()
    {
        $model = new Module();

        self::assertSame([
            'key' => null,
            'system' => 0,
            'layout' => 0,
            'hide_menu' => 0,
            'icon_small' => '',
            'version' => '',
            'link' => '',
            'author' => '',
        ], $model->getArray());
    }

    /**
     * Tests that setByArray() populates the model from an entry array.
     */
    public function testSetByArray()
    {
        $model = new Module();
        $model->setByArray([
            'name' => 'Contact',
            'key' => 'contact',
            'author' => 'Ilch Team',
            'languages' => ['en' => 'Contact', 'de' => 'Kontakt'],
            'system_module' => 1,
            'hide_menu' => 1,
            'official' => 1,
            'link' => 'https://www.ilch.de',
            'version' => '1.0.0',
            'icon_small' => 'contact.svg',
            'ilchCore' => '2.2.8',
            'phpVersion' => '8.1',
            'phpExtensions' => ['pdo_mysql', 'gd'],
            'depends' => ['news' => '1.0'],
            'folderRights' => ['uploads/contact'],
        ]);

        self::assertSame('Contact', $model->getName());
        self::assertSame('contact', $model->getKey());
        self::assertSame('Ilch Team', $model->getAuthor());
        self::assertSame('Contact', $model->getContentForLocale('en'));
        self::assertSame('Kontakt', $model->getContentForLocale('de'));
        self::assertTrue($model->getSystemModule());
        self::assertFalse($model->getLayoutModule());
        self::assertTrue($model->getHideMenu());
        self::assertTrue($model->getOfficial());
        self::assertSame('https://www.ilch.de', $model->getLink());
        self::assertSame('1.0.0', $model->getVersion());
        self::assertSame('contact.svg', $model->getIconSmall());
        self::assertSame('2.2.8', $model->getIlchCore());
        self::assertSame('8.1', $model->getPHPVersion());
        self::assertSame(['pdo_mysql' => false, 'gd' => false], $model->getPHPExtension());
        self::assertSame(['news' => '1.0'], $model->getDepends());
        self::assertSame(['news' => false], $model->getCheckDepends());
        self::assertSame(['uploads/contact' => false], $model->getFolderRights());
    }

    /**
     * Tests that setByArray() accepts the short "system" and "layout" keys.
     */
    public function testSetByArraySystemAndLayoutAliases()
    {
        $model = new Module();
        $model->setByArray([
            'system' => 1,
            'layout' => 1,
        ]);

        self::assertTrue($model->getSystemModule());
        self::assertTrue($model->getLayoutModule());
    }

    /**
     * Tests that setByArray() returns $this.
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new Module();

        self::assertSame($model, $model->setByArray(['name' => 'Contact']));
    }

    /**
     * Tests that setByArray() with an empty array leaves defaults untouched.
     */
    public function testSetByArrayEmpty()
    {
        $model = new Module();
        $model->setByArray([]);

        self::assertSame('', $model->getName());
        self::assertNull($model->getKey());
        self::assertFalse($model->getSystemModule());
        self::assertFalse($model->getLayoutModule());
        self::assertSame([], $model->getDepends());
    }
}
