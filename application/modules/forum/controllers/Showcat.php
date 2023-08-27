<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\TrackRead;
use Modules\User\Mappers\User as UserMapper;

class Showcat extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $userMapper = new UserMapper();

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

        $forumItems = $forumMapper->getForumItemsByParent($catId, ($this->getUser()) ? $this->getUser()->getId() : null);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index','action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()]);

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $forumIds = [];
        foreach($forumItems as $forumItem) {
            $forumIds[] = $forumItem->getId();
        }

        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('containsUnreadTopics', ($this->getUser()) ? $forumMapper->getListOfForumIdsWithUnreadTopics($this->getUser()->getId(), $forumIds) : []);
        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }

    public function markallasreadAction()
    {
        if ($this->getUser() && $this->getRequest()->isSecure()) {
            $forumMapper = new ForumMapper();
            $trackRead = new TrackRead();
            $userMapper = new UserMapper();

            $adminAccess = $this->getUser()->isAdmin();
            $forumIds = [];
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            foreach ($forumMapper->getForumItemsByParent($this->getRequest()->getParam('id') ?? 0) as $forumItem) {
                // If it is a forum and the user is either admin or has read access then the forum can be marked as read.
                if ($forumItem->getType() === 1 && $adminAccess || is_in_array($groupIds, explode(',', $forumItem->getReadAccess()))) {
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
