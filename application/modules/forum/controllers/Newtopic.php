<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\Forum\Mappers\Topic as TopicMapper;
use Modules\Forum\Models\ForumTopic as ForumTopicModel;
use Modules\Forum\Mappers\Post as PostMapper;
use Modules\Forum\Models\ForumPost as ForumPostModel;
use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Newtopic extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $forumMapper = new ForumMapper();
        $id = (int)$this->getRequest()->getParam('id');
        $forum = $forumMapper->getForumById($id);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forum').' - '.$forum->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('controller' => 'index', 'action' => 'index'))
                ->add($cat->getTitle(), array('controller' => 'showcat','action' => 'index', 'id' => $cat->getId()))
                ->add($forum->getTitle(), array('controller' => 'showtopics', 'action' => 'index', 'forumid' => $id))
                ->add($this->getTranslator()->trans('newTopicTitle'), array('controller' => 'newtopic','action' => 'index', 'id' => $id));

        if ($this->getRequest()->getPost('saveNewTopic')) {
            $topicModel = new ForumTopicModel();
            $topicMapper = new TopicMapper();
            $dateTime = new \Ilch\Date();

            $topicModel->setTopicTitle($this->getRequest()->getPost('topicTitle'));
            $topicModel->setText($this->getRequest()->getPost('text'));
            $topicModel->setTopicId($id);
            $topicModel->setForumId($id);
            $topicModel->setCat($id);
            $topicModel->setCreatorId($this->getUser()->getId());
            $topicModel->setType($this->getRequest()->getPost('type'));
            $topicModel->setDateCreated($dateTime);
            $topicMapper->save($topicModel);
            
            $postMapper = new PostMapper;
            $postModel = new ForumPostModel;
            $lastid = $topicMapper->getLastInsertId();
            $postModel->setTopicId($lastid);
            $postModel->setUserId($this->getUser()->getId());
            $postModel->setText($this->getRequest()->getPost('text'));
            $postModel->setDateCreated($dateTime);
            $postMapper->save($postModel);

            $this->redirect(array('controller' => 'showposts','action' => 'index','topicid' => $lastid));
        }

        $userMapper = new UserMapper();
        $userId = null;

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
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

        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('forum', $forum);
    }
}
