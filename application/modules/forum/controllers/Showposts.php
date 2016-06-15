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
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forumId->getId()])
                ->add($post->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);

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
            if (in_array($this->getUser()->getId(), explode(',',$lastRead)) == false) {
                $postModel->setId($lastPost->getId());
                $postModel->setRead($lastPost->getRead().','.$this->getUser()->getId());
                $postMapper->saveRead($postModel);
            }
        }
        $user = $userMapper->getUserById($userId);

        $ids = [3];
        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $us) {
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
                    $this->redirect(['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forumId]);
                }

                $this->redirect(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
            }
        }

        $this->addMessage('noAccess', 'danger');
        $this->redirect(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId, 'forumid' => $forumId]);
    }

    public function editAction()
    {
        $postMapper = new PostMapper();
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();

        $postId = (int)$this->getRequest()->getParam('id');
        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forum = $forumMapper->getForumByTopicId($topicId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $topic = $topicMapper->getPostById($topicId);
        $post = $postMapper->getPostById($postId);

        if ($this->getUser()) {
            if ($this->getUser()->getId() == $post->getAutor()->getId() || $this->getUser()->isAdmin()) {
                $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forum') . ' - ' . $forum->getTitle());

                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                    ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                    ->add($topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                    ->add($this->getTranslator()->trans('editPost'), ['controller' => 'newpost', 'action' => 'index', 'topicid' => $topicId]);

                if ($this->getRequest()->getPost('editPost')) {
                    $postMapper = new PostMapper;
                    $postModel = new ForumPostModel;
                    $postModel->setId($postId);
                    $postModel->setTopicId($topicId);
                    $postModel->setText($this->getRequest()->getPost('text'));
                    $postMapper->save($postModel);

                    $this->redirect(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
                }

                $this->getView()->set('post', $postMapper->getPostById($postId));
            } else {
                $this->addMessage('noAccessForum', 'danger');
                $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
            }
        } else {
            $this->addMessage('noAccessForum', 'danger');
            $this->redirect(['module' => 'forum', 'controller' => 'index', 'action' => 'index']);
        }
    }
}
