<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Boxes;

use Ilch\Box;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;

class Forum extends Box
{
    public function render()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();
        $userMapper = new UserMapper();

        // Add group 'guest' by default
        $groupIds = [3];

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $BoxEntryLimit = (!empty($this->getConfig()->get('forum_boxForumLimit'))) ? $this->getConfig()->get('forum_boxForumLimit') : 5;
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('topics', $topicMapper->getLastActiveTopics($BoxEntryLimit));
        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
        $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }
}
