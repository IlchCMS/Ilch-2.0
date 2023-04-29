<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use Ilch\Validation\Validators\Unique;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use stdClass;

/**
 * Tests the Unique validator.
 *
 * @package ilch_phpunit
 */
class UniqueTest extends DatabaseTestCase
{
    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
    }

    /**
     * Returns database schema sql statements to initialize database
     *
     * @return string
     */
    protected static function getSchemaSQLQueries()
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_groups` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2;';
    }

    /**
     * @dataProvider dpForTestValidator
     *
     * @param stdClass $data
     * @param bool $expectedIsValid
     * @param string $expectedErrorKey
     * @param array $expectedErrorParameters
     */
    public function testValidator(stdClass $data, bool $expectedIsValid, string $expectedErrorKey = '', array $expectedErrorParameters = [])
    {
        $validator = new Unique($data);
        $validator->run();
        self::assertSame($expectedIsValid, $validator->isValid());
        if (!empty($expectedErrorKey)) {
            self::assertSame($expectedErrorKey, $validator->getErrorKey());
            self::assertSame($expectedErrorParameters, $validator->getErrorParameters());
        }
    }

    /**
     * @return array
     */
    public function dpForTestValidator(): array
    {
        return [
            'unique value for name' => [
                'data'                    => $this->createData(['groups'], 'moderator'),
                'expectedIsValid'         => true
            ],
            'existing value for name ignored' => [
                'data'                    => $this->createData(['groups', 'name', 1], 'admin'),
                'expectedIsValid'         => true
            ],
            'existing value for name ignore different id' => [
                'data'                    => $this->createData(['groups', 'name', 2], 'admin'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.unique.valueExists',
                'expectedErrorParameters' => ['admin']
            ],
            'existing value for name' => [
                'data'                    => $this->createData(['groups'], 'admin'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.unique.valueExists',
                'expectedErrorParameters' => ['admin']
            ],
            'unique value for name inverted' => [
                'data'                    => $this->createData(['groups'], 'moderator', true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.unique.valueExists',
                'expectedErrorParameters' => ['moderator']
            ],
        ];
    }

    /**
     * Helper function for creating data object
     *
     * @param array $parameter
     * @param string|null $value
     * @param bool $invertResult
     * @return stdClass
     */
    private function createData(array $parameter = [], string $value = null, bool $invertResult = false): stdClass
    {
        $data = new stdClass();
        $data->field = 'name';
        $data->parameters = $parameter;
        $data->input = ['name' => $value];
        $data->invertResult = $invertResult;
        return $data;
    }
}
