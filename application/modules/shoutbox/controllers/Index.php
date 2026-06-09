<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index']);

        $pagination->setRowsPerPage($this->getConfig()->get('shoutbox_messagesPerPage') ?: $this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $shoutboxEntries = $shoutboxMapper->getEntriesBy([], ['id' => 'DESC'], $pagination);
        $userNameCache = [];

        foreach ($shoutboxEntries as $entry) {
            if (!isset($userNameCache[$entry->getUid()])) {
                // User not in cache.
                $user = $userMapper->getUserById($entry->getUid());

                if ($user) {
                    $userNameCache[$entry->getUid()] = $user->getName();
                }
            }
        }

        $this->getView()->set('dummyUserName', $userMapper->getDummyUser()->getName());
        $this->getView()->set('userNames', $userNameCache);
        $this->getView()->set('shoutbox', $shoutboxEntries);
        $this->getView()->set('pagination', $pagination);
    }

    /**
     * For use of ajax shoutbox
     */
    public function ajaxAction()
    {
        echo $this->getLayout()->getBox('shoutbox', 'shoutbox');
    }
}
