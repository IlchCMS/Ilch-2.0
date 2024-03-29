<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Ilch\Controller\Frontend;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\TrackRead;
use Modules\User\Mappers\User as UserMapper;
use Modules\Statistic\Mappers\Statistic as StatisticMapper;
use Modules\Forum\Mappers\ForumStatistics as ForumStaticsMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Modules\Forum\Mappers\GroupRanking as GroupRankingMapper;
use Ilch\Layout\Helper\LinkTag\Model as LinkTagModel;

class Index extends Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $forumMapper = new ForumMapper();
        $visitMapper = new StatisticMapper();
        $staticsMapper = new ForumStaticsMapper();
        $groupMapper = new GroupMapper();
        $groupRankingMapper = new GroupRankingMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('forum'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('forum'), ['action' => 'index']);

        $whoWasOnlineUsers = $visitMapper->getWhoWasOnline();
        $allOnlineUsers = $visitMapper->getVisitsCountOnline();
        $usersOnline = $visitMapper->getVisitsOnlineUser();
        $forumItems = $forumMapper->getForumItemsByParentIdsUser([0], $this->getUser());

        if (!empty($this->getConfig()->get('forum_filenameGroupappearanceCSS'))) {
            $linkTagModel = new LinkTagModel();
            $linkTagModel->setRel('stylesheet')
                ->setHref($this->getLayout()->getModuleUrl('static/css/groupappearance/' . $this->getConfig()->get('forum_filenameGroupappearanceCSS')));
            $this->getLayout()->add('linkTags', 'groupappearance', $linkTagModel);
        }

        $onlineUsersHighestRankedGroup = [];
        foreach ($usersOnline as $user) {
            foreach ($groupRankingMapper->getUserGroupsSortedByRank() as $groupRank) {
                $groupRankId = $groupRank->getId();
                foreach ($user->getGroups() as $group) {
                    $groupId = $group->getId();
                    if ($groupRankId == $groupId) {
                        $onlineUsersHighestRankedGroup[$user->getId()] = $groupId;
                        break 2;
                    }
                }
            }
        }

        $forumIds = [];
        foreach ($forumItems as $forumItem) {
            $forumIds[] = $forumItem->getId();
        }

        $this->getView()->set('onlineUsersHighestRankedGroup', $onlineUsersHighestRankedGroup)
            ->set('forumItems', $forumItems)
            ->set('usersOnlineList', $usersOnline)
            ->set('usersWhoWasOnline', $whoWasOnlineUsers)
            ->set('usersOnline', \count($usersOnline))
            ->set('guestOnline', $allOnlineUsers - \count($usersOnline))
            ->set('forumStatics', $staticsMapper->getForumStatistics())
            ->set('registNewUser', $userMapper->getUserById($visitMapper->getRegistNewUser()))
            ->set('listGroups', $groupMapper->getGroupList())
            ->set('forumMapper', $forumMapper)
            ->set('containsUnreadTopics', ($this->getUser()) ? $forumMapper->getListOfForumIdsWithUnreadTopics($this->getUser()->getId(), $forumIds) : [])
            ->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'))
            ->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
    }

    public function markallasreadAction()
    {
        if ($this->getUser() && $this->getRequest()->isSecure()) {
            $forumMapper = new ForumMapper();
            $trackRead = new TrackRead();

            $adminAccess = $this->getUser()->isAdmin();
            $forumIds = [];

            foreach ($forumMapper->getForumItemsUser($this->getUser()) as $forumItem) {
                // If it is a forum and the user is either admin or has read access then the forum can be marked as read.
                if ($forumItem->getType() === 1 && $adminAccess || $forumItem->getReadAccess()) {
                    $forumIds[] = $forumItem->getId();
                }
            }

            if (!empty($forumIds)) {
                $trackRead->markForumsAsRead($this->getUser()->getId(), $forumIds);
            }
            $this->addMessage('markedAllForumsAsRead', 'info');
        } else {
            $this->addMessage('noAccessForum', 'warning');
        }

        $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
    }
}
