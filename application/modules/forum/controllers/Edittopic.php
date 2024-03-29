<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Models\ForumTopic as TopicModel;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Models\ForumPost as PostModel;

class Edittopic extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();

        $user = null;
        $forumId = $this->getRequest()->getParam('forumid');
        $forum = $forumMapper->getForumByIdUser($forumId, $this->getUser());
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

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
            if ($this->getUser()->hasAccess('module_forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('edittopic')) {
                    $topicMapper = new TopicMapper();
                    $topicModel = new TopicModel();
                    $postMapper = new PostMapper();
                    $postModel = new PostModel();

                    $topics = $this->getRequest()->getPost('topicids');
                    foreach ($topics as $topic) {
                        $topicModel->setId($topic)
                            ->setForumId($this->getRequest()->getPost('edit'));
                        $topicMapper->save($topicModel);

                        $posts = $postMapper->getPostsByTopicId($topic);
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

        $this->getView()->set('forumItems', $forumMapper->getForumItemsByParentIdsUser([0], $user))
            ->set('editTopicItems', $this->getRequest()->getPost('check_topics'));
    }

    public function statusAction()
    {
        $topicMapper = new TopicMapper();

        if ($this->getUser()) {
            if ($this->getUser()->hasAccess('module_forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicChangeStatus') === 'topicChangeStatus') {
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

    public function typeAction()
    {
        $topicMapper = new TopicMapper();

        if ($this->getUser()) {
            if ($this->getUser()->hasAccess('module_forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicChangeType') === 'topicChangeType') {
                    foreach ($this->getRequest()->getPost('check_topics') as $topicId) {
                        $topicMapper->updateType($topicId);
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
                }
            }
        }
    }
}
