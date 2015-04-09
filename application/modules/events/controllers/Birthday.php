<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Birthday extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $date = new \Ilch\Date();
        $birthdayMapper = new UserMapper();
        $eventMapper = new EventMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuEventList'), array('controller' => 'index'))
                ->add($this->getTranslator()->trans('menuBirthdayList'), array('controller' => 'birthday'));

        $this->getView()->set('birthdayDateNow', $date->format('Y-m-d'));
        $this->getView()->set('birthdayDateNowYMD', $date->format('Ymd'));
        $this->getView()->set('birthdayListNOW', $eventMapper->getBirthdayUserListNOW());
        $this->getView()->set('birthdayList', $birthdayMapper->getUserList());
    }
}


