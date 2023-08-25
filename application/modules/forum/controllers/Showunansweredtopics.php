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
use Modules\User\Mappers\User as UserMapper;

class Showunansweredtopics extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $postMapper = new PostMapper();
        $userMapper = new UserMapper();

        $userId = null;
        $groupIds = [3];

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }
        $user = $userMapper->getUserById($userId);

        if ($this->getUser()) {
            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($this->getTranslator()->trans('showUnansweredTopics'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showUnansweredTopics'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('showUnansweredTopics'), ['action' => 'index']);

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('postMapper', $postMapper);
        $this->getView()->set('topics', $topicMapper->getTopics());
        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }
}
