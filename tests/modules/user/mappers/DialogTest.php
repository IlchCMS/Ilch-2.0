<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Modules\User\Mappers;

use PHPUnit\Ilch\DatabaseTestCase;
use PHPUnit\Ilch\PhpunitDataset;
use Modules\User\Config\Config as ModuleConfig;
use Modules\Admin\Config\Config as AdminConfig;
use Modules\User\Mappers\Dialog as DialogMapper;
use Modules\User\Models\Dialog as DialogModel;

class DialogTest extends DatabaseTestCase
{
    /**
     * @var DialogMapper
     */
    protected DialogMapper $out;
    protected PhpunitDataset $phpunitDataset;

    public function setUp(): void
    {
        parent::setUp();
        $this->phpunitDataset = new PhpunitDataset($this->db);
        $this->phpunitDataset->loadFromFile(__DIR__ . '/../_files/users_dialog.yml');
        $this->out = new DialogMapper();
    }

    /**
     * Tests that getDialog() returns null for a user without dialogs.
     */
    public function testGetDialogReturnsNullWithoutDialogs()
    {
        self::assertNull($this->out->getDialog(4, false));
        self::assertNull($this->out->getDialog(4));
    }

    /**
     * Tests that getDialog() returns only dialogs that are not hidden for the
     * user when $showHidden is false.
     *
     * User 1 has dialog 1 (not hidden) and dialog 2 (hidden). Only dialog 1
     * must be returned.
     */
    public function testGetDialogWithoutHidden()
    {
        $dialogs = $this->out->getDialog(1, false);

        self::assertNotNull($dialogs);
        self::assertCount(1, $dialogs);

        $dialog = $dialogs[0];
        self::assertEquals(1, $dialog->getCId());
        self::assertEquals(2, $dialog->getId());
        self::assertEquals('Testuser2', $dialog->getName());
        self::assertFalse($dialog->getHidden());
        self::assertFalse($dialog->getRead());
        self::assertEquals('Hello from user 2', $dialog->getText());
        self::assertEquals('2014-01-01 13:00:00', $dialog->getTime());
        self::assertEquals('static/img/noavatar.jpg', $dialog->getAvatar());
    }

    /**
     * Tests that getDialog() returns only dialogs that are hidden (but not
     * permanently hidden) for the user when $showHidden is true.
     *
     * Documents current behavior: the SQL condition is `h.permanent = 0`,
     * which evaluates to NULL for dialogs that are not hidden at all.
     * Therefore normal dialogs are NOT returned either. If the intent of
     * $showHidden = true is "show all dialogs including hidden ones", the
     * condition must be `(h.c_id IS NULL OR h.permanent = 0)`.
     */
    public function testGetDialogWithHidden()
    {
        $dialogs = $this->out->getDialog(1);

        self::assertNotNull($dialogs);

        // Only the hidden dialog 2 is returned, the non-hidden dialog 1 is not.
        self::assertCount(1, $dialogs);

        $dialog = $dialogs[0];
        self::assertEquals(2, $dialog->getCId());
        self::assertEquals(3, $dialog->getId());
        self::assertEquals('Testuser3', $dialog->getName());
        self::assertTrue($dialog->getHidden());
        self::assertFalse($dialog->getRead());
        self::assertEquals('Hi from user 3', $dialog->getText());
        self::assertEquals('2014-01-02 13:00:00', $dialog->getTime());
    }

    /**
     * Tests that getDialog() returns dialogs ordered by the dialog time descending.
     */
    public function testGetDialogOrder()
    {
        $dialogs = $this->out->getDialog(3, false);

        self::assertNotNull($dialogs);
        self::assertCount(2, $dialogs);

        // Dialog 3 (2014-01-03) is more recent than dialog 2 (2014-01-02).
        self::assertEquals(3, $dialogs[0]->getCId());
        self::assertEquals(2, $dialogs[1]->getCId());
    }

    /**
     * Tests that getDialog() marks a dialog as read when the other user has no
     * unread replies.
     */
    public function testGetDialogReadFlag()
    {
        $dialogs = $this->out->getDialog(3, false);

        // Dialog 3: reply of user 2 (cr 5) is read. Dialog 2: reply of user 1 (cr 3) is read.
        self::assertTrue($dialogs[0]->getRead());
        self::assertTrue($dialogs[1]->getRead());
    }

