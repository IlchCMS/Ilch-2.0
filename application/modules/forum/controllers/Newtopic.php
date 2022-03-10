<?php
/**
 * @copyright Ilch 2
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
            $postMapper = new PostMapper;
            $dateCreated = $postMapper->getDateOfLastPostByUserId($this->getUser()->getId());
            $isExcludedFromFloodProtection = is_in_array(array_keys($this->getUser()->getGroups()), explode(',', $this->getConfig()->get('forum_excludeFloodProtection')));

            if (!$isExcludedFromFloodProtection && ($dateCreated >= date('Y-m-d H:i:s', time()-$this->getConfig()->get('forum_floodInterval')))) {
                $this->addMessage('floodError', 'danger');
                $this->redirect()
                    ->withInput()
                    ->to(['action' => 'index', 'id' => $this->getRequest()->getParam('id')]);
            } else {
                $validation = Validation::create($this->getRequest()->getPost(), [
                    'topicTitle' => 'required',
                    'text' => 'required'
                ]);

                if ($validation->isValid()) {
                    $topicMapper = new TopicMapper();
                    $dateTime = new \Ilch\Date();

                    $topicModel = new ForumTopicModel();
                    $topicModel->setTopicPrefix($this->getRequest()->getPost('topicPrefix'))
                        ->setTopicTitle($this->getRequest()->getPost('topicTitle'))
                        ->setTopicId($id)
                        ->setForumId($id)
                        ->setCat($id)
                        ->setCreatorId($this->getUser()->getId())
                        ->setType($this->getRequest()->getPost('fix'))
                        ->setDateCreated($dateTime);
                    $topicMapper->save($topicModel);

                    $lastid = $topicMapper->getLastInsertId();

                    $postModel = new ForumPostModel;
                    $postModel->setTopicId($lastid)
                        ->setUserId($this->getUser()->getId())
                        ->setText($this->getRequest()->getPost('text'))
                        ->setForumId($id)
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
        }

        $userMapper = new UserMapper();
        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('readAccess', $readAccess);
        $this->getView()->set('cat', $cat);
        $this->getView()->set('forum', $forum);
    }
}
