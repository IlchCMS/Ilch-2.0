<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Showunansweredtopics extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'))
            ->add($this->getTranslator()->trans('showUnansweredTopics'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showUnansweredTopics'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('showUnansweredTopics'), ['action' => 'index']);

        $isAdmin = $this->getUser() && $this->getUser()->isAdmin();

        $forums = $forumMapper->getForumItemsUser($this->getUser());
        $topics = $topicMapper->getTopicsByForumIds(array_keys($forums));

        $topicsToShow = [];
        foreach ($topics as $topic) {
            if (($topic->getCountPosts() === 1) && ($isAdmin || $forums[$topic->getForumId()]->getReadAccess())) {
                $topicsToShow[] = [
                    'topic' => $topic,
                    'forumPrefix' => $forums[$topic->getForumId()]->getPrefix(),
                    'lastPost' => ($this->getUser()) ? $topicMapper->getLastPostByTopicId($topic->getId(), $this->getUser()->getId()) : $topicMapper->getLastPostByTopicId($topic->getId()),
                ];
            }
        }

        $this->getView()->set('topics', $topicsToShow);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }
}
