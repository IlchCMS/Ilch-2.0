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

        $this->getView()->set('dummyUser', $userMapper->getDummyUser());
        $this->getView()->set('users', $shoutboxMapper->getUsersOfEntries($shoutboxEntries));
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

    /**
     * Deletes an own entry (or any entry as admin).
     *
     * @since 1.8.0
     */
    public function deleteAction()
    {
        if ($this->getRequest()->isSecure()) {
            $shoutboxMapper = new ShoutboxMapper();
            $entry = $shoutboxMapper->getEntryById((int)$this->getRequest()->getParam('id'));
            $user = $this->getUser();

            if ($entry !== null && $user !== null && ($entry->getUid() === $user->getId() || $user->isAdmin())) {
                $shoutboxMapper->delete($entry->getId());
                $this->addMessage('deleteSuccess');
            }
        }

        $this->redirect(['action' => 'index']);
    }
}
