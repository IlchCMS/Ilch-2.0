<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Awards\Models;

use PHPUnit\Framework\TestCase;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Models\Recipient as RecipientModel;

class AwardsModelTest extends TestCase
{
    /**
     * Tests that setByArray() populates all fields from a complete array.
     */
    public function testSetByArrayComplete()
    {
        $model = new AwardsModel();
        $model->setByArray([
            'id'    => 5,
            'date'  => '2024-06-01',
            'rank'  => 3,
            'image' => 'gold.png',
            'event' => 'Summer Finals',
            'url'   => 'https://example.com/summer',
        ]);

        self::assertEquals(5, $model->getId());
        self::assertEquals('2024-06-01', $model->getDate());
        self::assertEquals(3, $model->getRank());
        self::assertEquals('gold.png', $model->getImage());
        self::assertEquals('Summer Finals', $model->getEvent());
        self::assertEquals('https://example.com/summer', $model->getURL());
    }

    /**
     * Tests that setByArray() skips empty/missing keys and preserves defaults.
     */
    public function testSetByArrayPartial()
    {
        $model = new AwardsModel();
        $model->setByArray([
            'date' => '2024-01-01',
            'event' => 'New Year',
        ]);

        // Fields that were provided
        self::assertEquals('2024-01-01', $model->getDate());
        self::assertEquals('New Year', $model->getEvent());

        // Fields that were NOT provided → defaults preserved
        self::assertEquals(0, $model->getId());
        self::assertEquals(1, $model->getRank());
        self::assertEquals('', $model->getImage());
        self::assertEquals('', $model->getURL());
    }

    /**
     * Tests that setByArray() skips a zero id (falsy in !empty check).
     */
    public function testSetByArrayZeroIdSkipped()
    {
        $model = new AwardsModel();
        $model->setByArray([
            'id'   => 0,
            'date' => '2024-01-01',
        ]);

        self::assertEquals(0, $model->getId());
    }

    /**
     * Tests that setByArray() is chainable (returns $this).
     */
    public function testSetByArrayReturnsSelf()
    {
        $model = new AwardsModel();
        $result = $model->setByArray(['date' => '2024-01-01']);

        self::assertSame($model, $result);
    }

    /**
     * Tests getArray() includes id when $withId is true (default).
     */
    public function testGetArrayWithId()
    {
        $model = new AwardsModel();
        $model->setId(7)
            ->setDate('2024-06-01')
            ->setRank(2)
            ->setImage('silver.png')
            ->setEvent('Tournament')
            ->setURL('https://example.com/t');

        $array = $model->getArray();

        self::assertEquals([
            'id'    => 7,
            'date'  => '2024-06-01',
            'rank'  => 2,
            'image' => 'silver.png',
            'event' => 'Tournament',
            'url'   => 'https://example.com/t',
        ], $array);
    }

    /**
     * Tests getArray() excludes id when $withId is false.
     */
    public function testGetArrayWithoutId()
    {
        $model = new AwardsModel();
        $model->setId(7)
            ->setDate('2024-06-01')
            ->setRank(2)
            ->setImage('silver.png')
            ->setEvent('Tournament')
            ->setURL('https://example.com/t');

        $array = $model->getArray(false);

        self::assertArrayNotHasKey('id', $array);
        self::assertEquals([
            'date'  => '2024-06-01',
            'rank'  => 2,
            'image' => 'silver.png',
            'event' => 'Tournament',
            'url'   => 'https://example.com/t',
        ], $array);
    }

    /**
     * Tests addRecipient() appends to the recipients collection.
     */
    public function testAddRecipient()
    {
        $model = new AwardsModel();

        $r1 = new RecipientModel();
        $r1->setAwardId(1)->setUtId(10)->setTyp(0);

        $r2 = new RecipientModel();
        $r2->setAwardId(1)->setUtId(11)->setTyp(1);

        $model->addRecipient($r1)->addRecipient($r2);

        self::assertCount(2, $model->getRecipients());
        self::assertSame($r1, $model->getRecipients()[0]);
        self::assertSame($r2, $model->getRecipients()[1]);
    }

    /**
     * Tests that setRecipients() replaces the entire collection.
     */
    public function testSetRecipientsReplaces()
    {
        $model = new AwardsModel();

        $r1 = new RecipientModel();
        $r1->setAwardId(1)->setUtId(10)->setTyp(0);
        $model->addRecipient($r1);

        $r2 = new RecipientModel();
        $r2->setAwardId(1)->setUtId(99)->setTyp(1);
        $model->setRecipients([$r2]);

        self::assertCount(1, $model->getRecipients());
        self::assertEquals(99, $model->getRecipients()[0]->getUtId());
    }
}
