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
     *
     * @param int $userid the user
     * @param bool $showHidden
     * @return null|\Modules\User\Models\Dialog
     * @throws \Ilch\Database\Exception
     */
    public function getDialog($userid, $showHidden = true)
    {
        $sql = 'SELECT u.id, u.avatar, c.c_id, u.name, c.time, h.c_id AS hidden
        FROM [prefix]_users_dialog c
        LEFT JOIN [prefix]_users_dialog_hidden AS h ON h.c_id = c.c_id AND h.user_id = '.$userid.'
        LEFT JOIN [prefix]_users AS u ON
        CASE
        WHEN c.user_one = '.$userid.'
        THEN c.user_two = u.id
        WHEN c.user_two = '.$userid.'
        THEN c.user_one = u.id
        END
        WHERE
        (c.user_one = '.$userid.' OR c.user_two = '.$userid.')';
        $sql .= ($showHidden) ? ' AND h.permanent = 0' : ' AND h.c_id IS NULL';
        $sql .= ' ORDER BY c.time DESC';

        $dialogsArray = $this->db()->queryArray($sql);

        if (empty($dialogsArray)) {
            return null;
        }

        $dialogs = [];

        foreach ($dialogsArray as $dialog) {
            $dialogModel = new DialogModel();
            $dialogModel->setId($dialog['id']);
            $dialogModel->setCId($dialog['c_id']);

            if (!empty($dialog['name'])) {
                $dialogModel->setName($dialog['name']);
            } else {
                $dialogModel->setName('No longer exists');
            }
            $readLastOneDialog = $this->getReadLastOneDialog($dialog['c_id']);
            $dialogModel->setRead($readLastOneDialog);
            if (file_exists($dialog['avatar'])) {
                $dialogModel->setAvatar($dialog['avatar']);
            }  else {
                $dialogModel->setAvatar('static/img/noavatar.jpg');
            }
            $last = $this->getLastOneDialog($dialog['c_id']);
            if ($last != null) {
                $dialogModel->setText($last->getText());
                $dialogModel->setTime($last->getTime());
            } else {
                $dialogModel->setText('');
                $dialogModel->setTime('');
            }
            $dialogModel->setHidden(!empty($dialog['hidden']));
            $dialogs[] = $dialogModel;

        }

        return $dialogs;
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
        FROM [prefix]_users_dialog c
        LEFT JOIN [prefix]_users u ON
        CASE
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
        $dialog = $this->db()->select(['R.time', 'R.reply'])
            ->from(['R' => 'users_dialog_reply'])
            ->join(['U' => 'users'], 'R.user_id_fk = U.id')
            ->where(['R.c_id_fk' => $c_id])
            ->order(['R.cr_id' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        if (empty($dialog)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setText($dialog['reply']);
        $dialogModel->setTime($dialog['time']);

        return $dialogModel;
    }

    /**
     * Get the last dialog read or not
     *
     * @param int $c_id
     * @return null|dialogModel
     */
    public function getReadLastOneDialog($c_id)
    {
        $dialog = $this->db()->select(['R.cr_id', 'R.time', 'R.reply', 'R.read', 'R.user_id_fk'])
            ->from(['R' => 'users_dialog_reply'])
            ->join(['U' => 'users'], 'R.user_id_fk = U.id')
            ->where(['R.c_id_fk' => $c_id, 'R.read =' => 0])
            ->order(['R.cr_id' => 'DESC'])
            ->execute()
            ->fetchAssoc();

        if (empty($dialog)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setText($dialog['reply']);
        $dialogModel->setTime($dialog['time']);
        $dialogModel->setCrId($dialog['cr_id']);
        $dialogModel->setUserOne($dialog['user_id_fk']);
        $dialogModel->setRead($dialog['read']);

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
        $dialogArray = $this->db()->select(['R.cr_id', 'R.time', 'R.reply', 'U.id', 'U.name', 'U.avatar'])
            ->from(['R' => 'users_dialog_reply'])
            ->join(['U' => 'users'], 'U.id = R.user_id_fk')
            ->where(['R.c_id_fk' => $c_id])
            ->order(['R.cr_id' => 'DESC'])
            ->limit(20)
            ->execute()
            ->fetchRows();

        if (empty($dialogArray)) {
            return null;
        }

        $dialogModels = [];

        foreach ($dialogArray as $dialog) {
            $dialogModel = new DialogModel();
            $dialogModel->setId($dialog['id']);
            $dialogModel->setCrId($dialog['cr_id']);
            $dialogModel->setName($dialog['name']);
            $dialogModel->setText($dialog['reply']);
            $dialogModel->setTime($dialog['time']);
            if (file_exists($dialog['avatar'])) {
                $dialogModel->setAvatar($dialog['avatar']);
            }  else {
                $dialogModel->setAvatar('static/img/noavatar.jpg');
            }
            $dialogModels[] = $dialogModel;

        }

        return array_reverse($dialogModels);
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
        $messageRow = $this->db()->select(['cr_id'])
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
                ->values(['c_id' => $c_id, 'user_id' => $userId, 'permanent' => 0])
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
     * Check if a dialog exists by $c_id
     *
     * @param int $c_id
     * @return null|\Modules\User\Models\Dialog
     */
    public function getDialogCheckByCId($c_id)
    {
        $row = $this->db()->select(['user_one', 'user_two'])
            ->from('users_dialog')
            ->where(['c_id' => $c_id])
            ->execute()
            ->fetchAssoc();

        if (empty($row)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setUserOne($row['user_one']);
        $dialogModel->setUserTwo($row['user_two']);

        return $dialogModel;
    }

    /**
     * Check if a dialog exists by $user_one and $user_two
     *
     * @param int $user_one
     * @param int $user_two
     * @return DialogModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getDialogCheck($user_one, $user_two)
    {
        $select = $this->db()->select(['c_id', 'user_one', 'user_two'])
            ->from('users_dialog')
            ->where(['user_one' => $user_one, 'user_two' => $user_two]);
        $select->orWhere($select->andX(['user_one' => $user_two, 'user_two' => $user_one]));
        $row = $select->execute()
            ->fetchAssoc();

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
        $row = $this->db()->select(['c_id'])
            ->from('users_dialog')
            ->where(['user_one' => $user_one])
            ->order(['c_id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

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
