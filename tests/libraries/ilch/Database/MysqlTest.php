<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Database;

use Ilch\Database\Mysql\Expression\Expression;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Config\Config as UserConfig;
use PHPUnit\Ilch\PhpunitDataset;

/**
 * Tests the MySQL database object.
 *
 * @copyright Ilch 2.0
 * @package ilch_phpunit
 */
class MysqlTest extends \PHPUnit\Ilch\DatabaseTestCase
{
    protected static array $fixtureFiles = [__DIR__ . '/../_files/mysql_database.yml'];

    protected $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFiles(static::$fixtureFiles);
    }

    protected static function getSchemaSQLQueries(): string
    {
        $configAdmin = new AdminConfig();
        $configUser = new UserConfig();
        return $configAdmin->getInstallSql() . $configUser->getInstallSql();
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching by a normal fieldname.
     */
    public function testSelectCellNormalField()
    {
        $result = $this->db->select('id')
            ->from('groups')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();
        self::assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if the db can be queried with a count of specific table rows when searching with a MySQL function.
     */
    public function testSelectCellWithCount()
    {
        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(['name' => 'Clanleader'])
            ->execute()
            ->fetchCell();
        self::assertEquals('2', $result, 'Wrong cell value was returned.');
    }

    /**
     * Tests if an update with an empty value can be done.
     */
    public function testUpdateWithEmptyValue()
    {
        $this->db->update('groups')
            ->values(['name' => ''])
            ->where(['id' => 2])
            ->execute();
        $result = $this->db->select('name')
            ->from('groups')
            ->where(['id' => 2])
            ->execute()
            ->fetchCell();
        self::assertEquals('', $result, 'The db entry has not being updated with an empty string.');
    }

    /**
     * Tests if an insertion with an empty value can be done.
     */
    public function testInsertWithEmptyValue()
    {
        $this->db->insert('groups')->values(['name' => ''])->execute();
        $result = $this->db->select('COUNT(*)')
            ->from('groups')
            ->where(['name' => ''])
            ->execute()
            ->fetchCell();
        self::assertEquals(1, $result, 'The db entry has not being inserted with an empty string.');
    }

    /**
     * Tests if ifForeignKeyConstraintExists works as expected.
     *
     * @return void
     */
    public function testIfForeignKeyConstraintExists()
    {
        self::assertTrue($this->db->ifForeignKeyConstraintExists('[prefix]_menu_items', 'FK_[prefix]_menu_items_[prefix]_menu'), 'Returning true for an existing foreign key constraint failed.');
        self::assertTrue($this->db->ifForeignKeyConstraintExists('menu_items', 'FK_menu_items_menu'), 'Returning true for an existing foreign key constraint failed. No prefixes.');
        self::assertFalse($this->db->ifForeignKeyConstraintExists('[prefix]_menu_items', 'wrongConstraintName'), 'Returning false for a clearly wrong contraint name failed.');
    }

    // ---- HAVING tests ----

    /**
     * Tests that a HAVING clause filters the groups of a grouped select.
     */
    public function testSelectWithGroupByAndHaving()
    {
        $select = $this->db->select(['name', 'COUNT(*) AS count'], 'groups');
        $select->group(['name']);
        $select->having(new Expression('COUNT(*) > 1'));

        $result = $select->execute();

        // The dataset has 5 rows and 4 distinct names; only 'Clanleader' has more than 1 row.
        $rows = $result->fetchRows();
        self::assertCount(1, $rows);
        self::assertSame('Clanleader', $rows[0]['name']);
        self::assertEquals('2', $rows[0]['count']);
    }

    /**
     * Tests that groups not matching the HAVING condition are excluded.
     */
    public function testSelectWithGroupByAndHavingExcludesGroups()
    {
        $select = $this->db->select(['name', 'COUNT(*) AS count'], 'groups');
        $select->group(['name']);
        $select->having(new Expression('COUNT(*) < 2'));

        $result = $select->execute();

        // The dataset has 5 rows and 4 distinct names: 'Clanleader' occurs twice,
        // the other 3 names exactly once -> exactly 3 groups match COUNT(*) < 2.
        self::assertSame(3, $result->getNumRows());
    }

    /**
     * Tests a combined WHERE, GROUP BY and HAVING clause.
     */
    public function testSelectWithWhereGroupByAndHaving()
    {
        $select = $this->db->select(['name', 'COUNT(*) AS count'], 'groups');
        $select->where(['name' => 'Clanleader']);
        $select->group(['name']);
        $select->having(new Expression('COUNT(*) > 1'));

        $result = $select->execute();

        $rows = $result->fetchRows();
        self::assertCount(1, $rows);
        self::assertSame('Clanleader', $rows[0]['name']);
        self::assertEquals('2', $rows[0]['count']);
    }

    /**
     * Tests a HAVING condition created with comp() (field/operator/value).
     */
    public function testSelectWithHavingViaComparison()
    {
        $select = $this->db->select(['name', 'COUNT(*) AS count'], 'groups');
        $select->group(['name']);
        $select->having($select->comp('COUNT(*) >', 1));

        $result = $select->execute();

        self::assertSame(1, $result->getNumRows());
    }

    /**
     * Tests that the found rows count respects the HAVING clause.
     */
    public function testGetFoundRowsWithHaving()
    {
        $select = $this->db->select(['name', 'COUNT(*) AS count'], 'groups');
        $select->group(['name']);
        $select->having(new Expression('COUNT(*) > 1'));
        $select->limit(1);
        $select->useFoundRows();

        $result = $select->execute();

        // Only the 'Clanleader' group matches the HAVING condition.
        self::assertSame(1, $result->getFoundRows());
        self::assertSame(1, $result->getNumRows());
    }

    // ---- Transaction tests ----

    /**
     * Tests a basic begin/commit cycle.
     */
    public function testBeginTransactionAndCommit()
    {
        $name = 'TransactionCommit';

        try {
            self::assertFalse($this->db->inTransaction());

            $this->db->beginTransaction();
            self::assertTrue($this->db->inTransaction());

            $this->db->insert('groups')->values(['name' => $name])->execute();

            $this->db->commit();
            self::assertFalse($this->db->inTransaction());

            self::assertSame($name, $this->getGroupByName($name), 'Row inserted inside a committed transaction must exist.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($name);
        }
    }

    /**
     * Tests a basic begin/rollback cycle.
     */
    public function testBeginTransactionAndRollback()
    {
        $name = 'TransactionRollback';

        try {
            $this->db->beginTransaction();
            $this->db->insert('groups')->values(['name' => $name])->execute();

            $this->db->rollback();
            self::assertFalse($this->db->inTransaction());

            self::assertNull($this->getGroupByName($name), 'Row inserted inside a rolled back transaction must not exist.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($name);
        }
    }

    public function testCommitWithoutActiveTransaction()
    {
        self::expectException(\Ilch\Database\Exception::class);
        self::expectExceptionMessage('Commit/rollback without active transaction');

        $this->db->commit();
    }

    public function testRollbackWithoutActiveTransaction()
    {
        self::expectException(\Ilch\Database\Exception::class);
        self::expectExceptionMessage('Commit/rollback without active transaction');

        $this->db->rollback();
    }

    /**
     * Tests that transaction() commits on success and returns the callback result.
     */
    public function testTransactionCallbackCommitsAndReturnsValue()
    {
        $name = 'TransactionCallbackCommit';

        try {
            $result = $this->db->transaction(function (Mysql $db) use ($name) {
                self::assertTrue($db->inTransaction());
                $db->insert('groups')->values(['name' => $name])->execute();
                return 'callback-result';
            });

            self::assertSame('callback-result', $result, 'transaction() must return the callback result.');
            self::assertFalse($this->db->inTransaction());
            self::assertSame($name, $this->getGroupByName($name));
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($name);
        }
    }

    /**
     * Tests that transaction() rolls back and rethrows when the callback fails.
     */
    public function testTransactionCallbackRollsBackOnException()
    {
        $name = 'TransactionCallbackRollback';

        try {
            try {
                $this->db->transaction(function (Mysql $db) use ($name) {
                    $db->insert('groups')->values(['name' => $name])->execute();
                    throw new \RuntimeException('failure inside transaction');
                });
                self::fail('transaction() must rethrow the exception thrown by the callback.');
            } catch (\RuntimeException $e) {
                self::assertSame('failure inside transaction', $e->getMessage());
            }

            self::assertFalse($this->db->inTransaction());
            self::assertNull($this->getGroupByName($name), 'Row inserted inside a failed transaction must not exist.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($name);
        }
    }

    /**
     * Tests nested transactions via savepoints: the inner commit only
     * releases the inner savepoint, the outer commit commits everything.
     */
    public function testNestedTransactionInnerCommit()
    {
        $name = 'NestedTransactionInnerCommit';

        try {
            $this->db->beginTransaction();
            $this->db->beginTransaction();
            self::assertTrue($this->db->inTransaction());

            $this->db->insert('groups')->values(['name' => $name])->execute();

            $this->db->commit(); // only the inner savepoint is released
            self::assertTrue($this->db->inTransaction());

            $this->db->commit(); // top-level transaction is committed
            self::assertFalse($this->db->inTransaction());

            self::assertSame($name, $this->getGroupByName($name), 'Row inserted inside a nested transaction must exist after the outer commit.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($name);
        }
    }

    /**
     * Tests that an inner rollback only undoes the inner changes while
     * changes made at the outer level are kept.
     */
    public function testNestedTransactionInnerRollbackKeepsOuterChanges()
    {
        $inner = 'NestedTransactionInner';
        $outer = 'NestedTransactionOuter';

        try {
            $this->db->beginTransaction();
            $this->db->insert('groups')->values(['name' => $outer])->execute();

            $this->db->beginTransaction();
            $this->db->insert('groups')->values(['name' => $inner])->execute();

            $this->db->rollback(); // rolls back only the inner changes
            self::assertTrue($this->db->inTransaction());

            $this->db->commit();
            self::assertFalse($this->db->inTransaction());

            self::assertSame($outer, $this->getGroupByName($outer), 'Outer changes must survive an inner rollback.');
            self::assertNull($this->getGroupByName($inner), 'Inner changes must be rolled back.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($inner);
            $this->deleteGroupByName($outer);
        }
    }

    /**
     * Tests nested transaction() callbacks.
     */
    public function testNestedTransactionCallbacksCommitBothLevels()
    {
        $inner = 'NestedCallbacksInner';
        $outer = 'NestedCallbacksOuter';

        try {
            $result = $this->db->transaction(function (Mysql $db) use ($outer, $inner) {
                $db->insert('groups')->values(['name' => $outer])->execute();

                return $db->transaction(function (Mysql $db) use ($inner) {
                    $db->insert('groups')->values(['name' => $inner])->execute();
                    return 'inner-result';
                });
            });

            self::assertSame('inner-result', $result, 'transaction() must return the result of the outer callback.');
            self::assertFalse($this->db->inTransaction());

            self::assertSame($outer, $this->getGroupByName($outer));
            self::assertSame($inner, $this->getGroupByName($inner));
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($outer);
            $this->deleteGroupByName($inner);
        }
    }

    /**
     * Tests that an uncaught inner failure rolls back the whole transaction
     * (the exception bubbles through the outer callback).
     */
    public function testNestedTransactionInnerFailureRollsBackEverything()
    {
        $inner = 'NestedCbFailInner';
        $outer = 'NestedCbFailOuter';

        try {
            try {
                $this->db->transaction(function (Mysql $db) use ($outer, $inner) {
                    $db->insert('groups')->values(['name' => $outer])->execute();

                    $db->transaction(function (Mysql $db) use ($inner) {
                        $db->insert('groups')->values(['name' => $inner])->execute();
                        throw new \RuntimeException('inner failure');
                    });
                    self::fail('inner exception must bubble up to the outer callback.');
                });
            } catch (\RuntimeException $e) {
                self::assertSame('inner failure', $e->getMessage());
            }

            self::assertFalse($this->db->inTransaction());
            self::assertNull($this->getGroupByName($outer), 'Outer changes must be rolled back when the outer callback fails.');
            self::assertNull($this->getGroupByName($inner), 'Inner changes must be rolled back.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($outer);
            $this->deleteGroupByName($inner);
        }
    }

    /**
     * Tests that an inner failure caught by the outer callback keeps the
     * outer changes, which are committed afterwards.
     */
    public function testNestedTransactionInnerFailureCaughtByOuterCommitsOuterChanges()
    {
        $inner = 'NestedCbCaughtInner';
        $outer = 'NestedCbCaughtOuter';

        try {
            $this->db->transaction(function (Mysql $db) use ($outer, $inner) {
                $db->insert('groups')->values(['name' => $outer])->execute();

                try {
                    $db->transaction(function (Mysql $db) use ($inner) {
                        $db->insert('groups')->values(['name' => $inner])->execute();
                        throw new \RuntimeException('inner failure');
                    });
                } catch (\RuntimeException $e) {
                    self::assertSame('inner failure', $e->getMessage());
                }
            });

            self::assertFalse($this->db->inTransaction());

            self::assertSame($outer, $this->getGroupByName($outer), 'Outer changes must survive an inner rollback.');
            self::assertNull($this->getGroupByName($inner), 'Inner changes must be rolled back.');
        } finally {
            $this->rollBackIfActive();
            $this->deleteGroupByName($outer);
            $this->deleteGroupByName($inner);
        }
    }

    /**
     * Tests that the error handling is switched to THROW_EXCEPTIONS inside
     * the transaction and restored afterwards.
     */
    public function testTransactionRestoresPreviousErrorHandling()
    {
        $previous = Mysql::$errorHandling;

        try {
            Mysql::$errorHandling = Mysql::IGNORE_ERRORS;

            $this->db->transaction(function (Mysql $db) {
                self::assertSame(Mysql::THROW_EXCEPTIONS, Mysql::$errorHandling, 'error handling must be switched to THROW_EXCEPTIONS inside the transaction.');
            });

            self::assertSame(Mysql::IGNORE_ERRORS, Mysql::$errorHandling, 'the previous error handling must be restored after the transaction.');
        } finally {
            Mysql::$errorHandling = $previous;
        }
    }

    /**
     * Tests that the previous error handling is restored even if the
     * transaction fails.
     */
    public function testTransactionRestoresErrorHandlingAfterException()
    {
        $previous = Mysql::$errorHandling;

        try {
            Mysql::$errorHandling = Mysql::IGNORE_ERRORS;

            try {
                $this->db->transaction(function (Mysql $db) {
                    throw new \RuntimeException('failure');
                });
            } catch (\RuntimeException $e) {
                self::assertSame('failure', $e->getMessage());
            }

            self::assertSame(Mysql::IGNORE_ERRORS, Mysql::$errorHandling, 'the previous error handling must be restored after a failed transaction.');
        } finally {
            Mysql::$errorHandling = $previous;
        }
    }

    // ---- helpers ----

    /**
     * Removes a group by its name. Used to keep the fixture state clean,
     * no matter how the test itself ended.
     *
     * @param string $name
     * @return void
     */
    private function deleteGroupByName(string $name): void
    {
        $this->db->delete('groups')->where(['name' => $name])->execute();
    }

    /**
     * @param string $name
     * @return string|null
     */
    private function getGroupByName(string $name): ?string
    {
        return $this->db->select('name')->from('groups')->where(['name' => $name])->execute()->fetchCell();
    }

    /**
     * Rolls back an open (possibly nested) transaction, if there is one, so
     * that no transaction state leaks into the next test.
     *
     * A failing rollback is ignored on purpose: cleanup must never hide the
     * original test failure.
     */
    private function rollBackIfActive(): void
    {
        while ($this->db->inTransaction()) {
            try {
                $this->db->rollback();
            } catch (\Throwable) {
                break;
            }
        }
    }
}
