<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Mappers\Rank as RankMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Modules\Forum\Models\ForumTopic as ForumTopicModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Accesses as Accesses;
use Ilch\Validation;

class Showposts extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $postMapper = new PostMapper();
        $topicMapper = new TopicMapper();
        $forumMapper = new ForumMapper();
        $topicModel = new ForumTopicModel;
        $pagination = new \Ilch\Pagination();
        $rankMapper = new RankMapper();

        $pagination->setRowsPerPage(!$this->getConfig()->get('forum_postsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('forum_postsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forumId = $forumMapper->getForumByTopicId($topicId);
        $forum = $forumMapper->getForumById($forumId->getId());
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $posts = $postMapper->getPostByTopicId($topicId, $pagination);
        $post = $topicMapper->getPostById($topicId);

        $prefix = '';
        if ($forumId->getPrefix() != '' AND $post->getTopicPrefix() > 0) {
            $prefix = explode(',', $forumId->getPrefix());
            array_unshift($prefix, '');

            foreach ($prefix as $key => $value) {
                if ($post->getTopicPrefix() == $key) {
                    $value = trim($value);
                    $prefix = '['.$value.'] ';
                }
            }
        }

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle())
                ->add($prefix.$post->getTopicTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forumId->getId()])
                ->add($prefix.$post->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);

        $topicModel->setId($topicId);
        $topicModel->setVisits($post->getVisits() + 1);
        $topicMapper->saveVisits($topicModel);

        $userMapper = new UserMapper();
        $user = null;
        if ($this->getUser()) {
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
        $user = $userMapper->getUserById($this->getUser()->getId());

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('post', $post);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('posts', $posts);
        $this->getView()->set('forum', $forum);
        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('pagination', $pagination);
        $this->getView()->set('userAccess', new Accesses($this->getRequest()));
        $this->getView()->set('rankMapper', $rankMapper);
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
            if ($this->getUser()->getId() == $post->getAutor()->getId() || $this->getUser()->isAdmin() || $this->getUser()->hasAccess('module_forum')) {
                $this->getLayout()->getTitle()
                        ->add($this->getTranslator()->trans('forum'))
                        ->add($cat->getTitle())
                        ->add($forum->getTitle())
                        ->add($topic->getTopicTitle())
                        ->add($this->getTranslator()->trans('editPost'));
                $this->getLayout()->getHmenu()
                    ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                    ->add($cat->getTitle(), ['controller' => 'showcat', 'action' => 'index', 'id' => $cat->getId()])
                    ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                    ->add($topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                    ->add($this->getTranslator()->trans('editPost'), ['controller' => 'newpost', 'action' => 'index', 'topicid' => $topicId]);

                if ($this->getRequest()->getPost('editPost')) {
                    $validation = Validation::create($this->getRequest()->getPost(), [
                        'text' => 'required'
                    ]);

                    if ($validation->isValid()) {
                        $postMapper = new PostMapper;

                        $postModel = new ForumPostModel;
                        $postModel->setId($postId)
                            ->setTopicId($topicId)
                            ->setText($this->getRequest()->getPost('text'));
                        $postMapper->save($postModel);

                        $this->redirect()
                            ->withMessage('saveSuccess')
                            ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId]);
                    }

                    $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['controller' => 'showposts', 'action' => 'edit', 'id' => $postId, 'topicid' => $topicId]);
                }

                $this->getView()->set('post', $postMapper->getPostById($postId));
            } else {
                $this->redirect()
                    ->withMessage('noAccessForum', 'danger')
                    ->to(['controller' => 'index', 'action' => 'index']);
            }
        } else {
            $this->redirect()
                ->withMessage('noAccessForum', 'danger')
                ->to(['controller' => 'index', 'action' => 'index']);
        }
    }
}
