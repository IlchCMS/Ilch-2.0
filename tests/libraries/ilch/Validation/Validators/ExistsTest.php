<?php
/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Admin\Mappers;

use Ilch\Validation\Validators\Exists;
use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use stdClass;

/**
 * Tests the Exists validator.
 *
 * @package ilch_phpunit
 */
class ExistsTest extends DatabaseTestCase
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
        $validator = new Exists($data);
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
            'existing table, column and value' => [
                'data'                    => $this->createData(['groups', 'id'], '1'),
                'expectedIsValid'         => true
            ],
            'existing table and column' => [
                'data'                    => $this->createData(['groups', 'id']),
                'expectedIsValid'         => true
            ],
            'existing table, other column' => [
                'data'                    => $this->createData(['groups', 'name']),
                'expectedIsValid'         => true
            ],
            'existing table, other column and value' => [
                'data'                    => $this->createData(['groups', 'name'], 'admin'),
                'expectedIsValid'         => true
            ],
            'existing table, two columns' => [
                'data'                    => $this->createData(['groups', 'name', 'id']),
                'expectedIsValid'         => true
            ],
            'existing table, other column and wrong value' => [
                'data'                    => $this->createData(['groups', 'name'], 'star'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.exists.resourceNotFound',
                'expectedErrorParameters' => []
            ],
            'existing table, column wrong value' => [
                'data'                    => $this->createData(['groups', 'id'], '3'),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.exists.resourceNotFound',
                'expectedErrorParameters' => []
            ],
            // Returns true for a not existing column?
            'existing table wrong column' => [
                'data'                    => $this->createData(['groups', 'abc']),
                'expectedIsValid'         => true
            ],
            //  MySQL Error: Unknown column 'abc' in 'field list'
//            'existing table wrong column with value' => [
//                'data'                    => $this->createData(['groups', 'abc'], '1'),
//                'expectedIsValid'         => false,
//                'expectedErrorKey'        => 'validation.errors.exists.resourceNotFound',
//                'expectedErrorParameters' => []
//            ],
            // Returns true for a not existing table?
            'wrong table' => [
                'data'                    => $this->createData(['abc']),
                'expectedIsValid'         => true
            ],
            'wrong table inverted' => [
                'data'                    => $this->createData(['abc'], null, true),
                'expectedIsValid'         => false,
                'expectedErrorKey'        => 'validation.errors.exists.resourceNotFound',
                'expectedErrorParameters' => []
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
        $data->field = 'fieldName';
        $data->parameters = $parameter;
        $data->input = ['fieldName' => $value];
        $data->invertResult = $invertResult;
        return $data;
    }
}