    /**
     * Tests that getDialogByCId() returns the user data of the given user.
     */
    public function testGetDialogByCId()
    {
        $dialog = $this->out->getDialogByCId(2);

        self::assertNotNull($dialog);
        self::assertEquals(2, $dialog->getId());
        self::assertEquals('Testuser2', $dialog->getName());
        self::assertEquals('static/img/noavatar.jpg', $dialog->getAvatar());
    }

    /**
     * Tests that getDialogByCId() returns null for a non-existent user.
     */
    public function testGetDialogByCIdNotFound()
    {
        self::assertNull($this->out->getDialogByCId(9999));
    }

    /**
     * Tests that getLastOneDialog() returns the latest reply of a dialog.
     */
    public function testGetLastOneDialog()
    {
        $dialog = $this->out->getLastOneDialog(1);

        self::assertNotNull($dialog);
        self::assertEquals('Hello from user 2', $dialog->getText());
        self::assertEquals('2014-01-01 13:00:00', $dialog->getTime());
    }

    /**
     * Tests that getLastOneDialog() returns null for a dialog without replies.
     */
    public function testGetLastOneDialogEmpty()
    {
        self::assertNull($this->out->getLastOneDialog(9999));
    }

    /**
     * Tests that getReadLastOneDialog() returns the newest unread reply.
     */
    public function testGetReadLastOneDialog()
    {
        $dialog = $this->out->getReadLastOneDialog(1);

        self::assertNotNull($dialog);
        self::assertEquals(2, $dialog->getCrId());
        self::assertEquals(2, $dialog->getUserOne());
        self::assertEquals('Hello from user 2', $dialog->getText());
        self::assertEquals('2014-01-01 13:00:00', $dialog->getTime());
        self::assertFalse($dialog->getRead());
    }

    /**
     * Tests that getReadLastOneDialog() ignores replies of the given user.
     *
     * For user 2 the unread reply of user 1 (cr 1) must be returned instead
     * of the unread reply of user 2 itself.
     */
    public function testGetReadLastOneDialogIgnoresOwnReplies()
    {
        $dialog = $this->out->getReadLastOneDialog(1, 2);

        self::assertNotNull($dialog);
        self::assertEquals(1, $dialog->getCrId());
        self::assertEquals(1, $dialog->getUserOne());
        self::assertEquals('Hello from user 1', $dialog->getText());
        self::assertEquals('2014-01-01 12:00:00', $dialog->getTime());
    }

    /**
     * Tests that getReadLastOneDialog() returns null when the only unread
     * reply belongs to the given user itself.
     */
    public function testGetReadLastOneDialogAllOwnReplies()
    {
        self::assertNull($this->out->getReadLastOneDialog(2, 3));
    }

    /**
     * Tests that getReadLastOneDialog() returns null when there are no unread replies.
     */
    public function testGetReadLastOneDialogNoneUnread()
    {
        // Mark all replies of the other user in dialog 2 as read.
        $this->out->markAllAsRead(2, 1);

        self::assertNull($this->out->getReadLastOneDialog(2));
    }

    /**
     * Tests that getCountOfUnreadMessagesByUser() counts only unread replies
     * of the other users.
     */
    public function testGetCountOfUnreadMessagesByUser()
    {
        // user 1: cr 2 (dialog 1) and cr 4 (dialog 2) are unread
        self::assertSame(2, $this->out->getCountOfUnreadMessagesByUser(1));
        // user 2: cr 1 (dialog 1) and cr 6 (dialog 3) are unread
        self::assertSame(2, $this->out->getCountOfUnreadMessagesByUser(2));
        // user 3: all replies of the other users are already read
        self::assertSame(0, $this->out->getCountOfUnreadMessagesByUser(3));
        // user 4: no dialogs at all
        self::assertSame(0, $this->out->getCountOfUnreadMessagesByUser(4));
    }

