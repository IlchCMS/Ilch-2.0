<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers;

use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\Downloads\Models\File as FileModel;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), array('action' => 'index'));
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();
        $downloadsItems = $downloadsMapper->getDownloadsItemsByParent(1, 0);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getView()->set('downloadsItems', $downloadsItems);
        $this->getView()->set('downloadsMapper', $downloadsMapper);
        $this->getView()->set('fileMapper', $fileMapper);
    }

    public function showAction() 
    {
        $fileMapper = new FileMapper();
        $pagination = new \Ilch\Pagination();
        $downloadsMapper = new DownloadsMapper();
        $id = $this->getRequest()->getParam('id');
        $downloads = $downloadsMapper->getDownloadsById($id);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('downloads').' - '.$downloads->getTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads').' - '.$downloads->getDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), array('action' => 'index'))
                ->add($downloads->getTitle(), array('action' => 'show', 'id' => $id));
        
        $pagination->setPage($this->getRequest()->getParam('page'));
        
        $this->getView()->set('file', $fileMapper->getFileByDownloadsId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showFileAction() 
    {
        $commentMapper = new CommentMapper;
        $fileMapper = new FileMapper();
        $downloadsMapper = new DownloadsMapper();

        $id = $this->getRequest()->getParam('id');
        $downloadsId = $this->getRequest()->getParam('downloads');

        if ($this->getRequest()->getPost('downloads_comment_text')) {
            $commentModel = new CommentModel();
            $commentModel->setKey('downloads/index/showfile/downloads/'.$downloadsId.'/id/'.$id);
            $commentModel->setText($this->getRequest()->getPost('downloads_comment_text'));

            $date = new \Ilch\Date();
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $downloads = $downloadsMapper->getDownloadsById($downloadsId);
        $comments = $commentMapper->getCommentsByKey('downloads/index/showfile/downloads/'.$downloadsId.'/id/'.$id);
        $file = $fileMapper->getFileById($id);

        $model = new FileModel();

        $model->setFileId($file->getFileId());
        $model->setVisits($file->getVisits() + 1);

        $fileMapper->saveVisits($model);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('downloads').' - '.$this->getTranslator()->trans('file').' - '.$file->getFileTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads').' - '.$file->getFileDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), array('action' => 'index'))
                ->add($downloads->getTitle(), array('action' => 'show', 'id' => $downloadsId))
                ->add($file->getFileTitle(), array('action' => 'showfile', 'downloads' => $downloadsId, 'id' => $id));

        $this->getView()->set('file', $fileMapper->getFileById($id));
        $this->getView()->set('comments', $comments);
    }
}
