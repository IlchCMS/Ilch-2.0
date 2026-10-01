<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Ilch\Date;
use Ilch\Pagination;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Showactivetopics extends Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $pagination = new Pagination();
        $date = new Date();
        $dateLessHours = new Date('-1 day');

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'))
            ->add($this->getTranslator()->trans('showActiveTopics'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showActiveTopics'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('showActiveTopics'), ['action' => 'index']);

        $isAdmin = $this->getUser() && $this->getUser()->isAdmin();

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_threadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_threadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $forums = $forumMapper->getForumItemsUser($this->getUser());
        $topics = $topicMapper->getTopicsByForumIds(array_keys($forums ?? []), $pagination);

        $topicIds = [];
        $topicsToShow = [];
        foreach ($topics as $topic) {
            if ($isAdmin || $forums[$topic->getForumId()]->getReadAccess()) {
                $topicIds[] = $topic->getId();
            }
        }

        $posts = $topicMapper->getLastPostsByTopicIds($topicIds, ($this->getUser()) ? $this->getUser()->getId() : null);

        foreach ($posts ?? [] as $post) {
            if ($post->getDateCreated() < $date->format('Y-m-d H:i:s', true) && $post->getDateCreated() > $dateLessHours->format('Y-m-d H:i:s', true)) {
                $topicsToShow[] = [
                    'topic' => $topics[$post->getTopicId()],
                    'forumPrefix' => $forums[$topics[$post->getTopicId()]->getForumId()]->getPrefixes(),
                    'lastPost' => $post,
                ];
            }
        }

        if (empty($topicsToShow)) {
            $pagination->setRows(0);
        }

        $this->getView()->set('topics', $topicsToShow);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
        $this->getView()->set('pagination', $pagination);
    }
}