    /**
     * Tests that getDialogMessage() returns the replies in ascending order.
     */
    public function testGetDialogMessage()
    {
        $messages = $this->out->getDialogMessage(1);

        self::assertNotNull($messages);
        self::assertCount(2, $messages);

        $first = $messages[0];
        self::assertEquals(1, $first->getCId());
        self::assertEquals(1, $first->getCrId());
        self::assertEquals(1, $first->getId());
        self::assertEquals('Testuser1', $first->getName());
        self::assertEquals('Hello from user 1', $first->getText());
        self::assertEquals('2014-01-01 12:00:00', $first->getTime());

        $second = $messages[1];
        self::assertEquals(1, $second->getCId());
        self::assertEquals(2, $second->getCrId());
        self::assertEquals(2, $second->getId());
        self::assertEquals('Testuser2', $second->getName());
        self::assertEquals('Hello from user 2', $second->getText());
        self::assertEquals('2014-01-01 13:00:00', $second->getTime());
    }

    /**
     * Tests that getDialogMessage() returns null for a dialog without replies.
     */
    public function testGetDialogMessageEmpty()
    {
        self::assertNull($this->out->getDialogMessage(9999));
    }

    /**
     * Tests that getDialogMessage() only returns the last 20 replies.
     */
    public function testGetDialogMessageLimitedTo20()
    {
        // Add 19 further replies to dialog 1, so there are 25 replies in total.
        for ($i = 1; $i <= 19; $i++) {
            $this->db->insert('users_dialog_reply')
                ->values([
                    'reply' => 'Extra reply ' . $i,
                    'user_id_fk' => 1,
                    'c_id_fk' => 1,
                    'time' => '2014-02-' . str_pad((string) $i, 2, '0', STR_PAD_LEFT) . ' 12:00:00',
                    'read' => 1
                ])
                ->execute();
        }

        $messages = $this->out->getDialogMessage(1);

        self::assertNotNull($messages);
        self::assertCount(20, $messages);

        // Dialog 1 has 21 replies: cr 1, 2 (fixture) and cr 7..25 (inserted).
        // The 20 newest by cr_id therefore start at cr 2 — cr 1 is truncated.
        self::assertEquals(2, $messages[0]->getCrId());
        self::assertEquals(25, $messages[19]->getCrId());
    }

    /**
     * Tests that isMessageOfUser() returns true for the author of a message.
     */
    public function testIsMessageOfUser()
    {
        self::assertTrue($this->out->isMessageOfUser(2, 2));
    }

    /**
     * Tests that isMessageOfUser() returns false for non-authors.
     */
    public function testIsMessageOfUserFalse()
    {
        self::assertFalse($this->out->isMessageOfUser(2, 1));
        self::assertFalse($this->out->isMessageOfUser(9999, 1));
    }

