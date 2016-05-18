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

        if($this->getUser()){
            $unread = '';
            $dialogCheck = new DialogMapper();
            $dialogs = $dialogCheck->getDialog($this->getUser()->getId());

            if (!empty($dialogs)) {
                foreach ($dialogs as $dialog) {
                    $dialogsUnread = $dialogCheck->getReadLastOneDialog($dialog->getCId());
                    if($dialogsUnread and $dialogsUnread->getUserOne() != $this->getUser()->getId()) {
                        $unread .= true;
                    }
                }
                $this->getView()->set('dialogUnread', $unread);
            }
        }
    }
}
