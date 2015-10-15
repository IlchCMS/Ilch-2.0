<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Showtopics extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $pagination = new \Ilch\Pagination();
        $userMapper = new UserMapper();

        $forumId = $this->getRequest()->getParam('forumid');
        $forum = $forumMapper->getForumById($forumId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $userId = null;
        $groupIds = array(0);

       if ($this->getRequest()->isPost() && $this->getRequest()->getPost('forumEdit') === 'forumEdit') {
           $forumEdit = true;
           $this->getView()->set('forumEdit', $forumEdit);
       }

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = array();
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forum').' - '.$forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('controller' => 'index', 'action' => 'index'))
                ->add($cat->getTitle(), array('controller' => 'showcat','action' => 'index', 'id' => $cat->getId()))
                ->add($forum->getTitle(), array('action' => 'index', 'forumid' => $forumId));

        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('forum', $forum);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topics', $topicMapper->getTopicsByForumId($forumId, $pagination));
        $this->getView()->set('groupIdsArray', $groupIdsArray);
        $this->getView()->set('pagination', $pagination);
    }
}
