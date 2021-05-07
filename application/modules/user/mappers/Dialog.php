<?php
/**
 * @copyright Ilch 2.0
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
    * @param int $c_id
    * @return null|\Modules\User\Models\Dialog
    */
    public function getLastOneDialog( $c_id )
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
    * @param int $c_id the user
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogMessage( $c_id )
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
     * Check if a user is the autor of a message.
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
     * Delete dialog.
     *
     * @param int $c_id
     */
    public function deleteDialog($c_id)
    {
        $this->db()->delete('users_dialog', ['c_id' => $c_id])
            ->execute();

        $this->db()->delete('users_dialog_hidden', ['c_id' => $c_id])
            ->execute();

        $this->db()->delete('users_dialog_reply', ['c_id_fk' => $c_id])
            ->execute();
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
     * @return bool
     */
    public function hasHiddenDialog($userId)
    {
        $dialogHiddenRow = $this->db()->select('user_id')
            ->from('users_dialog_hidden')
            ->where(['user_id' => $userId])
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
     * @param $userId
     * @return int
     */
    public function unhideAllDialogsByUser($userId)
    {
        return $this->db()->delete('users_dialog_hidden', ['user_id' => $userId])->execute();
    }

    /**
    * Check is exist dialog by $c_id
    * @param int $c_id
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogCheckByCId( $c_id )
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
    * @param int $user_one, $user_two
    * @return
    */
    public function getDialogCheck( $user_one, $user_two )
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
    * @param int $user_one
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogId( $user_one )
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
