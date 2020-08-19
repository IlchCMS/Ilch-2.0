<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\User\Mappers\User as UserMapper;

use Modules\Forum\Models\ForumPost as ForumPostModel;

class Shownewposts extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        if ($this->getUser()) {
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $postMapper = new PostMapper();
            $pagination = new \Ilch\Pagination();
            $userMapper = new UserMapper();

            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            $this->getLayout()->getTitle()
                    ->add($this->getTranslator()->trans('forum'))
                    ->add($this->getTranslator()->trans('showNewPosts'));
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('showNewPosts'));
            $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($this->getTranslator()->trans('showNewPosts'), ['action' => 'index']);

            $this->getView()->set('forumMapper', $forumMapper);
            $this->getView()->set('topicMapper', $topicMapper);
            $this->getView()->set('postMapper', $postMapper);
            $this->getView()->set('topics', $topicMapper->getTopics());
            $this->getView()->set('groupIdsArray', $groupIds);
            $this->getView()->set('DESCPostorder', $this->getConfig()->get('forum_DESCPostorder'));
            $this->getView()->set('postsPerPage', !$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
        } else {
            $this->addMessage('noAccessForum', 'warning');
            $this->redirect(['module' => 'forum', 'controller' => 'index']);
        }
    }

    public function markallasreadAction()
    {
        if ($this->getUser()) {
            $adminAccess = $this->getUser()->isAdmin();
            
            $forumMapper = new ForumMapper();
            $topicMapper = new TopicMapper();
            $postMapper = new PostMapper();
            $userMapper = new UserMapper();
            
            $postModel = new ForumPostModel;

            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }

            foreach ($topicMapper->getTopics() as $topic) {
                $forum = $forumMapper->getForumById($topic->getForumId());
                $lastPost = $topicMapper->getLastPostByTopicId($topic->getId());
                if ($adminAccess == true || is_in_array($groupIds, explode(',', $forum->getReadAccess()))) {
                    if (!in_array($this->getUser()->getId(), explode(',', $lastPost->getRead()))) {
                        $lastRead = $lastPost->getRead();
                        if (in_array($this->getUser()->getId(), explode(',',$lastRead)) == false) {
                            $postModel->setId($lastPost->getId());
                            $postModel->setRead($lastPost->getRead().','.$this->getUser()->getId());
                            $postMapper->saveRead($postModel);
                        }
                    }
                }
            }
            $this->addMessage('allasreadForum', 'info');
            $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
        } else {
            $this->addMessage('noAccessForum', 'warning');
            $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
        }
    }
}
