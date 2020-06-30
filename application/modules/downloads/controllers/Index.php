<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Controllers;

use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\File as FileModel;
use Modules\Comment\Mappers\Comment as CommentMapper;
use Modules\Comment\Models\Comment as CommentModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction() 
    {
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();

        $downloadsItems = $downloadsMapper->getDownloadsItemsByParent(1, 0);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

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

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if (!empty($downloads)) {
            $this->getLayout()->getTitle()
                    ->add($downloads->getTitle());
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads').' - '.$downloads->getDesc());
            $this->getLayout()->getHmenu()
                    ->add($downloads->getTitle(), ['action' => 'show', 'id' => $id]);
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('downloads_downloadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('downloads_downloadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        
        $this->getView()->set('file', $fileMapper->getFileByDownloadsId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showFileAction() 
    {
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();
        $commentMapper = new CommentMapper;

        $id = $this->getRequest()->getParam('id');
        $file = $fileMapper->getFileById($id);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if (!empty($file)) {
            $download = $downloadsMapper->getDownloadsById($file->getCat());

            $this->getLayout()->getTitle()
                    ->add($download->getTitle())
                    ->add($file->getFileTitle());
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads').' - '.$file->getFileDesc());
            $this->getLayout()->getHmenu()
                    ->add($download->getTitle(), ['action' => 'show', 'id' => $download->getId()])
                    ->add($file->getFileTitle(), ['action' => 'showfile', 'id' => $id]);

            $model = new FileModel();
            $model->setFileId($file->getFileId());
            $model->setVisits($file->getVisits() + 1);
            $fileMapper->saveVisits($model);
        }

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('downloads/index/showfile/id/'.$id.'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('downloads/index/showfile/id/'.$id);
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
            $this->redirect(['action' => 'showFile', 'id' => $id]);
        }
        if ($this->getRequest()->getParam('commentId') AND ($this->getRequest()->getParam('key') == 'up' OR $this->getRequest()->getParam('key') == 'down')) {
            $id = $this->getRequest()->getParam('id');
            $commentId = $this->getRequest()->getParam('commentId');
            $oldComment = $commentMapper->getCommentById($commentId);

            $commentModel = new CommentModel();
            $commentModel->setId($commentId);
            if ($this->getRequest()->getParam('key') == 'up') {
                $commentModel->setUp($oldComment->getUp()+1);
            } else {
                $commentModel->setDown($oldComment->getDown()+1);
            }
            $commentModel->setVoted($oldComment->getVoted().$this->getUser()->getId().',');
            $commentMapper->saveLike($commentModel);

            $this->redirect(['action' => 'showFile', 'id' => $id.'#comment_'.$commentId]);
        }

        $this->getView()->set('file', $file);
        $this->getView()->set('commentsKey', 'downloads/index/showfile/id/'.$id);
    }
}
