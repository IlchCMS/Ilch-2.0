<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Dialog as DialogModel;

class Dialog extends \Ilch\Mapper
{
    /**
     * Get users dialog
     * @param int $userid the user
     * @param bool $showHidden
     * @return null|\Modules\User\Models\Dialog
     * @throws \Ilch\Database\Exception
     */
    public function getDialog($userid, $showHidden = true)
    {
        $sql = 'SELECT u.id, u.avatar, c.c_id, u.name, c.time
        FROM [prefix]_users u, [prefix]_users_dialog c
        LEFT JOIN [prefix]_users_dialog_hidden AS h ON h.c_id = c.c_id and h.user_id = '.$userid.'
        WHERE CASE
        WHEN c.user_one = '.$userid.'
        THEN c.user_two = u.id
        WHEN c.user_two = '.$userid.'
        THEN c.user_one = u.id
        END
        AND
        (c.user_one = '.$userid.' or c.user_two = '.$userid.')';
        $sql .= ($showHidden) ? '' : ' AND h.c_id IS NULL ';
        $sql .= ' ORDER BY c.time DESC';

        $mailArray = $this->db()->queryArray($sql);

        if (empty($mailArray)) {
            return null;
        }

        $mails = [];

        foreach ($mailArray as $mail) {
            $mailModel = new DialogModel();
            $mailModel->setId($mail['id']);
            $mailModel->setCId($mail['c_id']);
            $mailModel->setName($mail['name']);
            $dialog = $this->getReadLastOneDialog($mail['c_id']);
            $mailModel->setRead($dialog);
            if (file_exists($mail['avatar'])) {
                $mailModel->setAvatar($mail['avatar']);
            }  else {
                $mailModel->setAvatar('static/img/noavatar.jpg');
            }
            $last = $this->getLastOneDialog($mail['c_id']);
            if ($last != null) {
                $mailModel->setText($last->getText());
                $mailModel->setTime($last->getTime());
            } else {
                $mailModel->setText('');
                $mailModel->setTime('');
            }

            $mails[] = $mailModel;

        }

        return $mails;
    }

    /**
     * Get dialog by dialog id.
     *
     * @param int $cId
     * @return DialogModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getDialogByCId($cId)
    {
        $sql = 'SELECT u.id, u.avatar, u.name
        FROM [prefix]_users u, [prefix]_users_dialog c
        WHERE CASE
        WHEN c.user_two = '.$cId.'
        THEN c.user_two = u.id
        WHEN c.user_one = '.$cId.'
        THEN c.user_one = u.id
        END';

        $dialogRow = $this->db()->queryRow($sql);

        if (empty($dialogRow)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setId($dialogRow['id']);
        $dialogModel->setName($dialogRow['name']);
        if (file_exists($dialogRow['avatar'])) {
            $dialogModel->setAvatar($dialogRow['avatar']);
        }  else {
            $dialogModel->setAvatar('static/img/noavatar.jpg');
        }

        return $dialogModel;
    }

    /**
     * Get the last dialog
     *
     * @param int $c_id
     * @return null|\Modules\User\Models\Dialog
     */
    public function getLastOneDialog($c_id)
    {
        $sql = 'SELECT R.cr_id,R.time,R.reply,U.id,U.name,U.avatar
                FROM [prefix]_users U, [prefix]_users_dialog_reply R 
                WHERE R.user_id_fk=U.id
                AND
                R.c_id_fk='.$c_id.'
                ORDER BY R.cr_id DESC';

        $mail = $this->db()->queryRow($sql);

        if (empty($mail)) {
            return null;
        }

        $mailModel = new DialogModel();
        $mailModel->setText($mail['reply']);
        $mailModel->setTime($mail['time']);

        return $mailModel;
    }

