<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\User\Mappers;

use Modules\User\Models\Dialog as DialogModel;

/**
 * Mapper for the dialog feature.
 */
class Dialog extends \Ilch\Mapper
{
    /**
     * Get users dialog
     *
     * @param int $userid the user
     * @param bool $showHidden
     * @return null|DialogModel[]
     * @throws \Ilch\Database\Exception
     */
    public function getDialog(int $userid, bool $showHidden = true): ?array
    {
        $sql = 'SELECT u.id, u.avatar, u.name, c.c_id, c.time, h.c_id AS hidden,
                lr.reply AS last_reply,
                lr.time AS last_time,
                (SELECT COUNT(*) FROM [prefix]_users_dialog_reply r
                 WHERE r.c_id_fk = c.c_id AND r.user_id_fk <> ' . $userid . ' AND r.read = 0) AS unread_count
            FROM [prefix]_users_dialog c
            LEFT JOIN [prefix]_users AS u
                ON u.id = CASE WHEN c.user_one = ' . $userid . ' THEN c.user_two ELSE c.user_one END
            LEFT JOIN [prefix]_users_dialog_hidden AS h
                ON h.c_id = c.c_id AND h.user_id = ' . $userid . '
            LEFT JOIN [prefix]_users_dialog_reply AS lr
                ON lr.cr_id = (SELECT MAX(r2.cr_id) FROM [prefix]_users_dialog_reply r2 WHERE r2.c_id_fk = c.c_id)
            WHERE
            (c.user_one = ' . $userid . ' OR c.user_two = ' . $userid . ')
            AND ' . ($showHidden ? 'h.permanent = 0' : 'h.c_id IS NULL') . '
            ORDER BY c.time DESC';

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

            $dialogModel->setRead((int) $dialog['unread_count'] === 0);

            if (!empty($dialog['avatar']) && file_exists($dialog['avatar'])) {
                $dialogModel->setAvatar($dialog['avatar']);
            } else {
                $dialogModel->setAvatar('static/img/noavatar.jpg');
            }

            $dialogModel->setText((string) ($dialog['last_reply'] ?? ''));
            $dialogModel->setTime((string) ($dialog['last_time'] ?? ''));
            $dialogModel->setHidden(!empty($dialog['hidden']));
            $dialogs[] = $dialogModel;
        }

