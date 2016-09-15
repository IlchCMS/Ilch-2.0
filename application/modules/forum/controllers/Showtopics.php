<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Accesses as Accesses;

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
        $groupIds = [3];

       if ($this->getRequest()->isPost() && $this->getRequest()->getPost('forumEdit') === 'forumEdit') {
           $this->getView()->set('forumEdit', true);
       }

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['action' => 'index', 'forumid' => $forumId]);

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_threadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_threadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('forum', $forum);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topics', $topicMapper->getTopicsByForumId($forumId, $pagination));
        $this->getView()->set('groupIdsArray', $groupIdsArray);
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('userAccess', new Accesses($this->getRequest()));
    }

    public function deleteAction()
    {
        $topicMapper = new TopicMapper();

        if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicDelete') == 'topicDelete') {
            foreach ($this->getRequest()->getPost('check_topics') as $topicId) {
                $topicMapper->deleteById($topicId);
            }

            $this->addMessage('deleteSuccess');
            $this->redirect(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
        }
    }
}
