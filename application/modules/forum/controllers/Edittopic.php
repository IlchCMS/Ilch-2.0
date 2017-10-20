<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Models\ForumPost as PostModel;
use Ilch\Accesses as Accesses;

class Edittopic extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $userMapper = new UserMapper();
        $forumMapper = new ForumMapper();
        $forumItems = $forumMapper->getForumItemsByParent(0);

        $forumId = $this->getRequest()->getParam('forumid');
        $forum = $forumMapper->getForumById($forumId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $userId = null;
        $groupIds = [3];

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
            ->add($cat->getTitle())
            ->add($forum->getTitle())
            ->add($this->getTranslator()->trans('topicMove'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
            ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forumId])
            ->add($this->getTranslator()->trans('topicMove'), ['action' => 'index', 'forumid' => $forumId]);

        if ($this->getUser()) {
            $access = new Accesses($this->getRequest());

            if ($access->hasAccess('forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('edittopic')) {
                    $topicMapper = new TopicMapper();
                    $topicModel = new TopicModel();
                    $postMapper = new PostMapper();
                    $postModel = new PostModel();

                    $topics = $this->getRequest()->getPost('topicids');
                    foreach ($topics as $topic) {
                        $topicModel->setId($topic)
                            ->setTopicId($this->getRequest()->getPost('edit'))
                            ->setForumId($this->getRequest()->getPost('edit'));
                        $topicMapper->save($topicModel);

                        $posts = $postMapper->getPostByTopicId($topic);
                        foreach ($posts as $post) {
                            $postModel->setId($post->getId())
                                ->setTopicId($this->getRequest()->getPost('edit'))
                                ->setForumId($this->getRequest()->getPost('edit'));
                            $postMapper->saveForEdit($postModel);
                        }
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getPost('edit')]);
                }
            }
        }

        $this->getView()->set('groupIdsArray', $groupIdsArray)
            ->set('forumItems', $forumItems)
            ->set('editTopicItems', $this->getRequest()->getPost('check_topics'));
    }

    public function statusAction()
    {
        $topicMapper = new TopicMapper();

        if ($this->getUser()) {
            $access = new Accesses($this->getRequest());
            if ($access->hasAccess('forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicChangeStatus') == 'topicChangeStatus') {
                    foreach ($this->getRequest()->getPost('check_topics') as $topicId) {
                        $topicMapper->updateStatus($topicId);
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
                }
            }
        }
    }
}
