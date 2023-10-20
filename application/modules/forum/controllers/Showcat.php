<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\TrackRead;

class Showcat extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();

        $catId = $this->getRequest()->getParam('id');
        if (empty($catId) || !is_numeric($catId)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Category', 'errorText' => 'notFound']);
            return;
        }

        $cat = $forumMapper->getForumById($catId);
        if ($cat === null) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Category', 'errorText' => 'notFound']);
            return;
        }

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index','action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()]);

        $forumItems = $forumMapper->getForumItemsByParentIdsUser([$catId], $this->getUser());

        $forumIds = [];
        foreach ($forumItems as $forumItem) {
            $forumIds[] = $forumItem->getId();
        }

        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('containsUnreadTopics', ($this->getUser()) ? $forumMapper->getListOfForumIdsWithUnreadTopics($this->getUser()->getId(), $forumIds) : []);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }

    public function markallasreadAction()
    {
        if ($this->getUser() && $this->getRequest()->isSecure()) {
            $forumMapper = new ForumMapper();
            $trackRead = new TrackRead();

            $adminAccess = $this->getUser()->isAdmin();
            $forumIds = [];

            foreach ($forumMapper->getForumItemsByParentIdsUser([$this->getRequest()->getParam('id')] ?? [0], $this->getUser()) as $forumItem) {
                // If it is a forum and the user is either admin or has read access then the forum can be marked as read.
                if ($forumItem->getType() === 1 && $adminAccess || $forumItem->getReadAccess()) {
                    $forumIds[] = $forumItem->getId();
                }
            }

            if (!empty($forumIds)) {
                $trackRead->markForumsAsRead($this->getUser()->getId(), $forumIds);
            }
            $this->addMessage('markedAllForumsAsRead', 'info');
        } else {
            $this->addMessage('noAccessForum', 'warning');
        }

        $this->redirect(['module' => 'forum', 'controller' => 'showcat', 'action' => 'index', 'id' => $this->getRequest()->getParam('id')]);
    }
}
