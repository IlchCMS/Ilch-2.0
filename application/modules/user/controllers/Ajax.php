<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\Dialog as DialogMapper;

class Ajax extends \Ilch\Controller\Frontend
{
    public function checkNewMessageAction()
    {
        $this->getLayout()->setFile('modules/admin/layouts/ajax');

        if ($this->getUser()) {
            $unread = 0;
            $dialogCheck = new DialogMapper();

            $unread = $dialogCheck->getCountOfUnreadMessagesByUser($this->getUser()->getId());

            $this->getView()->set('dialogUnread', $unread);
        }
    }
}