    /**
     * Tests that deleteMessageOfUser() deletes the message and updates the
     * dialog time to the time of the newest remaining reply.
     */
    public function testDeleteMessageOfUser()
    {
        $this->out->deleteMessageOfUser(2, 2);

        $messages = $this->out->getDialogMessage(1);
        self::assertNotNull($messages);
        self::assertCount(1, $messages);
        self::assertEquals(1, $messages[0]->getCrId());

        // The dialog time is updated to the time of the remaining reply.
        $time = $this->db->select('time')
            ->from('users_dialog')
            ->where(['c_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame('2014-01-01 12:00:00', $time);
    }

    /**
     * Tests that deleteMessageOfUser() does not update the dialog time when
     * the last remaining reply of the dialog was removed.
     */
    public function testDeleteMessageOfUserKeepsTimeWithoutRemainingReplies()
    {
        $this->out->deleteMessageOfUser(2, 2);
        $this->out->deleteMessageOfUser(1, 1);

        self::assertNull($this->out->getDialogMessage(1));

        // The time stays at the time of the previously last remaining reply.
        $time = $this->db->select('time')
            ->from('users_dialog')
            ->where(['c_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame('2014-01-01 12:00:00', $time);
    }

    /**
     * Tests that deleteMessageOfUser() leaves the dialog time untouched when
     * a dialog with exactly one reply loses that reply.
     */
    public function testDeleteMessageOfUserLastMessage()
    {
        // Create a dialog with exactly one reply.
        $this->db->insert('users_dialog')
            ->values(['user_one' => 4, 'user_two' => 2, 'time' => '2014-01-05 12:00:00'])
            ->execute();
        $cId = (int) $this->db->getLastInsertId();

        $this->db->insert('users_dialog_reply')
            ->values([
                'reply' => 'Only message',
                'user_id_fk' => 4,
                'c_id_fk' => $cId,
                'time' => '2014-01-05 12:00:00',
                'read' => 0
            ])
            ->execute();
        $crId = (int) $this->db->getLastInsertId();

        $this->out->deleteMessageOfUser($crId, 4);

        self::assertNull($this->out->getDialogMessage($cId));

        $time = $this->db->select('time')
            ->from('users_dialog')
            ->where(['c_id' => $cId])
            ->execute()
            ->fetchCell();
        self::assertSame('2014-01-05 12:00:00', $time);
    }

    /**
     * Tests that deleteMessageOfUser() does nothing for a non-existent message.
     */
    public function testDeleteMessageOfUserNotFound()
    {
        $this->out->deleteMessageOfUser(9999, 1);

        $messages = $this->out->getDialogMessage(1);
        self::assertNotNull($messages);
        self::assertCount(2, $messages);
    }

    /**
     * Tests that permanentlyHideOrDeleteDialog() hides the dialog permanently
     * when the other user still exists, deletes the own messages, marks the
     * other user's messages as read and returns 0.
     */
    public function testPermanentlyHideOrDeleteDialogHides()
    {
        $result = $this->out->permanentlyHideOrDeleteDialog(1, 1);

        self::assertSame(0, $result);

        // The dialog still exists.
        $dialog = $this->out->getDialogCheckByCId(1);
        self::assertNotNull($dialog);
        self::assertEquals(1, $dialog->getUserOne());
        self::assertEquals(2, $dialog->getUserTwo());

        // The own messages are deleted, the other message is left but marked as read.
        self::assertFalse($this->out->isMessageOfUser(1, 1));
        self::assertTrue($this->out->isMessageOfUser(2, 2));
        $read = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => 2])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $read);

        // The dialog is permanently hidden for user 1.
        $permanent = $this->db->select('permanent')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 1, 'user_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $permanent);

        // Neither the normal view nor the hidden view shows dialog 1 for user 1
        // (a permanently hidden dialog is not counted in the hidden view either).
        self::assertNull($this->out->getDialog(1, false));
        $dialogs = $this->out->getDialog(1);
        self::assertNotNull($dialogs);
        self::assertCount(1, $dialogs);
        self::assertEquals(2, $dialogs[0]->getCId());

        // The other user still sees the dialog.
        $dialogs = $this->out->getDialog(2, false);
        self::assertNotNull($dialogs);
        self::assertCount(2, $dialogs);
        self::assertEquals(3, $dialogs[0]->getCId());
        self::assertEquals(1, $dialogs[1]->getCId());
    }

    /**
     * Tests that permanentlyHideOrDeleteDialog() deletes the dialog completely
     * when the other user has already permanently hidden it.
     */
    public function testPermanentlyHideOrDeleteDialogDeletesWhenOtherUserDeletedIt()
    {
        // User 2 has already permanently hidden the dialog.
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 2, 'permanent' => 1])
            ->execute();

        $result = $this->out->permanentlyHideOrDeleteDialog(1, 1);

        self::assertSame(1, $result);

        // The dialog is gone and all hidden entries of the dialog are removed.
        self::assertNull($this->out->getDialogCheckByCId(1));
        $hiddenCount = $this->db->select('COUNT(*)')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(0, (int) $hiddenCount);

