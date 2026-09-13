<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Rule\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\Rule\Config\Config as ModuleConfig;
use Modules\User\Config\Config as UserConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\Rule\Mappers\Rule as RuleMapper;
use Modules\Rule\Models\Rule as RuleModel;

class RuleTest extends DatabaseTestCase
{
    /**
     * @var RuleMapper
     */
    protected RuleMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/mysql_database.yml');
        $this->out = new RuleMapper();
    }

    /**
     * Tests that checkDB() returns true when tables exist.
     */
    public function testCheckDB()
    {
        self::assertTrue($this->out->checkDB());
    }

    /**
     * Tests that getEntriesBy() returns all rules ordered by position ASC.
     */
    public function testGetEntriesBy()
    {
        $rules = $this->out->getEntriesBy();

        self::assertNotNull($rules);
        self::assertCount(4, $rules);
        self::assertInstanceOf(RuleModel::class, $rules[0]);
    }

    /**
     * Tests that getEntriesBy() returns correct fields for a top-level rule.
     */
    public function testGetEntriesByFieldsTopLevel()
    {
        $rule = $this->out->getRuleById(1);

        self::assertNotNull($rule);
        self::assertEquals(1, $rule->getId());
        self::assertEquals('1', $rule->getParagraph());
        self::assertEquals('General Rules', $rule->getTitle());
        self::assertEquals('These are the general rules.', $rule->getText());
        self::assertEquals(1, $rule->getPosition());
        self::assertEquals(0, $rule->getParentId());
        self::assertEquals('', $rule->getParentTitle());
        self::assertEquals('all', $rule->getAccess());
    }

    /**
     * Tests that getEntriesBy() returns correct fields for a child rule.
     */
    public function testGetEntriesByFieldsChild()
    {
        $rule = $this->out->getRuleById(2);

        self::assertNotNull($rule);
        self::assertEquals(2, $rule->getId());
        self::assertEquals('1.1', $rule->getParagraph());
        self::assertEquals('Be Respectful', $rule->getTitle());
        self::assertEquals('Treat others with respect.', $rule->getText());
        self::assertEquals(1, $rule->getPosition());
        self::assertEquals(1, $rule->getParentId());
        self::assertEquals('General Rules', $rule->getParentTitle());
        self::assertEquals('1,2', $rule->getAccess());
    }

    /**
     * Tests that getEntriesBy() returns null when no rules exist.
     */
    public function testGetEntriesByEmpty()
    {
        $this->out->delete(1);
        $this->out->delete(2);
        $this->out->delete(3);
        $this->out->delete(4);

        $rules = $this->out->getEntriesBy();

        self::assertNull($rules);
    }

    /**
     * Tests that getEntriesBy() with a where filter works.
     */
    public function testGetEntriesByWithWhere()
    {
        $rules = $this->out->getEntriesBy(['r.parent_id' => 1]);

        self::assertNotNull($rules);
        self::assertCount(2, $rules);
    }

    /**
     * Tests that getEntriesBy() respects custom orderBy.
     */
    public function testGetEntriesByCustomOrder()
    {
        $rules = $this->out->getEntriesBy([], ['r.id' => 'DESC']);

        self::assertNotNull($rules);
        self::assertCount(4, $rules);
        self::assertEquals(4, $rules[0]->getId());
        self::assertEquals(1, $rules[3]->getId());
    }

    /**
     * Tests that getRuleById() returns the correct rule.
     */
    public function testGetRuleById()
    {
        $rule = $this->out->getRuleById(3);

        self::assertNotNull($rule);
        self::assertEquals(3, $rule->getId());
        self::assertEquals('1.2', $rule->getParagraph());
        self::assertEquals('No Spam', $rule->getTitle());
        self::assertEquals('Spam is not allowed.', $rule->getText());
        self::assertEquals('General Rules', $rule->getParentTitle());
        self::assertEquals('1,3', $rule->getAccess());
    }

    /**
     * Tests that getRuleById() returns null for a non-existent id.
     */
    public function testGetRuleByIdNotFound()
    {
        $rule = $this->out->getRuleById(9999);

        self::assertNull($rule);
    }

    /**
     * Tests that getRules() with default group '3' returns accessible rules.
     * Rules 1 & 4 have access_all=1, Rule 3 has group 3 access, Rule 2 does not.
     */
    public function testGetRulesDefaultGroup()
    {
        $rules = $this->out->getRules();

        self::assertNotNull($rules);
        self::assertCount(3, $rules);

        $ids = array_map(fn($r) => $r->getId(), $rules);
        self::assertContains(1, $ids);
        self::assertContains(3, $ids);
        self::assertContains(4, $ids);
        self::assertNotContains(2, $ids);
    }

    /**
     * Tests that getRules() with multiple group ids returns accessible rules.
     */
    public function testGetRulesMultipleGroups()
    {
        $rules = $this->out->getRules([], '1,2');

        self::assertNotNull($rules);
        self::assertCount(4, $rules);
    }

    /**
     * Tests that getRules() with a string group id works.
     */
    public function testGetRulesStringGroupId()
    {
        $rules = $this->out->getRules([], '2');

        self::assertNotNull($rules);

        // Rule 1 (access_all=1), Rule 2 (group 2), Rule 4 (access_all=1)
        $ids = array_map(fn($r) => $r->getId(), $rules);
        self::assertContains(1, $ids);
        self::assertContains(2, $ids);
        self::assertContains(4, $ids);
        self::assertNotContains(3, $ids);
    }

    /**
     * Tests that getRulesItemsByParent() returns child rules for a parent.
     */
    public function testGetRulesItemsByParent()
    {
        $rules = $this->out->getRulesItemsByParent(1, '1,2,3');

        self::assertNotNull($rules);
        self::assertCount(2, $rules);

        $ids = array_map(fn($r) => $r->getId(), $rules);
        self::assertContains(2, $ids);
        self::assertContains(3, $ids);
    }

    /**
     * Tests that getRulesItemsByParent() filters by group access.
     */
    public function testGetRulesItemsByParentWithGroupFilter()
    {
        $rules = $this->out->getRulesItemsByParent(1);

        self::assertNotNull($rules);
        self::assertCount(1, $rules);
        self::assertEquals(3, $rules[0]->getId());
    }

    /**
     * Tests that getRulesItemsByParent() returns null when no children exist.
     */
    public function testGetRulesItemsByParentNotFound()
    {
        $rules = $this->out->getRulesItemsByParent(9999);

        self::assertNull($rules);
    }

    /**
     * Tests that sort() updates the position of a rule.
     */
    public function testSort()
    {
        $this->out->sort(3, 5);

        $rule = $this->out->getRuleById(3);
        self::assertNotNull($rule);
        self::assertEquals(5, $rule->getPosition());
    }

    /**
     * Tests that sort() does not affect other rules.
     */
    public function testSortDoesNotAffectOthers()
    {
        $this->out->sort(3, 5);

        $rule = $this->out->getRuleById(1);
        self::assertNotNull($rule);
        self::assertEquals(1, $rule->getPosition());
    }

    /**
     * Tests inserting a new top-level rule (parent_id=0).
     */
    public function testSaveInsertTopLevel()
    {
        $model = new RuleModel();
        $model->setId(0)
            ->setParagraph('3')
            ->setTitle('Safety Rules')
            ->setText('Stay safe.')
            ->setPosition(0)
            ->setParentId(0)
            ->setAccess('all');

        $result = $this->out->save($model);

        self::assertGreaterThan(4, $result);

        $rule = $this->out->getRuleById($result);
        self::assertNotNull($rule);
        self::assertEquals('Safety Rules', $rule->getTitle());
        self::assertEquals('3', $rule->getParagraph());
        self::assertEquals(0, $rule->getParentId());
        self::assertEquals('all', $rule->getAccess());

        // Position should be max existing (2) + 1 = 3
        self::assertEquals(3, $rule->getPosition());
    }

    /**
     * Tests inserting a new child rule (parent_id != 0).
     */
    public function testSaveInsertWithParent()
    {
        $model = new RuleModel();
        $model->setId(0)
            ->setParagraph('1.3')
            ->setTitle('No Harassment')
            ->setText('Harassment is forbidden.')
            ->setPosition(0)
            ->setParentId(1)
            ->setAccess('1,2');

        $result = $this->out->save($model);

        self::assertGreaterThan(4, $result);

        $rule = $this->out->getRuleById($result);
        self::assertNotNull($rule);
        self::assertEquals('No Harassment', $rule->getTitle());
        self::assertEquals(1, $rule->getParentId());

        // Position should be the parent's position (1)
        self::assertEquals(1, $rule->getPosition());
    }

    /**
     * Tests updating an existing rule via save().
     */
    public function testSaveUpdate()
    {
        $model = new RuleModel();
        $model->setId(1)
            ->setParagraph('1')
            ->setTitle('Updated General Rules')
            ->setText('Updated text.')
            ->setPosition(1)
            ->setParentId(0)
            ->setAccess('all');

        $result = $this->out->save($model);

        self::assertSame(1, $result);

        $rule = $this->out->getRuleById(1);
        self::assertNotNull($rule);
        self::assertEquals('Updated General Rules', $rule->getTitle());
        self::assertEquals('Updated text.', $rule->getText());
    }

    /**
     * Tests that update does not affect other rules.
     */
    public function testSaveUpdateDoesNotAffectOthers()
    {
        $model = new RuleModel();
        $model->setId(1)
            ->setParagraph('1')
            ->setTitle('Changed Title')
            ->setText('Changed text.')
            ->setPosition(1)
            ->setParentId(0)
            ->setAccess('all');

        $this->out->save($model);

        $other = $this->out->getRuleById(2);
        self::assertNotNull($other);
        self::assertEquals('Be Respectful', $other->getTitle());
        self::assertEquals('Treat others with respect.', $other->getText());
    }

    /**
     * Tests that saveAccess() with 'all' does not insert rules_access rows.
     */
    public function testSaveAccessAll()
    {
        $model = new RuleModel();
        $model->setId(0)
            ->setParagraph('5')
            ->setTitle('Open Rule')
            ->setText('Public rule.')
            ->setPosition(0)
            ->setParentId(0)
            ->setAccess('all');

        $newId = $this->out->save($model);

        // Access should be 'all' and no rules_access rows should exist for this rule
        $rule = $this->out->getRuleById($newId);
        self::assertNotNull($rule);
        self::assertEquals('all', $rule->getAccess());
    }

    /**
     * Tests that saveAccess() with specific groups inserts rows including admin (group 1).
     */
    public function testSaveAccessSpecificGroups()
    {
        $model = new RuleModel();
        $model->setId(0)
            ->setParagraph('6')
            ->setTitle('Members Only')
            ->setText('Members rule.')
            ->setPosition(0)
            ->setParentId(0)
            ->setAccess('2,3');

        $newId = $this->out->save($model);

        // Admin (group 1) should be auto-added, so access should include 1, 2, 3
        $rule = $this->out->getRuleById($newId);
        self::assertNotNull($rule);

        $accessGroups = explode(',', $rule->getAccess());
        sort($accessGroups);
        self::assertEquals(['1', '2', '3'], $accessGroups);
    }

    /**
     * Tests that saveAccess() replaces old access entries with new ones.
     */
    public function testSaveAccessReplacesOld()
    {
        // Rule 2 currently has access for groups 1,2
        $model = new RuleModel();
        $model->setId(2)
            ->setParagraph('1.1')
            ->setTitle('Be Respectful')
            ->setText('Treat others with respect.')
            ->setPosition(1)
            ->setParentId(1)
            ->setAccess('3');

        $this->out->save($model);

        // Now access should be groups 1 (auto-admin) and 3
        $rule = $this->out->getRuleById(2);
        self::assertNotNull($rule);

        $accessGroups = explode(',', $rule->getAccess());
        sort($accessGroups);
        self::assertEquals(['1', '3'], $accessGroups);
    }

    /**
     * Tests that delete() removes a rule.
     */
    public function testDelete()
    {
        $this->out->delete(2);

        self::assertNull($this->out->getRuleById(2));

        $rules = $this->out->getEntriesBy();
        self::assertCount(3, $rules);
    }

    /**
     * Tests that delete() cascades rules_access entries (foreign key ON DELETE CASCADE).
     */
    public function testDeleteCascadesAccess()
    {
        $this->out->delete(3);

        // Rule 3 had access for groups 1,3 - should be gone
        $rules = $this->out->getRules();

        $ids = array_map(fn($r) => $r->getId(), $rules);
        self::assertNotContains(3, $ids);
    }

    /**
     * Tests that delete() on a non-existent id does not throw.
     */
    public function testDeleteNotFound()
    {
        $this->out->delete(9999);

        $rules = $this->out->getEntriesBy();
        self::assertCount(4, $rules);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $userConfig = new UserConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $userConfig->getInstallSql() . $config->getInstallSql();
    }
}
