<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;

class Showactivetopics extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $pagination = new \Ilch\Pagination();
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

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($this->getTranslator()->trans('showActiveTopics'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showActiveTopics'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($this->getTranslator()->trans('showActiveTopics'), ['action' => 'index']);

        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topics', $topicMapper->getTopics($pagination));
        $this->getView()->set('groupIdsArray', $groupIdsArray);
        $this->getView()->set('pagination', $pagination);
    }
}