        return $dialogs;
    }

    /**
     * Get the user data shown in the dialog header.
     *
     * @param int $userId the user id of the other participant of the dialog
     * @return DialogModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getDialogByCId(int $userId): ?DialogModel
    {
        $userRow = $this->db()->select(['id', 'avatar', 'name'])
            ->from('users')
            ->where(['id' => $userId])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($userRow)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setId($userRow['id']);
        $dialogModel->setName($userRow['name']);

        if (!empty($userRow['avatar']) && file_exists($userRow['avatar'])) {
            $dialogModel->setAvatar($userRow['avatar']);
        } else {
            $dialogModel->setAvatar('static/img/noavatar.jpg');
        }

        return $dialogModel;
    }

    /**
     * Get the last dialog
     *
     * @param int $c_id
     * @return null|DialogModel
     */
    public function getLastOneDialog(int $c_id): ?DialogModel
    {
        $dialog = $this->db()->select(['R.time', 'R.reply'])
            ->from(['R' => 'users_dialog_reply'])
            ->where(['R.c_id_fk' => $c_id])
            ->order(['R.cr_id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($dialog)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setText((string) $dialog['reply']);
        $dialogModel->setTime((string) $dialog['time']);

        return $dialogModel;
    }

    /**
     * Get the last unread dialog reply
     *
     * @param int $c_id
     * @param int|null $userId if given, only replies of the other user are considered
     * @return null|DialogModel
     */
    public function getReadLastOneDialog(int $c_id, ?int $userId = null): ?DialogModel
    {
        $select = $this->db()->select(['R.cr_id', 'R.time', 'R.reply', 'R.user_id_fk'])
            ->from(['R' => 'users_dialog_reply'])
            ->where(['R.c_id_fk' => $c_id, 'R.read' => 0]);

        if ($userId !== null) {
            $select->andWhere(['R.user_id_fk !=' => $userId]);
        }

        $dialog = $select
            ->order(['R.cr_id' => 'DESC'])
            ->limit(1)
            ->execute()
            ->fetchAssoc();

        if (empty($dialog)) {
            return null;
        }

        $dialogModel = new DialogModel();
        $dialogModel->setText((string) $dialog['reply']);
        $dialogModel->setTime((string) $dialog['time']);
        $dialogModel->setCrId((int) $dialog['cr_id']);
        $dialogModel->setUserOne((int) $dialog['user_id_fk']);
        $dialogModel->setRead(false);

        return $dialogModel;
    }

    /**
     * Get the count of unread messages for a user
     *
     * @param int $user_id
     * @return int
     */
    public function getCountOfUnreadMessagesByUser(int $user_id): int
    {
        return (int)$this->db()->select('COUNT(*)')
            ->from(['r' => 'users_dialog_reply'])
            ->join(['u' => 'users_dialog'], 'r.c_id_fk = u.c_id')
            ->where(['u.user_one' => $user_id, 'u.user_two' => $user_id], 'or')
            ->andWhere(['r.user_id_fk !=' => $user_id, 'r.read' => 0])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get the dialog message
     *
     * @param int $c_id the user
     * @return null|DialogModel[]
     */
    public function getDialogMessage(int $c_id): ?array
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
            $dialogModel->setCId($c_id);
            $dialogModel->setId((int) $dialog['id']);
            $dialogModel->setCrId((int) $dialog['cr_id']);
            $dialogModel->setName((string) $dialog['name']);
            $dialogModel->setText((string) $dialog['reply']);
            $dialogModel->setTime((string) $dialog['time']);
            if (!empty($dialog['avatar']) && file_exists($dialog['avatar'])) {
                $dialogModel->setAvatar($dialog['avatar']);
            } else {
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
    public function isMessageOfUser(int $cr_id, int $userId): bool
    {
        $messageRow = $this->db()->select(['cr_id'])
            ->from('users_dialog_reply')
            ->where(['cr_id' => $cr_id, 'user_id_fk' => $userId])
            ->limit(1)
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
    public function deleteMessageOfUser(int $cr_id, int $userId)
    {
        $cId = $this->db()->select('c_id_fk')
            ->from('users_dialog_reply')
            ->where(['cr_id' => $cr_id, 'user_id_fk' => $userId])
            ->execute()
            ->fetchCell();

        $this->db()->delete('users_dialog_reply', ['cr_id' => $cr_id, 'user_id_fk' => $userId])
            ->execute();

        if ($cId) {
            $newTime = $this->db()->select('MAX(time)')
                ->from('users_dialog_reply')
                ->where(['c_id_fk' => $cId])
                ->execute()
                ->fetchCell();

            if (!empty($newTime)) {
                $this->db()->update('users_dialog')
                    ->values(['time' => $newTime])
                    ->where(['c_id' => $cId])
                    ->execute();
            }
        }
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
    private function deleteDialog(int $c_id, int $userId): int
    {
        if ($c_id && $userId) {
            $dialog = $this->db()->select()
                ->fields(['d.c_id', 'd.user_one', 'd.user_two'])
                ->from(['d' => 'users_dialog'])
                ->join(['firstuser' => 'users'], 'd.user_one = firstuser.id', 'LEFT', ['id_user_one' => 'firstuser.id'])
                ->join(['seconduser' => 'users'], 'd.user_two = seconduser.id', 'LEFT', ['id_user_two' => 'seconduser.id'])
                ->join(['dhotheruser' => 'users_dialog_hidden'], ['dhotheruser.permanent' => 1, 'dhotheruser.c_id = d.c_id', 'dhotheruser.user_id !=' => $userId], 'LEFT', ['id_other_user_permanent' => 'dhotheruser.user_id'])
                ->where(['d.c_id' => $c_id])
                ->limit(1)
                ->execute()
                ->fetchAssoc();

            if (($dialog['id_user_one'] == $userId && empty($dialog['id_user_two'])) || ($dialog['id_user_two'] == $userId && empty($dialog['id_user_one']))) {
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

        return 0;
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

        $cIds = [];

        foreach ($dialogs as $dialog) {
            if (empty($dialog['id_user_one']) && empty($dialog['id_user_two'])) {
                // Delete dialog if both users are not existing.
                $cIds[] = $dialog['c_id'];
                continue;
            }

            if (($dialog['id_user_one'] == $userId && empty($dialog['id_user_two'])) || ($dialog['id_user_two'] == $userId && empty($dialog['id_user_one']))) {
                // Delete dialog if other user is not existing.
                $cIds[] = $dialog['c_id'];
                continue;
            }

            if ($dialog['id_other_user_permanent']) {
                // Delete dialog if other user has already "deleted" it.
                $cIds[] = $dialog['c_id'];
            }
        }

        if (empty($cIds)) {
            return;
        }

        $this->db()->delete('users_dialog', ['c_id' => $cIds])
            ->execute();

        // Get rid of orphaned hidden dialog entries for both users.
        $this->db()->delete('users_dialog_hidden', ['c_id' => $cIds])
            ->execute();
    }

    /**
     * "Delete" or permantly hide dialog for user.
     *
     * @param int $c_id
     * @param int $userId
     * @return int
     * @since 2.1.43
     */
    public function permanentlyHideOrDeleteDialog(int $c_id, int $userId): int
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
        // Additionally, mark all messages of the other user as read.
        $this->hideDialog($c_id, $userId);

        $this->db()->update('users_dialog_hidden')
            ->values(['permanent' => 1])
            ->where(['c_id' => $c_id, 'user_id' => $userId])
            ->execute();

        $this->markAllAsRead($c_id, $userId);

        return 0;
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
    public function hideDialog(int $c_id, int $userId)
    {
        $this->db()->query('INSERT INTO [prefix]_users_dialog_hidden (c_id, user_id, permanent)
            SELECT ' . $c_id . ', ' . $userId . ', 0
            FROM DUAL
            WHERE NOT EXISTS (SELECT 1 FROM [prefix]_users_dialog_hidden WHERE c_id = ' . $c_id . ' AND user_id = ' . $userId . ')');
    }

    /**
     * Check if user has hidden a dialog.
     * Permanently hidden ("deleted") dialogs are not counted here, because they
     * are not shown in the hidden dialog view either.
     *
     * @param int $userId
     * @param bool|null $includePermanent true: count permanently hidden dialogs, otherwise: count hidden dialogs
     * @return bool
     * @since $includePermanent since 2.1.43
     */
    public function hasHiddenDialog(int $userId, ?bool $includePermanent = null): bool
    {
        $permanent = ($includePermanent === true) ? 1 : 0;

        return (bool) $this->db()->select('user_id')
            ->from('users_dialog_hidden')
            ->where(['user_id' => $userId, 'permanent' => $permanent])
            ->limit(1)
            ->execute()
            ->fetchCell();
    }
    /**
     * Unhide a dialog of a user.
     *
     * @param int $c_id
     * @param int $userId
     * @return int count of affected rows.
     */
    public function unhideDialog(int $c_id, int $userId): int
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
    public function unhideAllDialogsByUser(int $userId): int
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
    public function unhideDialogById(int $c_id): int
    {
        return $this->db()->delete('users_dialog_hidden', ['c_id' => $c_id])->execute();
    }

    /**
     * Check if a dialog exists by $c_id
     *
     * @param int $c_id
     * @return null|DialogModel
     */
    public function getDialogCheckByCId(int $c_id): ?DialogModel
    {
        $row = $this->db()->select(['user_one', 'user_two'])
            ->from('users_dialog')
            ->where(['c_id' => $c_id])
            ->limit(1)
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
     */
    public function getDialogCheck(int $user_one, int $user_two): ?DialogModel
    {
        $select = $this->db()->select(['c_id', 'user_one', 'user_two'])
            ->from('users_dialog')
            ->where(['user_one' => $user_one, 'user_two' => $user_two]);
        $select->orWhere($select->andX(['user_one' => $user_two, 'user_two' => $user_one]));
        $row = $select->limit(1)
            ->execute()
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
     * @return null|DialogModel
     */
    public function getDialogId(int $user_one): ?DialogModel
    {
        $row = $this->db()->select(['c_id'])
            ->from('users_dialog')
            ->where(['user_one' => $user_one, 'user_two' => $user_one], 'or')
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
        if (!empty($model->getUserOne()) && !empty($model->getUserTwo())) {
            $this->db()->insert('users_dialog')
                ->values([
                    'user_one' => $model->getUserOne(),
                    'user_two' => $model->getUserTwo(),
                    'time' => $model->getTime()
                ])
                ->execute();
            return;
        }

        $this->db()->insert('users_dialog_reply')
            ->values([
                'user_id_fk' => $model->getId(),
                'reply' => $model->getText(),
                'time' => $model->getTime(),
                'c_id_fk' => $model->getCId()
            ])
            ->execute();

        $this->db()->update('users_dialog')
            ->values(['time' => $model->getTime()])
            ->where(['c_id' => $model->getCId()])
            ->execute();
    }

    /**
     * Mark all messages of the other user in a dialog as read.
     *
     * @param int $c_id dialog id
     * @param int $userId id of the user (messages of the other user are getting marked as read)
     */
    public function markAllAsRead(int $c_id, int $userId)
    {
        $this->db()->update('users_dialog_reply')
            ->values(['read' => 1])
            ->where(['c_id_fk' => $c_id, 'user_id_fk <>' => $userId])
            ->execute();
    }
}
