<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Ilch\Validation;

class Newpost extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();

        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forum = $forumMapper->getForumByTopicId($topicId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $topic = $topicMapper->getPostById($topicId);
        $post = $topicMapper->getPostById($topicId);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle())
                ->add($topic->getTopicTitle())
                ->add($this->getTranslator()->trans('newPost'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                ->add($topic->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                ->add($this->getTranslator()->trans('newPost'), ['controller' => 'newpost','action' => 'index', 'topicid' => $topicId]);

        if ($this->getRequest()->getPost('saveNewPost')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'text' => 'required'
            ]);

            if ($validation->isValid()) {
                $postMapper = new PostMapper;
                $dateTime = new \Ilch\Date();

                $postModel = new ForumPostModel;
                $postModel->setTopicId($topicId)
                    ->setUserId($this->getUser()->getId())
                    ->setText($this->getRequest()->getPost('text'))
                    ->setForumId($forum->getId())
                    ->setDateCreated($dateTime);
                $postMapper->save($postModel);

                $lastPost = $forumMapper->getLastPostByTopicId($forum->getId());

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['controller' => 'newpost', 'action' => 'index', 'topicid' => $this->getRequest()->getParam('topicid')]);
        }

        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('post', $post);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('forum', $forum);
    }
}