        // User 2 still has dialog 3.
        $dialogs = $this->out->getDialog(2, false);
        self::assertNotNull($dialogs);
        self::assertCount(1, $dialogs);
        self::assertEquals(3, $dialogs[0]->getCId());
    }

    /**
     * Tests (documents current behavior) that the replies of the other user
     * are left behind as orphaned rows when a dialog is deleted completely.
     *
     * There is no foreign key from users_dialog_reply to users_dialog, so the
     * replies of user 2 in dialog 1 survive the deletion of the dialog row.
     */
    public function testPermanentlyHideOrDeleteDialogLeavesOrphanedReplies()
    {
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 2, 'permanent' => 1])
            ->execute();

        $this->out->permanentlyHideOrDeleteDialog(1, 1);

        $orphanedReplies = $this->db->select('COUNT(*)')
            ->from('users_dialog_reply')
            ->where(['c_id_fk' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $orphanedReplies);
    }

    /**
     * Tests that permanentlyHideOrDeleteDialog() returns 0 for a non-existent
     * dialog.
     *
     * Note: this documents two side effects in the current implementation:
     * - an orphaned entry is created in users_dialog_hidden
     * - deleteDialog() triggers a PHP 8 warning because the fetched (empty)
     *   row is accessed as an array without a guard
     */
    public function testPermanentlyHideOrDeleteDialogNotFound()
    {
        $result = $this->out->permanentlyHideOrDeleteDialog(9999, 1);

        self::assertSame(0, $result);

        // Orphaned hidden entry for the non-existent dialog.
        $permanent = $this->db->select('permanent')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 9999, 'user_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $permanent);
    }

    /**
     * Tests that hideDialog() adds the dialog to the hidden dialogs of the user.
     */
    public function testHideDialog()
    {
        $this->out->hideDialog(1, 2);

        self::assertTrue($this->out->hasHiddenDialog(2));

        $permanent = $this->db->select('permanent')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 1, 'user_id' => 2])
            ->execute()
            ->fetchCell();
        self::assertSame(0, (int) $permanent);
    }

    /**
     * Tests that hideDialog() does not create duplicate entries.
     */
    public function testHideDialogDoesNotDuplicate()
    {
        $this->out->hideDialog(1, 2);
        $this->out->hideDialog(1, 2);

        $count = $this->db->select('COUNT(*)')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 1, 'user_id' => 2])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $count);
    }

    /**
     * Tests that hideDialog() does not reset the permanent flag of an existing entry.
     */
    public function testHideDialogDoesNotResetPermanent()
    {
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 3, 'permanent' => 1])
            ->execute();

        $this->out->hideDialog(1, 3);

        $permanent = $this->db->select('permanent')
            ->from('users_dialog_hidden')
            ->where(['c_id' => 1, 'user_id' => 3])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $permanent);
    }

    /**
     * Tests that hasHiddenDialog() returns true only if the user has
     * (non-permanently) hidden dialogs by default.
     */
    public function testHasHiddenDialog()
    {
        // User 1 has dialog 2 hidden (permanent = 0).
        self::assertTrue($this->out->hasHiddenDialog(1));
        self::assertFalse($this->out->hasHiddenDialog(2));
        self::assertFalse($this->out->hasHiddenDialog(4));

        // Permanently hidden dialogs are not counted by default.
        self::assertFalse($this->out->hasHiddenDialog(1, true));
    }

    /**
     * Tests that hasHiddenDialog() counts permanently hidden dialogs when requested.
     */
    public function testHasHiddenDialogIncludePermanent()
    {
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 1, 'permanent' => 1])
            ->execute();

        self::assertTrue($this->out->hasHiddenDialog(1, true));
        self::assertTrue($this->out->hasHiddenDialog(1));
        self::assertTrue($this->out->hasHiddenDialog(1, false));
    }

    /**
     * Tests that unhideDialog() removes the hidden entry and returns the
     * number of affected rows.
     */
    public function testUnhideDialog()
    {
        $affected = $this->out->unhideDialog(2, 1);

        self::assertSame(1, $affected);
        self::assertFalse($this->out->hasHiddenDialog(1));
    }

    /**
     * Tests that unhideDialog() returns 0 if the dialog was not hidden.
     */
    public function testUnhideDialogNotFound()
    {
        self::assertSame(0, $this->out->unhideDialog(1, 2));
    }

    /**
     * Tests that unhideAllDialogsByUser() removes all hidden entries of a user.
     */
    public function testUnhideAllDialogsByUser()
    {
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 1, 'permanent' => 0])
            ->execute();

        // User 1 now has two hidden entries: dialog 1 and dialog 2.
        $affected = $this->out->unhideAllDialogsByUser(1);

        self::assertSame(2, $affected);
        self::assertFalse($this->out->hasHiddenDialog(1));
    }

    /**
     * Tests that unhideAllDialogsByUser() returns 0 for a user without hidden dialogs.
     */
    public function testUnhideAllDialogsByUserEmpty()
    {
        self::assertSame(0, $this->out->unhideAllDialogsByUser(4));
    }

    /**
     * Tests that unhideDialogById() removes the hidden entries of all users
     * for a dialog, without touching other dialogs.
     */
    public function testUnhideDialogById()
    {
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 1, 'permanent' => 0])
            ->execute();
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 2, 'permanent' => 0])
            ->execute();

        $affected = $this->out->unhideDialogById(1);

        self::assertSame(2, $affected);

        // The hidden entry of dialog 2 for user 1 is not affected.
        self::assertTrue($this->out->hasHiddenDialog(1));
    }

    /**
     * Tests that getDialogCheckByCId() returns the users of a dialog.
     */
    public function testGetDialogCheckByCId()
    {
        $dialog = $this->out->getDialogCheckByCId(1);

        self::assertNotNull($dialog);
        self::assertEquals(1, $dialog->getUserOne());
        self::assertEquals(2, $dialog->getUserTwo());
    }

    /**
     * Tests that getDialogCheckByCId() returns null for a non-existent dialog.
     */
    public function testGetDialogCheckByCIdNotFound()
    {
        self::assertNull($this->out->getDialogCheckByCId(9999));
    }

    /**
     * Tests that getDialogCheck() finds a dialog by both users.
     */
    public function testGetDialogCheck()
    {
        $dialog = $this->out->getDialogCheck(1, 2);

        self::assertNotNull($dialog);
        self::assertEquals(1, $dialog->getCId());
        self::assertEquals(1, $dialog->getUserOne());
        self::assertEquals(2, $dialog->getUserTwo());
    }

    /**
     * Tests that getDialogCheck() finds the dialog in both argument orders.
     */
    public function testGetDialogCheckReversed()
    {
        $dialog = $this->out->getDialogCheck(2, 1);

        self::assertNotNull($dialog);
        self::assertEquals(1, $dialog->getCId());
        self::assertEquals(1, $dialog->getUserOne());
        self::assertEquals(2, $dialog->getUserTwo());
    }

    /**
     * Tests that getDialogCheck() returns null if no dialog exists.
     */
    public function testGetDialogCheckNotFound()
    {
        self::assertNull($this->out->getDialogCheck(1, 4));
        self::assertNull($this->out->getDialogCheck(4, 9999));
    }

    /**
     * Tests that getDialogId() returns the most recent dialog of a user.
     */
    public function testGetDialogId()
    {
        // Dialog 2 has the newest c_id of the dialogs of user 1.
        $dialog = $this->out->getDialogId(1);
        self::assertNotNull($dialog);
        self::assertEquals(2, $dialog->getCId());

        // Dialog 3 has the newest c_id of the dialogs of user 2.
        $dialog = $this->out->getDialogId(2);
        self::assertNotNull($dialog);
        self::assertEquals(3, $dialog->getCId());
    }

    /**
     * Tests that getDialogId() returns null for a user without dialogs.
     */
    public function testGetDialogIdNotFound()
    {
        self::assertNull($this->out->getDialogId(4));
    }

    /**
     * Tests that save() creates a new conversation when both users are set.
     */
    public function testSaveNewConversation()
    {
        $model = new DialogModel();
        $model->setUserOne(2)
            ->setUserTwo(4)
            ->setTime('2014-01-05 12:12:12');

        $cId = $this->out->save($model);

        self::assertGreaterThan(3, $cId);
        self::assertEquals($cId, $model->getCId());

        $dialog = $this->out->getDialogCheckByCId($cId);
        self::assertNotNull($dialog);
        self::assertEquals(2, $dialog->getUserOne());
        self::assertEquals(4, $dialog->getUserTwo());

        $time = $this->db->select('time')
            ->from('users_dialog')
            ->where(['c_id' => $cId])
            ->execute()
            ->fetchCell();
        self::assertSame('2014-01-05 12:12:12', $time);
    }

    /**
     * Tests that save() adds a reply to an existing conversation when only
     * the author and the conversation id are set.
     */
    public function testSaveReply()
    {
        $model = new DialogModel();
        $model->setId(2)
            ->setCId(1)
            ->setText('New reply from user 2')
            ->setTime('2014-01-04 12:12:12')
            ->setRead(false);

        $cId = $this->out->save($model);

        self::assertSame(1, $cId);

        $messages = $this->out->getDialogMessage(1);
        self::assertNotNull($messages);
        self::assertCount(3, $messages);

        $last = $messages[2];
        self::assertEquals(1, $last->getCId());
        self::assertEquals(2, $last->getId());
        self::assertEquals('New reply from user 2', $last->getText());
        self::assertEquals('2014-01-04 12:12:12', $last->getTime());

        // The reply is stored as unread.
        $read = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => $last->getCrId()])
            ->execute()
            ->fetchCell();
        self::assertSame(0, (int) $read);

        // The conversation time is updated to the time of the new reply.
        $time = $this->db->select('time')
            ->from('users_dialog')
            ->where(['c_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame('2014-01-04 12:12:12', $time);

        // The new reply counts as unread for the other user.
        self::assertSame(3, $this->out->getCountOfUnreadMessagesByUser(1));
    }

    /**
     * Tests that markAllAsRead() marks only the replies of the other user as read.
     */
    public function testMarkAllAsRead()
    {
        $this->out->markAllAsRead(1, 1);

        // Reply 1 belongs to user 1 itself and must stay unread.
        $read1 = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(0, (int) $read1);

        // Reply 2 belongs to user 2 and must be read now.
        $read2 = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => 2])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $read2);

        // For user 1 only the unread reply of dialog 2 is left.
        self::assertSame(1, $this->out->getCountOfUnreadMessagesByUser(1));
    }

    /**
     * Tests that markAllAsRead() does not mark the own replies of the given user.
     */
    public function testMarkAllAsReadKeepsOwnRepliesUnread()
    {
        $this->out->markAllAsRead(3, 3);

        // Reply 6 belongs to user 3 itself and must stay unread.
        $read6 = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => 6])
            ->execute()
            ->fetchCell();
        self::assertSame(0, (int) $read6);

        // Reply 5 of user 2 in dialog 3 was already read and stays read.
        $read5 = $this->db->select('read')
            ->from('users_dialog_reply')
            ->where(['cr_id' => 5])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $read5);
    }

    /**
     * Tests that deleteAllOfUser() removes the messages and hidden entries of
     * a user and leaves dialogs alone while the other users still exist.
     */
    public function testDeleteAllOfUser()
    {
        $this->out->deleteAllOfUser(1);

        // All messages of user 1 are gone.
        self::assertFalse($this->out->isMessageOfUser(1, 1));
        self::assertFalse($this->out->isMessageOfUser(3, 1));

        // The dialogs still exist because the other users still exist. The
        // formerly hidden dialog 2 is visible again because the hidden
        // entry of user 1 was removed.
        $dialogs = $this->out->getDialog(1, false);
        self::assertNotNull($dialogs);
        self::assertCount(2, $dialogs);
        self::assertEquals(2, $dialogs[0]->getCId());
        self::assertEquals(1, $dialogs[1]->getCId());

        self::assertFalse($this->out->hasHiddenDialog(1));
    }

    /**
     * Tests that deleteAllOfUser() deletes dialogs whose other user has
     * already permanently hidden them.
     */
    public function testDeleteAllOfUserDeletesDialogsAlreadyDeletedByOtherUser()
    {
        // User 2 has permanently hidden dialog 1.
        $this->db->insert('users_dialog_hidden')
            ->values(['c_id' => 1, 'user_id' => 2, 'permanent' => 1])
            ->execute();

        $this->out->deleteAllOfUser(1);

        // Dialog 1 is gone, dialog 2 still exists (user 3 did not delete it).
        self::assertNull($this->out->getDialogCheckByCId(1));
        self::assertNotNull($this->out->getDialogCheckByCId(2));

        // Documents current behavior: the remaining reply of user 2 in dialog 1
        // is orphaned (no FK from users_dialog_reply to users_dialog).
        $orphaned = $this->db->select('COUNT(*)')
            ->from('users_dialog_reply')
            ->where(['c_id_fk' => 1])
            ->execute()
            ->fetchCell();
        self::assertSame(1, (int) $orphaned);
    }

    /**
     * Returns database schema SQL statements to initialize database.
     *
     * @return string
     */
    protected static function getSchemaSQLQueries(): string
    {
        $config = new ModuleConfig();
        $adminConfig = new AdminConfig();

        return $adminConfig->getInstallSql() . $config->getInstallSql();
    }
}
