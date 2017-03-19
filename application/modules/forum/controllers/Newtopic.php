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
use Ilch\Validation;

class Newtopic extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $forumMapper = new ForumMapper();
        $id = (int)$this->getRequest()->getParam('id');
        $forum = $forumMapper->getForumById($id);
        $cat = $forumMapper->getCatByParentId($forum->getParentId());

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle())
                ->add($forum->getTitle())
                ->add($this->getTranslator()->trans('newTopicTitle'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum').' - '.$forum->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index', 'action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()])
                ->add($forum->getTitle(), ['controller' => 'showtopics', 'action' => 'index', 'forumid' => $id])
                ->add($this->getTranslator()->trans('newTopicTitle'), ['controller' => 'newtopic','action' => 'index', 'id' => $id]);

        if ($this->getRequest()->getPost('saveNewTopic')) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'topicTitle' => 'required',
                'text' => 'required'
            ]);

            if ($validation->isValid()) {
                $topicMapper = new TopicMapper();
                $postMapper = new PostMapper;
                $dateTime = new \Ilch\Date();

                $topicModel = new ForumTopicModel();
                $topicModel->setTopicPrefix($this->getRequest()->getPost('topicPrefix'))
                    ->setTopicTitle($this->getRequest()->getPost('topicTitle'))
                    ->setTopicId($id)
                    ->setForumId($id)
                    ->setCat($id)
                    ->setCreatorId($this->getUser()->getId())
                    ->setType($this->getRequest()->getPost('type'))
                    ->setDateCreated($dateTime);
                $topicMapper->save($topicModel);

                $lastid = $topicMapper->getLastInsertId();

                $postModel = new ForumPostModel;
                $postModel->setTopicId($lastid)
                    ->setUserId($this->getUser()->getId())
                    ->setText($this->getRequest()->getPost('text'))
                    ->setDateCreated($dateTime);
                $postMapper->save($postModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['controller' => 'showposts', 'action' => 'index', 'topicid' => $lastid]);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index', 'id' => $this->getRequest()->getParam('id')]);
        }

        $userMapper = new UserMapper();
        $userId = null;

        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
        }

        $user = $userMapper->getUserById($userId);
        $ids = [0];

        if ($user) {
            $ids = [];
            foreach ($user->getGroups() as $us) {
                $ids[] = $us->getId();
            }
        }

        $readAccess = explode(',',implode(',', $ids));

        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('forum', $forum);
    }
}
