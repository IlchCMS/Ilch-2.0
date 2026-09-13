<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Rule\Models;

use PHPUnit\Framework\TestCase;
use Modules\Rule\Models\Rule as RuleModel;

class RuleModelTest extends TestCase
{
    public function testDefaultValues()
    {
        $model = new RuleModel();

        self::assertSame(0, $model->getId());
        self::assertSame('', $model->getParagraph());
        self::assertSame('', $model->getTitle());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getPosition());
        self::assertSame(0, $model->getParentId());
        self::assertSame('', $model->getParentTitle());
        self::assertSame('', $model->getAccess());
    }

    public function testSetId()
    {
        $model = new RuleModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    public function testSetIdZero()
    {
        $model = new RuleModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    public function testSetParagraph()
    {
        $model = new RuleModel();
        $model->setParagraph('1.1');

        self::assertSame('1.1', $model->getParagraph());
    }

    public function testSetTitle()
    {
        $model = new RuleModel();
        $model->setTitle('Be Respectful');

        self::assertSame('Be Respectful', $model->getTitle());
    }

    public function testSetText()
    {
        $model = new RuleModel();
        $model->setText('<p>Treat others well.</p>');

        self::assertSame('<p>Treat others well.</p>', $model->getText());
    }

    public function testSetPosition()
    {
        $model = new RuleModel();
        $model->setPosition(3);

        self::assertSame(3, $model->getPosition());
    }

    public function testSetParentId()
    {
        $model = new RuleModel();
        $model->setParentId(2);

        self::assertSame(2, $model->getParentId());
    }

    public function testSetParentTitle()
    {
        $model = new RuleModel();
        $model->setParentTitle('General Rules');

        self::assertSame('General Rules', $model->getParentTitle());
    }

    public function testSetAccess()
    {
        $model = new RuleModel();
        $model->setAccess('1,2,3');

        self::assertSame('1,2,3', $model->getAccess());
    }

    public function testSetAccessAll()
    {
        $model = new RuleModel();
        $model->setAccess('all');

        self::assertSame('all', $model->getAccess());
    }

    public function testSettersReturnSelf()
    {
        $model = new RuleModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setParagraph('1'));
        self::assertSame($model, $model->setTitle('Test'));
        self::assertSame($model, $model->setText('Text'));
        self::assertSame($model, $model->setPosition(1));
        self::assertSame($model, $model->setParentId(0));
        self::assertSame($model, $model->setParentTitle('Parent'));
        self::assertSame($model, $model->setAccess('all'));
    }

    public function testChainedSetters()
    {
        $model = (new RuleModel())
            ->setId(3)
            ->setParagraph('1.2')
            ->setTitle('No Spam')
            ->setText('Spam is not allowed.')
            ->setPosition(2)
            ->setParentId(1)
            ->setParentTitle('General Rules')
            ->setAccess('1,3');

        self::assertSame(3, $model->getId());
        self::assertSame('1.2', $model->getParagraph());
        self::assertSame('No Spam', $model->getTitle());
        self::assertSame('Spam is not allowed.', $model->getText());
        self::assertSame(2, $model->getPosition());
        self::assertSame(1, $model->getParentId());
        self::assertSame('General Rules', $model->getParentTitle());
        self::assertSame('1,3', $model->getAccess());
    }

    public function testOverwriteValues()
    {
        $model = new RuleModel();
        $model->setId(1)
            ->setParagraph('1')
            ->setTitle('Old')
            ->setText('Old text')
            ->setPosition(1)
            ->setParentId(0)
            ->setAccess('all');

        $model->setId(2)
            ->setParagraph('2')
            ->setTitle('New')
            ->setText('New text')
            ->setPosition(2)
            ->setParentId(1)
            ->setAccess('1,2');

        self::assertSame(2, $model->getId());
        self::assertSame('2', $model->getParagraph());
        self::assertSame('New', $model->getTitle());
        self::assertSame('New text', $model->getText());
        self::assertSame(2, $model->getPosition());
        self::assertSame(1, $model->getParentId());
        self::assertSame('1,2', $model->getAccess());
    }

    public function testSetByArrayFull()
    {
        $model = new RuleModel();
        $model->setByArray([
            'id'           => 2,
            'paragraph'    => '1.1',
            'title'        => 'Be Respectful',
            'text'         => 'Treat others with respect.',
            'position'     => 1,
            'parent_id'    => 1,
            'parent_title' => 'General Rules',
            'access'       => '1,2',
            'access_all'   => 0,
        ]);

        self::assertSame(2, $model->getId());
        self::assertSame('1.1', $model->getParagraph());
        self::assertSame('Be Respectful', $model->getTitle());
        self::assertSame('Treat others with respect.', $model->getText());
        self::assertSame(1, $model->getPosition());
        self::assertSame(1, $model->getParentId());
        self::assertSame('General Rules', $model->getParentTitle());
        self::assertSame('1,2', $model->getAccess());
    }

    public function testSetByArrayAccessAllOverridesAccess()
    {
        $model = new RuleModel();
        $model->setByArray([
            'id'         => 1,
            'access'     => '1,2',
            'access_all' => 1,
        ]);

        self::assertSame('all', $model->getAccess());
    }

    public function testSetByArrayAccessAllZeroKeepsAccess()
    {
        $model = new RuleModel();
        $model->setByArray([
            'id'         => 2,
            'access'     => '1,2',
            'access_all' => 0,
        ]);

        self::assertSame('1,2', $model->getAccess());
    }

    public function testSetByArrayPartial()
    {
        $model = new RuleModel();
        $model->setByArray([
            'id'      => 5,
            'title'   => 'Test Rule',
        ]);

        self::assertSame(5, $model->getId());
        self::assertSame('Test Rule', $model->getTitle());
        self::assertSame('', $model->getParagraph());
        self::assertSame('', $model->getText());
        self::assertSame(0, $model->getPosition());
        self::assertSame(0, $model->getParentId());
        self::assertSame('', $model->getParentTitle());
        self::assertSame('', $model->getAccess());
    }

    public function testSetByArrayReturnsSelf()
    {
        $model = new RuleModel();
        $result = $model->setByArray(['id' => 1]);

        self::assertSame($model, $result);
    }

    public function testGetArrayWithId()
    {
        $model = (new RuleModel())
            ->setId(3)
            ->setParagraph('1.2')
            ->setTitle('No Spam')
            ->setText('Spam is not allowed.')
            ->setPosition(2)
            ->setParentId(1)
            ->setAccess('1,3');

        $array = $model->getArray();

        self::assertSame(3, $array['id']);
        self::assertSame('1.2', $array['paragraph']);
        self::assertSame('No Spam', $array['title']);
        self::assertSame('Spam is not allowed.', $array['text']);
        self::assertSame(2, $array['position']);
        self::assertSame(1, $array['parent_id']);
        self::assertSame(0, $array['access_all']);
    }

    public function testGetArrayWithoutId()
    {
        $model = (new RuleModel())
            ->setId(3)
            ->setParagraph('1.2')
            ->setTitle('No Spam')
            ->setText('Spam is not allowed.')
            ->setPosition(2)
            ->setParentId(1)
            ->setAccess('1,3');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertSame('1.2', $array['paragraph']);
        self::assertSame('No Spam', $array['title']);
    }

    public function testGetArrayAccessAll()
    {
        $model = (new RuleModel())
            ->setId(1)
            ->setParagraph('1')
            ->setTitle('General')
            ->setText('General rules.')
            ->setPosition(1)
            ->setParentId(0)
            ->setAccess('all');

        $array = $model->getArray();

        self::assertSame(1, $array['access_all']);
    }

    public function testGetArrayAccessNotAll()
    {
        $model = (new RuleModel())
            ->setId(2)
            ->setParagraph('1.1')
            ->setTitle('Be Respectful')
            ->setText('Treat others.')
            ->setPosition(1)
            ->setParentId(1)
            ->setAccess('1,2');

        $array = $model->getArray();

        self::assertSame(0, $array['access_all']);
    }
}