    /**
     * Get the last dialog read or not
     *
     * @param int $c_id
     * @return null|dialogModel
     */
    public function getReadLastOneDialog($c_id)
    {
        $sql = 'SELECT R.cr_id,R.time,R.reply,R.read,R.user_id_fk,U.id,U.name,U.avatar
                FROM [prefix]_users U, [prefix]_users_dialog_reply R
                WHERE R.user_id_fk=U.id
                AND R.c_id_fk='.$c_id.'
                AND R.read = 0
                ORDER BY R.cr_id DESC';

        $mail = $this->db()->queryRow($sql);

        if (empty($mail)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setText($mail['reply']);
        $dialogModel->setTime($mail['time']);
        $dialogModel->setCrId($mail['cr_id']);
        $dialogModel->setUserOne($mail['user_id_fk']);
        $dialogModel->setRead($mail['read']);

        return $dialogModel;
    }

    /**
     * Get the count of unread messages for a user
     *
     * @param int $user_id
     * @return int
     */
    public function getCountOfUnreadMessagesByUser($user_id)
    {
        $result = $this->db()->select('COUNT(*)')
            ->from(['r' => 'users_dialog_reply', 'u' => 'users_dialog'])
            ->join(['u' => 'users_dialog'], 'r.c_id_fk = u.c_id')
            ->where(['u.user_one' => $user_id, 'u.user_two' => $user_id], 'or')
            ->andWhere(['r.user_id_fk !=' => $user_id, 'r.read' => 0])
            ->execute();

        return $result->fetchCell();
    }

    /**
     * Get the dialog message
     *
     * @param int $c_id the user
     * @return null|\Modules\User\Models\Dialog
     */
    public function getDialogMessage($c_id)
    {
        $sql = 'SELECT R.cr_id,R.time,R.reply,U.id,U.name,U.avatar
                FROM [prefix]_users U, [prefix]_users_dialog_reply R 
                WHERE R.user_id_fk=U.id
                AND
                R.c_id_fk='.$c_id.'
                ORDER BY R.cr_id DESC LIMIT 20';

        $mailArray = $this->db()->queryArray($sql);

        if (empty($mailArray)) {
            return null;
        }

        $mails = [];

        foreach ($mailArray as $mail) {
            $mailModel = new DialogModel();
            $mailModel->setId($mail['id']);
            $mailModel->setCrId($mail['cr_id']);
            $mailModel->setName($mail['name']);
            $mailModel->setText($mail['reply']);
            $mailModel->setTime($mail['time']);
            if (file_exists($mail['avatar'])) {
                $mailModel->setAvatar($mail['avatar']);
            }  else {
                $mailModel->setAvatar('static/img/noavatar.jpg');
            }
            $mails[] = $mailModel;

        }

        return array_reverse($mails);
    }

    /**
     * Check if a user is the author of a message.
     *
     * @param int $cr_id
     * @param int $userId
     * @return bool
     */
    public function isMessageOfUser($cr_id, $userId)
    {
        $messageRow = $this->db()->select(['cr_id', 'user_id_fk'])
            ->from('users_dialog_reply')
            ->where(['cr_id' => $cr_id, 'user_id_fk' => $userId])
            ->execute()
            ->fetchRow();

        if (empty($messageRow)) {
            return false;
        }

        return true;
    }

    /**
     * Delete message of user.
     *
     * @param int $cr_id
     * @param int $userId
     */
    public function deleteMessageOfUser($cr_id, $userId)
    {
        $this->db()->delete('users_dialog_reply', ['cr_id' => $cr_id, 'user_id_fk' => $userId])
            ->execute();
    }

    /**
     * Delete all messages of a user within a conversation/dialog.
     *
     * @param int $c_id id of the conversation/dialog
     * @param int $userId id of the user
     * @since 2.1.43
     */
    private function deleteMessagesOfUserInDialog(int $c_id, int $userId)
    {
        $this->db()->delete('users_dialog_reply', ['c_id_fk' => $c_id, 'user_id_fk' => $userId])
            ->execute();
    }

    /**
     * Delete all messages of a user.
     * Call this for example when the user gets deleted.
     *
     * @param int $userId id of the user
     * @since 2.1.43
     */
    private function deleteAllMessagesOfUser(int $userId)
    {
        $this->db()->delete('users_dialog_reply', ['user_id_fk' => $userId])
            ->execute();
    }

    /**
     * Delete dialog if both users are no longer existing or one of them if the other is specified.
     *
     * @param int $c_id
     * @param int $userId
     * @return int
     * @since 2.1.43
     */
    private function deleteDialog(int $c_id, int $userId)
    {
        if ($c_id && $userId) {
            $dialog = $this->db()->select()
                ->fields(['d.c_id', 'd.user_one', 'd.user_two'])
                ->from(['d' => 'users_dialog'])
                ->join(['firstuser' => 'users'], 'd.user_one = firstuser.id', 'LEFT', ['id_user_one' => 'firstuser.id'])
                ->join(['seconduser' => 'users'], 'd.user_two = seconduser.id', 'LEFT', ['id_user_two' => 'seconduser.id'])
                ->join(['dhotheruser' => 'users_dialog_hidden'], ['dhotheruser.permanent' => 1, 'dhotheruser.c_id = d.c_id', 'dhotheruser.user_id !=' => $userId], 'LEFT', ['id_other_user_permanent' => 'dhotheruser.user_id'])
                ->where(['d.c_id' => $c_id])
                ->execute()
                ->fetchAssoc();

            if (($dialog['id_user_one'] == $userId && empty($dialog['id_user_two']))
                || ($dialog['id_user_two'] == $userId && empty($dialog['id_user_one']))) {
                // Delete dialog if other user is not existing.
                return $this->db()->delete('users_dialog', ['c_id' => $c_id])
                    ->execute();
            }

            if ($dialog['id_other_user_permanent']) {
                // Delete dialog if other user has already "deleted" it.
                return $this->db()->delete('users_dialog', ['c_id' => $c_id])
                    ->execute();
            }
        }
    }

    /**
     * Delete all dialogs of a user.
     *
     * @param int $userId
     * @since 2.1.43
     */
    private function deleteAllDialogsOfUser(int $userId)
    {
        $dialogs = $this->db()->select()
            ->fields(['d.c_id', 'd.user_one', 'd.user_two'])
            ->from(['d' => 'users_dialog'])
            ->join(['firstuser' => 'users'], 'd.user_one = firstuser.id', 'LEFT', ['id_user_one' => 'firstuser.id'])
            ->join(['seconduser' => 'users'], 'd.user_two = seconduser.id', 'LEFT', ['id_user_two' => 'seconduser.id'])
            ->join(['dhotheruser' => 'users_dialog_hidden'], ['dhotheruser.permanent' => 1, 'dhotheruser.c_id = d.c_id', 'dhotheruser.user_id !=' => $userId], 'LEFT', ['id_other_user_permanent' => 'dhotheruser.user_id'])
            ->where(['d.user_one' => $userId, 'd.user_two' => $userId], 'or')
            ->execute()
            ->fetchRows();

        foreach ($dialogs as $dialog) {
            if (empty($dialog['id_user_one']) && empty($dialog['id_user_two'])) {
                // Delete dialog if both users are not existing.
                $this->db()->delete('users_dialog', ['c_id' => $dialog['c_id']])
                    ->execute();
                continue;
            }

            if (($dialog['id_user_one'] == $userId && empty($dialog['id_user_two']))
                || ($dialog['id_user_two'] == $userId && empty($dialog['id_user_one']))) {
                // Delete dialog if other user is not existing.
                $this->db()->delete('users_dialog', ['c_id' => $dialog['c_id']])
                    ->execute();
                continue;
            }

            if ($dialog['id_other_user_permanent']) {
                // Delete dialog if other user has already "deleted" it.
                $this->db()->delete('users_dialog', ['c_id' => $c_id])
                    ->execute();
            }
        }
    }

    /**
     * "Delete" or permantly hide dialog for user.
     *
     * @param int $c_id
     * @param int $userId
     * @return int
     * @since 2.1.43
     */
    public function permanentlyHideOrDeleteDialog(int $c_id, int $userId)
    {
        $this->deleteMessagesOfUserInDialog($c_id, $userId);
        $affectedRows = $this->deleteDialog($c_id, $userId);

        if ($affectedRows) {
            // Dialog was deleted completely. Get rid of possibly existing hidden dialog entries.
            $this->unhideDialogById($c_id);
            return $affectedRows;
        }

        // Dialog couldn't be really deleted as other user still uses it.
        // Hide the dialog and set it as permanently hidden (to make it later look like deleted).
        $this->hideDialog($c_id, $userId);

        $this->db()->update('users_dialog_hidden')
            ->values(['permanent' => 1])
            ->where(['c_id' => $c_id, 'user_id' => $userId])
            ->execute();
    }

    /**
     * Delete all messages of the user and (hidden) dialogs as possible.
     * Call this if the user gets deleted.
     *
     * @param int $userId
     * @since 2.1.43
     */
    public function deleteAllOfUser(int $userId)
    {
        $this->deleteAllMessagesOfUser($userId);
        $this->deleteAllDialogsOfUser($userId);
        $this->unhideAllDialogsByUser($userId);
    }

    /**
     * Add dialog to list of hidden dialogs.
     *
     * @param int $c_id
     * @param int $userId
     */
    public function hideDialog($c_id, $userId)
    {
        $dialogHiddenRow = $this->db()->select('*')
            ->from('users_dialog_hidden')
            ->where(['c_id' => $c_id, 'user_id' => $userId])
            ->execute()
            ->fetchRow();

        if (empty($dialogHiddenRow)) {
            $this->db()->insert('users_dialog_hidden')
                ->values(['c_id' => $c_id, 'user_id' => $userId])
                ->execute();
        }
    }

    /**
     * Check if user has hidden a dialog.
     *
     * @param int $userId
     * @param null|bool $includePermanent
     * @return bool
     * @since $includePermanent since 2.1.43
     */
    public function hasHiddenDialog($userId, $includePermanent = null)
    {
        $dialogHiddenRow = $this->db()->select('user_id')
            ->from('users_dialog_hidden')
            ->where(['user_id' => $userId, 'permanent' => $includePermanent])
            ->execute()
            ->fetchRow();

        return (!empty($dialogHiddenRow));
    }

    /**
     * Unhide a dialog of an user.
     *
     * @param int $c_id
     * @param int $userId
     * @return int count of affected rows.
     */
    public function unhideDialog($c_id, $userId)
    {
        return $this->db()->delete('users_dialog_hidden', ['c_id' => $c_id, 'user_id' => $userId])->execute();
    }

    /**
     * Unhide all dialogs of a specific user.
     * This can be called too, when the user gets deleted to get rid of then orphaned entries.
     *
     * @param int $userId
     * @return int
     */
    public function unhideAllDialogsByUser($userId)
    {
        return $this->db()->delete('users_dialog_hidden', ['user_id' => $userId])->execute();
    }

    /**
     * Unhide a dialog for everyone.
     * Call this when the dialog gets finally deleted.
     *
     * @param int $c_id
     * @return int
     */
    public function unhideDialogById(int $c_id)
    {
        return $this->db()->delete('users_dialog_hidden', ['c_id' => $c_id])->execute();
    }

    /**
    * Check is exist dialog by $c_id
     *
    * @param int $c_id
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogCheckByCId($c_id)
    {
        $sql = 'SELECT user_one, user_two
                FROM [prefix]_users_dialog
                WHERE c_id='.$c_id;

        $row = $this->db()->queryRow($sql);

        if (empty($row)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setUserOne($row['user_one']);
        $dialogModel->setUserTwo($row['user_two']);

        return $dialogModel;
    }

    /**
     * Check is exist dialog by $user_one and $user_two
     *
     * @param int $user_one
     * @param int $user_two
     * @return DialogModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getDialogCheck($user_one, $user_two)
    {
        $sql = 'SELECT c_id, user_one, user_two
                FROM [prefix]_users_dialog
                WHERE (user_one='.$user_one.' AND user_two='.$user_two.') OR
                    (user_one='.$user_two.' AND user_two='.$user_one.')';

        $row = $this->db()->queryRow($sql);

        if (empty($row)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setUserOne($row['user_one']);
        $dialogModel->setUserTwo($row['user_two']);
        $dialogModel->setCId($row['c_id']);

        return $dialogModel;
    }

     /**
    * Get the dialog id
      *
    * @param int $user_one
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogId($user_one)
    {
        $sql = 'SELECT c_id
                FROM [prefix]_users_dialog
                WHERE user_one='.$user_one.' 
                ORDER BY c_id DESC limit 1';

        $row = $this->db()->queryRow($sql);

        if (empty($row)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setCId($row['c_id']);

        return $dialogModel;
    }

    /**
     * Inserts or updates dialog entry.
     *
     * @param DialogModel $model
     */
    public function save(DialogModel $model)
    {
        $fields = [
            'user_id_fk' => $model->getId(),
            'reply' => $model->getText(),
            'time' => $model->getTime(),
            'c_id_fk' => $model->getCId(),
            'user_one' => $model->getUserOne(),
            'user_two' => $model->getUserTwo()
        ];

        if (!empty($fields['user_one']) || !empty($fields['user_two'])) {
            $this->db()->insert('users_dialog')
                ->values($fields)
                ->execute();
            return;
        }

        $this->db()->insert('users_dialog_reply')
            ->values($fields)
            ->execute();

        $this->db()->update('users_dialog')
            ->values(['time' => $model->getTime()])
            ->where(['c_id' => $model->getCId()])
            ->execute();
    }

    /**
     * Updates dialog read.
     *
     * @param DialogModel $model
     */
    public function updateRead(DialogModel $model)
    {
        $fields = [
            'cr_id' => $model->getCrId(),
            'read' => $model->getRead(),
        ];

        $this->db()->update('users_dialog_reply')
                ->values($fields)
                ->where(['cr_id' => $model->getCrId()])
                ->execute();
    }
}
