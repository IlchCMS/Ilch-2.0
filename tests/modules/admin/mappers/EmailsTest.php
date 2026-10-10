<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Admin\Config\Config as ModuleConfig;
use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Admin\Models\Emails as EmailsModel;

class EmailsTest extends DatabaseTestCase
{
    /**
     * @var EmailsMapper
     */
    protected Emails $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new EmailsMapper();
    }

    /**
     * Tests that getEmailsModule() returns the unique module keys of all modules with emails.
     */
    public function testGetEmailsModule()
    {
        $emailsModule = $this->out->getEmailsModule();

        self::assertNotNull($emailsModule);
        self::assertCount(2, $emailsModule);
        self::assertInstanceOf(EmailsModel::class, $emailsModule[0]);

        // Ordered by moduleKey DESC
        self::assertEquals('lastwar', $emailsModule[0]->getModuleKey());
        self::assertEquals('admin', $emailsModule[1]->getModuleKey());
    }

    /**
     * Tests that getEmailsModule() returns null when no emails exist.
     */
    public function testGetEmailsModuleEmpty()
    {
        $this->db->delete('emails')->execute();

        self::assertNull($this->out->getEmailsModule());
    }

    /**
     * Tests that getEmailsByKey() returns all emails of the given module and locale.
     */
    public function testGetEmailsByKey()
    {
        $emails = $this->out->getEmailsByKey('admin', 'de_DE');

        self::assertNotNull($emails);
        self::assertCount(2, $emails);

        $types = [];
        foreach ($emails as $email) {
            $types[] = $email->getType();
        }
        sort($types);
        self::assertEquals(['installSuccess', 'updateAvailable'], $types);
    }

    /**
     * Tests that getEmailsByKey() returns the correct fields.
     */
    public function testGetEmailsByKeyFields()
    {
        $emails = $this->out->getEmailsByKey('admin', 'en_EN');

        self::assertNotNull($emails);
        self::assertCount(1, $emails);

        self::assertEquals('installSuccess', $emails[0]->getType());
        self::assertEquals('Installation successful', $emails[0]->getDesc());
        self::assertEquals('en_EN', $emails[0]->getLocale());
    }

    /**
     * Tests that getEmailsByKey() returns null if no emails match.
     */
    public function testGetEmailsByKeyEmpty()
    {
        self::assertNull($this->out->getEmailsByKey('lastwar', 'en_EN'));
        self::assertNull($this->out->getEmailsByKey('contact', 'de_DE'));
    }

    /**
     * Tests that getEmailsByKeyTypeLocale() returns the email of the given combination.
     */
    public function testGetEmailsByKeyTypeLocale()
    {
        $emails = $this->out->getEmailsByKeyTypeLocale('lastwar', 'warStarted', 'de_DE');

        self::assertNotNull($emails);
        self::assertCount(1, $emails);

        self::assertEquals('warStarted', $emails[0]->getType());
        self::assertEquals('War started', $emails[0]->getDesc());
        self::assertEquals('de_DE', $emails[0]->getLocale());
    }

    /**
     * Tests that getEmailsByKeyTypeLocale() returns null if nothing matches.
     */
    public function testGetEmailsByKeyTypeLocaleEmpty()
    {
        self::assertNull($this->out->getEmailsByKeyTypeLocale('lastwar', 'warEnded', 'de_DE'));
    }

    /**
     * Tests that getEmail() returns the email with all fields for the requested locale.
     */
    public function testGetEmail()
    {
        $email = $this->out->getEmail('admin', 'installSuccess', 'de_DE');

        self::assertNotNull($email);
        self::assertEquals('admin', $email->getModuleKey());
        self::assertEquals('installSuccess', $email->getType());
        self::assertEquals('Installation successful', $email->getDesc());
        self::assertEquals('Your installation was successful.', $email->getText());
        self::assertEquals('de_DE', $email->getLocale());
    }

    /**
     * Tests that getEmail() falls back to de_DE if the requested locale does not exist.
     */
    public function testGetEmailFallsBackToDeDe()
    {
        $email = $this->out->getEmail('lastwar', 'warStarted', 'en_EN');

        self::assertNotNull($email);
        self::assertEquals('lastwar', $email->getModuleKey());
        self::assertEquals('warStarted', $email->getType());
        self::assertEquals('A new war has started.', $email->getText());
        self::assertEquals('de_DE', $email->getLocale());
    }

    /**
     * Tests that getEmail() returns null if neither the locale nor the de_DE fallback exists.
     */
    public function testGetEmailNotFound()
    {
        self::assertNull($this->out->getEmail('lastwar', 'warEnded', 'de_DE'));
        self::assertNull($this->out->getEmail('contact', 'warStarted', 'de_DE'));
    }

    /**
     * Tests that save() inserts a new email.
     */
    public function testSaveInsert()
    {
        $model = new EmailsModel();
        $model->setModuleKey('lastwar');
        $model->setType('warEnded');
        $model->setDesc('War ended');
        $model->setText('The war has ended.');
        $model->setLocale('de_DE');

        $this->out->save($model);

        $email = $this->out->getEmail('lastwar', 'warEnded', 'de_DE');
        self::assertNotNull($email);
        self::assertEquals('lastwar', $email->getModuleKey());
        self::assertEquals('warEnded', $email->getType());
        self::assertEquals('War ended', $email->getDesc());
        self::assertEquals('The war has ended.', $email->getText());
        self::assertEquals('de_DE', $email->getLocale());

        // The old entry is still there
        $emails = $this->out->getEmailsByKey('lastwar', 'de_DE');
        self::assertCount(2, $emails);
    }

    /**
     * Tests that save() updates an existing email instead of creating a duplicate.
     */
    public function testSaveUpdate()
    {
        $model = new EmailsModel();
        $model->setModuleKey('lastwar');
        $model->setType('warStarted');
        $model->setDesc('War started (updated)');
        $model->setText('A new war has started (updated).');
        $model->setLocale('de_DE');

        $this->out->save($model);

        $email = $this->out->getEmail('lastwar', 'warStarted', 'de_DE');
        self::assertNotNull($email);
        self::assertEquals('War started (updated)', $email->getDesc());
        self::assertEquals('A new war has started (updated).', $email->getText());

        // No duplicate row was created
        $emails = $this->out->getEmailsByKey('lastwar', 'de_DE');
        self::assertCount(1, $emails);
    }

    /**
     * Tests that saving for a new locale does not touch the de_DE entry.
     */
    public function testSaveDifferentLocale()
    {
        $model = new EmailsModel();
        $model->setModuleKey('lastwar');
        $model->setType('warStarted');
        $model->setDesc('War started');
        $model->setText('A new war has started.');
        $model->setLocale('en_EN');

        $this->out->save($model);

        // New en_EN entry exists now
        $emailEn = $this->out->getEmail('lastwar', 'warStarted', 'en_EN');
        self::assertNotNull($emailEn);
        self::assertEquals('en_EN', $emailEn->getLocale());
        self::assertEquals('A new war has started.', $emailEn->getText());

        // de_DE entry untouched
        $emailDe = $this->out->getEmail('lastwar', 'warStarted', 'de_DE');
        self::assertNotNull($emailDe);
        self::assertEquals('A new war has started.', $emailDe->getText());
    }

    /**
     * Tests that updating one email does not affect emails of other modules.
     */
    public function testSaveDoesNotAffectOthers()
    {
        $model = new EmailsModel();
        $model->setModuleKey('lastwar');
        $model->setType('warStarted');
        $model->setDesc('Changed');
        $model->setText('Changed text');
        $model->setLocale('de_DE');

        $this->out->save($model);

        $email = $this->out->getEmail('admin', 'installSuccess', 'de_DE');
        self::assertNotNull($email);
        self::assertEquals('Installation successful', $email->getDesc());
        self::assertEquals('Your installation was successful.', $email->getText());
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();

        return $config->getInstallSql();
    }
}
