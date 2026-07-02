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
        $userCache = [];

        foreach ($shoutboxEntries as $entry) {
            if (!isset($userCache[$entry->getUid()])) {
                // User not in cache.
                $user = $userMapper->getUserById($entry->getUid());

                if ($user) {
                    $userCache[$entry->getUid()] = $user;
                }
            }
        }

        $this->getView()->set('dummyUser', $userMapper->getDummyUser());
        $this->getView()->set('users', $userCache);
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
