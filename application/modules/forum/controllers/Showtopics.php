<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Pagination;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\TrackRead;

class Showtopics extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $pagination = new Pagination();

        $forumId = $this->getRequest()->getParam('forumid');
        if (empty($forumId) || !is_numeric($forumId)) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Forum', 'errorText' => 'notFound']);
            return;
        }

        $forum = $forumMapper->getForumByIdUser($forumId, $this->getUser());
        if ($forum === null) {
            $this->redirect(['module' => 'error', 'controller' => 'index', 'action' => 'index', 'error' => 'Forum', 'errorText' => 'notFound']);
            return;
        }

        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('forumEdit') === 'forumEdit') {
            $this->getView()->set('forumEdit', true);
        }

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'))
            ->add($cat->getTitle())
            ->add($forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum') . ' - ' . $forum->getDesc());
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
            ->add($forum->getTitle(), ['action' => 'index', 'forumid' => $forumId]);

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_threadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_threadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        $topics = $topicMapper->getTopicsByForumId($forumId, $pagination);

        $posts = $topicMapper->getLastPostsByTopicIds(array_keys($topics), ($this->getUser()) ? $this->getUser()->getId() : null);
        $postTopicRelation = [];
        foreach ($posts ?? [] as $index => $post) {
            $postTopicRelation[$post->getTopicId()] = $index;
        }

        $this->getView()->set('forum', $forum);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('topics', $topics);
        $this->getView()->set('posts', $posts);
        $this->getView()->set('postTopicRelation', $postTopicRelation);
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }

    public function deleteAction()
    {
        $topicMapper = new TopicMapper();

        if ($this->getUser()) {
            if ($this->getUser()->hasAccess('module_forum') || $this->getUser()->isAdmin()) {
                if ($this->getRequest()->isSecure() && $this->getRequest()->getPost('topicDelete') === 'topicDelete') {
                    foreach ($this->getRequest()->getPost('check_topics') as $topicId) {
                        $topicMapper->deleteById($topicId);
                    }

                    $this->addMessage('deleteSuccess');
                    $this->redirect(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
                }
            }
        }
    }

    public function marktopicsasreadAction()
    {
        if ($this->getUser() && $this->getRequest()->isSecure()) {
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $trackRead = new TrackRead();

            $adminAccess = $this->getUser()->isAdmin();
            $topicIds = [];
            $forum = $forumMapper->getForumByIdUser($this->getRequest()->getParam('forumid'), $this->getUser());

            // If the topic belongs to a forum and the user is either admin or has read access then the topic can be marked as read.
            if (!empty($forum) && $forum->getType() === 1 && ($adminAccess || $forum->getReadAccess())) {
                $topics = $topicMapper->getTopicsByForumId($this->getRequest()->getParam('forumid'));
                foreach ($topics as $topic) {
                    $topicIds[] = $topic->getId();
                }

                if (!empty($topicIds)) {
                    if (count($topics) === count($topicIds)) {
                        // As we want to mark all topics in a forum as read, we can mark the forum as read instead of each topic.
                        $trackRead->markForumAsRead($this->getUser()->getId(), $forum->getId());
                    } else {
                        // Mark each topic as read as not all topics can be marked as read.
                        $trackRead->markTopicsAsRead($this->getUser()->getId(), $topicIds, $forum->getId());
                    }
                }
            }

            $this->addMessage('markedAllTopicsAsRead', 'info');
        } else {
            $this->addMessage('noAccessForum', 'warning');
        }

        $this->redirect(['module' => 'forum', 'controller' => 'showtopics', 'action' => 'index', 'forumid' => $this->getRequest()->getParam('forumid')]);
    }
}
