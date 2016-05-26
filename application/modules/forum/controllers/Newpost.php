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

class Newpost extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $forumMapper = new ForumMapper();
        $topicMapper = new TopicMapper();

        $topicId = (int)$this->getRequest()->getParam('topicid');
        $forum = $forumMapper->getForumByTopicId($topicId);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());
        $post = $topicMapper->getPostById($topicId);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forum').' - '.$forum->getTitle());
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $forum->getId()])
                ->add($post->getTopicTitle(), ['controller' => 'showposts', 'action' => 'index', 'topicid' => $topicId])
                ->add($this->getTranslator()->trans('newPost'), ['controller' => 'newpost','action' => 'index', 'topicid' => $topicId]);

        if ($this->getRequest()->getPost('saveNewPost')) {
            $postMapper = new PostMapper;
            $postModel = new ForumPostModel;
            $dateTime = new \Ilch\Date();
            $postModel->setTopicId($topicId);
            $postModel->setUserId($this->getUser()->getId());
            $postModel->setText($this->getRequest()->getPost('text'));
            $postModel->setForumId($forum->getId());
            $postModel->setDateCreated($dateTime);
            $postMapper->save($postModel);

            $lastPost = $forumMapper->getLastPostByTopicId($forum->getId());
            $this->redirect(['controller' => 'showposts','action' => 'index','topicid' => $lastPost->getTopicId(), 'page' => $lastPost->getPage()]);
        }
    }
}
