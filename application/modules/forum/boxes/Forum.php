<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Boxes;

use Ilch\Box;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;

class Forum extends Box
{
    public function render()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();

        $boxEntryLimit = (!empty($this->getConfig()->get('forum_boxForumLimit'))) ? $this->getConfig()->get('forum_boxForumLimit') : 5;
        $lastActiveTopics = $topicMapper->getLastActiveTopics($boxEntryLimit);
        $forumIds = array_column($lastActiveTopics, 'forum_id');
        $forums = $forumMapper->getForumsByIdsUser($forumIds, $this->getUser());
        $topicIds = array_column($lastActiveTopics, 'topic_id');
        $counts = $forumMapper->getCountPostsByTopicIds($topicIds);
        $isAdmin = $this->getUser() && $this->getUser()->isAdmin();

        $topicIds = [];
        $lastActiveTopicsToShow = [];
        foreach ($lastActiveTopics as $topic) {
            if ($isAdmin || $forums[$topic['forum_id']]->getReadAccess()) {
                $topicIds[] = $topic['topic_id'];
            }
        }

        $posts = $topicMapper->getLastPostsByTopicIds($topicIds, ($this->getUser()) ? $this->getUser()->getId() : null);

        foreach ($posts as $post) {
            $foundTopic = null;
            foreach ($lastActiveTopics as $topic) {
                if ($topic['topic_id'] == $post->getTopicId()) {
                    $foundTopic = $topic;
                    break;
                }
            }

            $lastActiveTopicsToShow[] = [
                'forumId' => $foundTopic['forum_id'],
                'topicId' => $foundTopic['topic_id'],
                'topicTitle' => $foundTopic['topic_title'],
                'countPosts' => $counts[$foundTopic['topic_id']],
                'lastPost' => $post,
            ];
        }

        $this->getView()->set('lastActiveTopicsToShow', $lastActiveTopicsToShow);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }
}
