<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\Away\Models;

use PHPUnit\Framework\TestCase;
use Modules\Away\Models\Away as AwayModel;

class AwayModelTest extends TestCase
{
    /**
     * Tests that setId() sets and returns the id.
     */
    public function testSetId()
    {
        $model = new AwayModel();
        $model->setId(5);

        self::assertSame(5, $model->getId());
    }

    /**
     * Tests that setId() casts to int.
     */
    public function testSetIdCastsToInt()
    {
        $model = new AwayModel();
        $model->setId('42');

        self::assertSame(42, $model->getId());
        self::assertIsInt($model->getId());
    }

    /**
     * Tests that setId(0) stores zero (falsy but valid).
     */
    public function testSetIdZero()
    {
        $model = new AwayModel();
        $model->setId(0);

        self::assertSame(0, $model->getId());
    }

    /**
     * Tests that setUserId() sets and returns the userId.
     */
    public function testSetUserId()
    {
        $model = new AwayModel();
        $model->setUserId(7);

        self::assertSame(7, $model->getUserId());
    }

    /**
     * Tests that setUserId() casts to int.
     */
    public function testSetUserIdCastsToInt()
    {
        $model = new AwayModel();
        $model->setUserId('42');

        self::assertSame(42, $model->getUserId());
        self::assertIsInt($model->getUserId());
    }

    /**
     * Tests that setReason() sets and returns the reason.
     */
    public function testSetReason()
    {
        $model = new AwayModel();
        $model->setReason('Vacation');

        self::assertSame('Vacation', $model->getReason());
    }

    /**
     * Tests that setReason() casts to string.
     */
    public function testSetReasonCastsToString()
    {
        $model = new AwayModel();
        $model->setReason(123);

        self::assertSame('123', $model->getReason());
        self::assertIsString($model->getReason());
    }

    /**
     * Tests that setStart() sets and returns the start.
     */
    public function testSetStart()
    {
        $model = new AwayModel();
        $model->setStart('2025-03-01');

        self::assertSame('2025-03-01', $model->getStart());
    }

    /**
     * Tests that setStart() casts to string.
     */
    public function testSetStartCastsToString()
    {
        $model = new AwayModel();
        $model->setStart(20250301);

        self::assertSame('20250301', $model->getStart());
        self::assertIsString($model->getStart());
    }

    /**
     * Tests that setEnd() sets and returns the end.
     */
    public function testSetEnd()
    {
        $model = new AwayModel();
        $model->setEnd('2025-03-15');

        self::assertSame('2025-03-15', $model->getEnd());
    }

    /**
     * Tests that setEnd() casts to string.
     */
    public function testSetEndCastsToString()
    {
        $model = new AwayModel();
        $model->setEnd(20250315);

        self::assertSame('20250315', $model->getEnd());
        self::assertIsString($model->getEnd());
    }

    /**
     * Tests that setText() sets and returns the text.
     */
    public function testSetText()
    {
        $model = new AwayModel();
        $model->setText('On holiday');

        self::assertSame('On holiday', $model->getText());
    }

    /**
     * Tests that setText() casts to string.
     */
    public function testSetTextCastsToString()
    {
        $model = new AwayModel();
        $model->setText(123);

        self::assertSame('123', $model->getText());
        self::assertIsString($model->getText());
    }

    /**
     * Tests that setStatus() sets and returns the status.
     */
    public function testSetStatus()
    {
        $model = new AwayModel();
        $model->setStatus(1);

        self::assertSame(1, $model->getStatus());
    }

    /**
     * Tests that setStatus() casts to int.
     */
    public function testSetStatusCastsToInt()
    {
        $model = new AwayModel();
        $model->setStatus('1');

        self::assertSame(1, $model->getStatus());
        self::assertIsInt($model->getStatus());
    }

    /**
     * Tests that setShow() sets and returns the show.
     */
    public function testSetShow()
    {
        $model = new AwayModel();
        $model->setShow(1);

        self::assertSame(1, $model->getShow());
    }

    /**
     * Tests that setShow() casts to int.
     */
    public function testSetShowCastsToInt()
    {
        $model = new AwayModel();
        $model->setShow('1');

        self::assertSame(1, $model->getShow());
        self::assertIsInt($model->getShow());
    }

    /**
     * Tests that setters are chainable (return $this).
     */
    public function testSettersReturnSelf()
    {
        $model = new AwayModel();

        self::assertSame($model, $model->setId(1));
        self::assertSame($model, $model->setUserId(1));
        self::assertSame($model, $model->setReason('Test'));
        self::assertSame($model, $model->setStart('2025-01-01'));
        self::assertSame($model, $model->setEnd('2025-01-02'));
        self::assertSame($model, $model->setText('Test text'));
        self::assertSame($model, $model->setStatus(1));
        self::assertSame($model, $model->setShow(1));
    }

    /**
     * Tests that chaining setters builds a complete model.
     */
    public function testChainedSetters()
    {
        $model = (new AwayModel())
            ->setId(1)
            ->setUserId(2)
            ->setReason('Vacation')
            ->setStart('2025-03-01')
            ->setEnd('2025-03-15')
            ->setText('On holiday')
            ->setStatus(2)
            ->setShow(1);

        self::assertSame(1, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('Vacation', $model->getReason());
        self::assertSame('2025-03-01', $model->getStart());
        self::assertSame('2025-03-15', $model->getEnd());
        self::assertSame('On holiday', $model->getText());
        self::assertSame(2, $model->getStatus());
        self::assertSame(1, $model->getShow());
    }

    /**
     * Tests the default values: 0 for id/userId, '' for strings, 2 for status, 0 for show.
     */
    public function testDefaultValues()
    {
        $model = new AwayModel();

        self::assertSame(0, $model->getId());
        self::assertSame(0, $model->getUserId());
        self::assertSame('', $model->getReason());
        self::assertSame('', $model->getStart());
        self::assertSame('', $model->getEnd());
        self::assertSame('', $model->getText());
        self::assertSame(2, $model->getStatus());
        self::assertSame(0, $model->getShow());
    }

    /**
     * Tests that overwriting a previously set value works.
     */
    public function testOverwriteValues()
    {
        $model = new AwayModel();
        $model->setId(1)
            ->setUserId(1)
            ->setReason('Old')
            ->setStart('2025-01-01')
            ->setEnd('2025-01-02')
            ->setText('Old text')
            ->setStatus(1)
            ->setShow(1);

        $model->setId(2)
            ->setUserId(2)
            ->setReason('New')
            ->setStart('2025-02-01')
            ->setEnd('2025-02-02')
            ->setText('New text')
            ->setStatus(0)
            ->setShow(0);

        self::assertSame(2, $model->getId());
        self::assertSame(2, $model->getUserId());
        self::assertSame('New', $model->getReason());
        self::assertSame('2025-02-01', $model->getStart());
        self::assertSame('2025-02-02', $model->getEnd());
        self::assertSame('New text', $model->getText());
        self::assertSame(0, $model->getStatus());
        self::assertSame(0, $model->getShow());
    }
}
