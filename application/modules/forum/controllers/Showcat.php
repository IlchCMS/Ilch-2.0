<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\User as UserMapper;

defined('ACCESS') or die('no direct access');

class Showcat extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $catId = (int)$this->getRequest()->getParam('id');
        
        $forumMapper = new ForumMapper();
        $forumItems = $forumMapper->getForumItemsByParent(1, $catId);
        $cat = $forumMapper->getForumById($catId);

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), array('controller' => 'index','action' => 'index'))
                ->add($cat->getTitle(), array('controller' => 'showcat','action' => 'index', 'id' => $cat->getId()));

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('forumOverview'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forumOverview'));
        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('cat', $cat);

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
    }
}
