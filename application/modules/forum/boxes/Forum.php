<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Boxes;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\User\Mappers\User as UserMapper;

class Forum extends \Ilch\Box
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

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('topics', $topicMapper->getTopics('', 5));
        $this->getView()->set('groupIdsArray', $groupIds);
    }
}
