<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Modules\Forum\Models\ForumTopic as ForumTopicModel;
use Modules\User\Mappers\User as UserMapper;

class Showposts extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $postMapper = new PostMapper();
        $topicMapper = new TopicMapper();
        $forumMapper = new ForumMapper();
        $topicModel = new ForumTopicModel;
        $pagination = new \Ilch\Pagination();
        $pagination->setPage($this->getRequest()->getParam('page'));

        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forumId = $forumMapper->getForumByTopicId($topicId);
        $forum = $forumMapper->getForumById($forumId->getId());
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $posts = $postMapper->getPostByTopicId($topicId, $pagination);
        $post = $topicMapper->getPostById($topicId);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forum').' - '.$forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('controller' => 'index','action' => 'index'))
                ->add($cat->getTitle(), array('controller' => 'showcat','action' => 'index', 'id' => $cat->getId()))
                ->add($forum->getTitle(), array('controller' => 'showtopics','action' => 'index', 'forumid' => $forumId->getId()))
                ->add($post->getTopicTitle(), array('controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId));

        $topicModel->setId($topicId);
        $topicModel->setVisits($post->getVisits() + 1);
        $topicMapper->saveVisits($topicModel);

        $userMapper = new UserMapper();
        $userId = null;
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $postMapper = new PostMapper;
            $postModel = new ForumPostModel;

            $lastPost = $topicMapper->getLastPostByTopicId($topicId);

            $lastRead = $lastPost->getRead();
            if(in_array($this->getUser()->getId(), explode('|',$lastRead)) == false) {
                $postModel->setId($lastPost->getId());
                $postModel->setRead($lastPost->getRead().'|'.$this->getUser()->getId());
                $postMapper->saveRead($postModel);
            }
        }
        $user = $userMapper->getUserById($userId);

        $ids = array(0);
        if($user){
            $ids = array();
            foreach ($user->getGroups() as $us){
                $ids[] = $us->getId();
            }
        }
        $readAccess = explode(',',implode(',', $ids));

        $this->getView()->set('post', $post);
        $this->getView()->set('posts', $posts);
        $this->getView()->set('forum', $forum);
        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('pagination', $pagination);
    }

    public function deleteAction()
    {
        $postMapper = new PostMapper();
        $topicMapper = new TopicMapper();
        $forumMapper = new ForumMapper();

        $postId = (int)$this->getRequest()->getParam('id');
        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forumId = (int)$this->getRequest()->getParam('forumid');
        $countPosts = $forumMapper->getCountPostsByTopicId($topicId);
        if ($this->getUser()) {
            if ($this->getUser()->isAdmin()) {
                $postMapper->deleteById($postId);
                if ($countPosts === '1') {
                    $topicMapper->deleteById($topicId);
                    $this->redirect(array('controller' => 'showtopics','action' => 'index','forumid' => $forumId ));
                }
                $this->redirect(array('controller' => 'showposts','action' => 'index','topicid' => $topicId, 'forumid' => $forumId ));
            }
        }
        $this->addMessage('noAccess', 'danger');
        $this->redirect(array('controller' => 'showposts','action' => 'index','topicid' => $topicId, 'forumid' => $forumId ));
    }
}
