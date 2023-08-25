<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Mappers\TrackRead;
use Modules\User\Mappers\User as UserMapper;

class Shownewposts extends Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $postMapper = new PostMapper();
            $userMapper = new UserMapper();

            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            $this->getLayout()->getTitle()
                    ->add($this->getTranslator()->trans('forum'))
                    ->add($this->getTranslator()->trans('showNewPosts'));
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showNewPosts'));
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('showNewPosts'), ['action' => 'index']);

            $this->getView()->set('forumMapper', $forumMapper);
            $this->getView()->set('topicMapper', $topicMapper);
            $this->getView()->set('postMapper', $postMapper);
            $this->getView()->set('topics', $topicMapper->getTopics());
            $this->getView()->set('groupIdsArray', $groupIds);
            $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
            $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
        } else {
            $this->addMessage('noAccessForum', 'warning');
            $this->redirect(['module' => 'forum', 'controller' => 'index']);
        }
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

            foreach ($forumMapper->getForumItems() as $forumItem) {
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

        $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
    }
}
