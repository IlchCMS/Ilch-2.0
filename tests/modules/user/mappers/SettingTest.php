<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use Modules\Admin\Config\Config as AdminConfig;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\User\Mappers\Setting as SettingMapper;

class SettingTest extends DatabaseTestCase
{
    protected SettingMapper $out;
    protected PhpunitDataset $phpunitDataset;
    protected array $workDirs = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database_setting.yml');
        $this->out = new SettingMapper();
    }

    protected function tearDown(): void
    {
        foreach ($this->workDirs as $dir) {
            $this->removeDir($dir);
        }
        $this->workDirs = [];

        parent::tearDown();
    }

    /**
     * Tests that getNicebytes() picks KB for small values.
     */
    public function testGetNicebytesAutoFormatKB()
    {
        self::assertSame('0.5 KB', $this->out->getNicebytes(512));
        self::assertSame('1 KB', $this->out->getNicebytes(1024));
        self::assertSame('1.5 KB', $this->out->getNicebytes(1536));
        self::assertSame('976.56 KB', $this->out->getNicebytes(999999));
    }

    /**
     * Tests that getNicebytes() picks MB for large values.
     */
    public function testGetNicebytesAutoFormatMB()
    {
        self::assertSame('1 MB', $this->out->getNicebytes(1048576));
        self::assertSame('2 MB', $this->out->getNicebytes(2097152));
        self::assertSame('0.95 MB', $this->out->getNicebytes(1000000));
    }

    /**
     * Tests that getNicebytes() handles zero bytes.
     */
    public function testGetNicebytesZero()
    {
        self::assertSame('0 KB', $this->out->getNicebytes(0));
    }

    /**
     * Tests that getNicebytes() honours an explicitly given format.
     */
    public function testGetNicebytesExplicitFormat()
    {
        self::assertSame('0.5 KB', $this->out->getNicebytes(512, 'KB'));
        self::assertSame('0 MB', $this->out->getNicebytes(512, 'MB'));
        self::assertSame('512 B', $this->out->getNicebytes(512, 'B'));
    }

    /**
     * Tests that getNicebytes() returns a number without a unit when $addFormat is false.
     */
    public function testGetNicebytesWithoutFormatSuffix()
    {
        self::assertSame(0.5, $this->out->getNicebytes(512, 'KB', false));
        self::assertSame(1.0, $this->out->getNicebytes(1048576, 'MB', false));
        self::assertSame(512, $this->out->getNicebytes(512, 'B', false));
    }

    /**
     * Tests the return types of getNicebytes().
     */
    public function testGetNicebytesReturnType()
    {
        self::assertIsString($this->out->getNicebytes(512));
        self::assertIsFloat($this->out->getNicebytes(512, 'KB', false));
        self::assertIsInt($this->out->getNicebytes(512, 'B', false));
    }

    /**
     * Tests that delAvatarById() resets the avatar of a user without a custom avatar file.
     */
    public function testDelAvatarByIdEmptyAvatar()
    {
        $this->out->delAvatarById(1);

        $avatar = $this->db->select('avatar')->from('users')->where(['id' => 1])->execute()->fetchCell('avatar');
        self::assertSame('', $avatar);
    }

    /**
     * Tests that delAvatarById() deletes the file of an existing custom avatar and resets the field.
     */
    public function testDelAvatarByIdDeletesExistingCustomAvatarFile()
    {
        $workDir = $this->createWorkDir();
        $avatarRel = 'static/upload/avatar/custom1.png';
        $avatarAbs = $workDir . '/' . $avatarRel;
        $this->createFile($avatarAbs);

        $this->db->update('users')
            ->values(['avatar' => $avatarRel])
            ->where(['id' => 2])
            ->execute();

        $this->runInDir($workDir, function () {
            $this->out->delAvatarById(2);
        });

        self::assertFalse(file_exists($avatarAbs));
        $avatar = $this->db->select('avatar')->from('users')->where(['id' => 2])->execute()->fetchCell('avatar');
        self::assertSame('', $avatar);
    }

    /**
     * Tests that delAvatarById() does NOT delete the default noavatar.jpg image.
     * NOTE: This test is expected to FAIL with the current mapper implementation
     * (see the findings below: !strpos() treats a match at position 0 as "not found").
     */
    public function testDelAvatarByIdKeepsDefaultNoavatarImage()
    {
        $workDir = $this->createWorkDir();
        $noavatarRel = 'static/img/noavatar.jpg';
        $noavatarAbs = $workDir . '/' . $noavatarRel;
        $this->createFile($noavatarAbs);

        $this->db->update('users')
            ->values(['avatar' => $noavatarRel])
            ->where(['id' => 3])
            ->execute();

        $this->runInDir($workDir, function () {
            $this->out->delAvatarById(3);
        });

        self::assertTrue(file_exists($noavatarAbs), 'The default noavatar.jpg image must not be deleted.');
        $avatar = $this->db->select('avatar')->from('users')->where(['id' => 3])->execute()->fetchCell('avatar');
        self::assertSame('', $avatar);
    }

    /**
     * Tests that delAvatarById() on a non-existent id does not affect other users.
     */
    public function testDelAvatarByIdNotFound()
    {
        $this->out->delAvatarById(9999);

        $avatar = $this->db->select('avatar')->from('users')->where(['id' => 1])->execute()->fetchCell('avatar');
        self::assertSame('', $avatar);
    }

    /**
     * Creates a temporary working directory and registers it for cleanup.
     */
    private function createWorkDir(): string
    {
        $dir = sys_get_temp_dir() . '/ilch_setting_test_' . uniqid('', true);
        mkdir($dir, 0777, true);
        $this->workDirs[] = $dir;

        return $dir;
    }

    private function createFile(string $path): void
    {
        mkdir(dirname($path), 0777, true);
        file_put_contents($path, 'test');
    }

    /**
     * Runs the given callback with the process working directory set to $dir.
     */
    private function runInDir(string $dir, callable $callback): void
    {
        $previous = getcwd();
        chdir($dir);
        try {
            $callback();
        } finally {
            chdir($previous);
        }
    }

    private function removeDir(string $dir): void
    {
        foreach (scandir($dir) as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . '/' . $item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    /**
     * Returns database schema sql statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $configAdmin = new AdminConfig();

        return $configAdmin->getInstallSql() . $config->getInstallSql();
    }
}
