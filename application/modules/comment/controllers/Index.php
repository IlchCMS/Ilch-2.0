<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Comment\Controllers;

use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {
        $locale = '';

        if ((bool)$this->getConfig()->get('multilingual_acp')) {
            if ($this->getTranslator()->getLocale() != $this->getConfig()->get('content_language')) {
                $locale = $this->getTranslator()->getLocale();
            }
        }

        $this->locale = $locale;
    }

    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuComments'), array('action' => 'index'));
        $commentMapper = new CommentMapper();

        $this->getView()->set('comments', $commentMapper->getComments($this->locale));

        if ($this->getRequest()->getPost('comment_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('article/index/show/id/'.$this->getRequest()->getParam('id_a').'/id_c/'.$this->getRequest()->getParam('id'));
            if ($this->getRequest()->getParam('id') != 1) {
                $commentModel->setFKId($this->getRequest()->getParam('id'));
            } else {
                $commentModel->setFKId(0);
            }
            $commentModel->setText($this->getRequest()->getPost('comment_comment_text'));

            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $comments = $commentMapper->getComments('comment/index/index/id/'.$this->getRequest()->getParam('id'));

        $this->getView()->set('comments', $comments);
    }
}
