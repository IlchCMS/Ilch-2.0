<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Downloads\Controllers;

use Ilch\Comments;
use Ilch\Controller\Frontend;
use Ilch\Pagination;
use Modules\Downloads\Mappers\Downloads as DownloadsMapper;
use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Models\File as FileModel;

class Index extends Frontend
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
        $pagination = new Pagination();
        $downloadsMapper = new DownloadsMapper();

        $id = $this->getRequest()->getParam('id');
        $downloads = $downloadsMapper->getDownloadsById($id);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if ($downloads !== null) {
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

        $id = $this->getRequest()->getParam('id');
        $file = $fileMapper->getFileById($id);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if ($file !== null) {
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

        if ($this->getUser()) {
            if ($this->getRequest()->getPost('saveComment')) {
                $comments = new Comments();
                $key = 'downloads/index/showfile/id/'.$id;

                if ($this->getRequest()->getPost('fkId')) {
                    $key .= '/id_c/'.$this->getRequest()->getPost('fkId');
                }

                $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                $this->redirect(['action' => 'showFile', 'id' => $id]);
            }
            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'showFile', 'id' => $id.'#comment_'.$commentId]);
            }
        }

        $this->getView()->set('file', $file);
        $this->getView()->set('commentsKey', 'downloads/index/showfile/id/'.$id);
    }
}
