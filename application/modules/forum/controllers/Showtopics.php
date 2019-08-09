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
use Modules\Forum\Mappers\TopicSubscription as TopicSubscriptionMapper;
use Ilch\Accesses as Accesses;

class Showtopics extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $postMapper = new PostMapper();
        $pagination = new \Ilch\Pagination();
        $userMapper = new UserMapper();

        $forumId = $this->getRequest()->getParam('forumid');
        if (empty($forumId) || !is_numeric($forumId)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Forum', 'errorText' => 'notFound']);
            return;
        }

        $forum = $forumMapper->getForumById($forumId);
        if (empty($forum)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Forum', 'errorText' => 'notFound']);
            return;
        }

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

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['action' => 'index', 'forumid' => $forumId]);

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_threadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_threadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('forum', $forum);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('postMapper', $postMapper);
        $this->getView()->set('topics', $topicMapper->getTopicsByForumId($forumId, $pagination));
        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('userAccess', new Accesses($this->getRequest()));
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }

    public function deleteAction()
    {
        $topicMapper = new TopicMapper();
        $topicSubscriptionMapper = new TopicSubscriptionMapper();

        if ($this->getUser()) {
            $access = new Accesses($this->getRequest());
            if ($access->hasAccess('forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicDelete') == 'topicDelete') {
                    foreach ($this->getRequest()->getPost('check_topics') as $topicId) {
                        $topicMapper->deleteById($topicId);
                        $topicSubscriptionMapper->deleteAllSubscriptionsForTopic($topicId);
                    }

                    $this->addMessage('deleteSuccess');
                    $this->redirect(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
                }
            }
        }
    }
}
