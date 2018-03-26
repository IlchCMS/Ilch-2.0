<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Controllers;

use Modules\Forum\Mappers\Forum as ForumMapper;
use Modules\User\Mappers\User as UserMapper;

class Showcat extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $forumMapper = new ForumMapper();
        $userMapper = new UserMapper();

        $catId = (int)$this->getRequest()->getParam('id');
        $forumItems = $forumMapper->getForumItemsByParent($catId);
        $cat = $forumMapper->getForumById($catId);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('forum'))
                ->add($cat->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('forum'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('forum'), ['controller' => 'index','action' => 'index'])
                ->add($cat->getTitle(), ['controller' => 'showcat','action' => 'index', 'id' => $cat->getId()]);

        $this->getView()->set('forumItems', $forumItems);
        $this->getView()->set('forumMapper', $forumMapper);
        $this->getView()->set('cat', $cat);

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
    }
}
