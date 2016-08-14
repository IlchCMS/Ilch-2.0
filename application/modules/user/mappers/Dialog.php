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
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialog( $userid )
    {
        $sql = 'SELECT u.id, u.avatar, c.c_id, u.name, c.time
        FROM [prefix]_users u, [prefix]_users_dialog c
        WHERE CASE
        WHEN c.user_one = '.$userid.'
        THEN c.user_two = u.id
        WHEN c.user_two = '.$userid.'
        THEN c.user_one = u.id
        END
        AND
        (c.user_one = '.$userid.' or c.user_two = '.$userid.')
        ORDER BY c.c_id DESC';

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
                AND
                R.c_id_fk='.$c_id.'
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
    * Check is exist dialog by $c_id
    * @param int $c_id
    * @return null|\Modules\User\Models\Dialog
    */
    public function getDialogCheckByCId( $c_id )
    {
        $sql = 'SELECT user_one, user_two
                FROM [prefix]_users_dialog
                WHERE c_id='.$c_id.'';

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
        $sql = 'SELECT c_id
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

        if (!empty($fields['user_one']) or !empty($fields['user_two'])) {
            $this->db()->insert('users_dialog')
                ->values($fields)
                ->execute();
            return ;
        }  else {
            $this->db()->insert('users_dialog_reply')
                ->values($fields)
                ->execute();
        }

        return ;
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

        return ;
    }
}
