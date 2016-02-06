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

        $groupIds = array(0);

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = array();
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('topicMapper', $topicMapper);
        $this->getView()->set('topics', $topicMapper->getTopics('', 5));
        $this->getView()->set('groupIdsArray', $groupIdsArray);
    }
}
