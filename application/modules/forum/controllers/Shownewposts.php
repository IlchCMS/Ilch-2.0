<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\User\Mappers\User as UserMapper;

class Shownewposts extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $postMapper = new PostMapper();
            $pagination = new \Ilch\Pagination();
            $userMapper = new UserMapper();

            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            $pagination->setRowsPerPage(!$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
            $pagination->setPage($this->getRequest()->getParam('page'));

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
            $this->getView()->set('topics', $topicMapper->getTopics($pagination));
            $this->getView()->set('groupIdsArray', $groupIds);
            $this->getView()->set('pagination', $pagination);
        } else {
            $this->addMessage('noAccessForum', 'warning');
            $this->redirect(['module' => 'forum', 'controller' => 'index']);
        }
    }
}
