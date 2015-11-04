<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;

class Showunread extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $pagination = new \Ilch\Pagination();
            $userMapper = new UserMapper();

            
            

            $userId = null;
            $groupIds = array(0);

            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = array();
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            $groupIdsArray = explode(',',implode(',', $groupIds));

            $pagination->setPage($this->getRequest()->getParam('page'));

            $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('controller' => 'index', 'action' => 'index'))
                ->add($this->getTranslator()->trans('showNewPosts'), array('action' => 'index'));

            $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('showNewPosts'));
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showNewPosts'));

            
            $this->getView()->set('topicMapper', $topicMapper);
            $this->getView()->set('forumMapper', $forumMapper);
            $this->getView()->set('topics', $topicMapper->getTopics($pagination));
            $this->getView()->set('groupIdsArray', $groupIdsArray);
            $this->getView()->set('pagination', $pagination);
        } else {
            $this->addMessage('noAccessForum', 'warning');
            $this->redirect(array('module' => 'forum', 'controller' => 'index'));
        }
    }
}
