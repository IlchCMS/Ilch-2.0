<?php
/**
 * @copyright Ilch 2.0
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

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

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
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index'])
                ->add($downloads->getTitle(), ['action' => 'show', 'id' => $id]);

        $pagination->setRowsPerPage(empty($this->getConfig()->get('downloads_downloadsPerPage')) ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('downloads_downloadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));
        
        $this->getView()->set('file', $fileMapper->getFileByDownloadsId($id, $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function showFileAction() 
    {
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();
        $commentMapper = new CommentMapper;
        $userMapper = new UserMapper();
        $config = \Ilch\Registry::get('config');

        $id = $this->getRequest()->getParam('id');
        $downloadsId = $this->getRequest()->getParam('downloads');
        $downloads = $downloadsMapper->getDownloadsById($downloadsId);
        $file = $fileMapper->getFileById($id);
        $comments = $commentMapper->getCommentsByKey('downloads/index/showfile/downloads/'.$downloadsId.'/id/'.$id);

        $this->getLayout()->set('metaTitle', $this->getTranslator()->trans('downloads').' - '.$this->getTranslator()->trans('file').' - '.$file->getFileTitle());
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads').' - '.$file->getFileDesc());
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index'])
                ->add($downloads->getTitle(), ['action' => 'show', 'id' => $downloadsId])
                ->add($file->getFileTitle(), ['action' => 'showfile', 'downloads' => $downloadsId, 'id' => $id]);

        $model = new FileModel();
        $model->setFileId($file->getFileId());
        $model->setVisits($file->getVisits() + 1);
        $fileMapper->saveVisits($model);

        if ($this->getRequest()->getPost('saveComment')) {
            $date = new \Ilch\Date();
            $commentModel = new CommentModel();
            if ($this->getRequest()->getPost('fkId')) {
                $commentModel->setKey('downloads/index/showfile/downloads/'.$downloadsId.'/id/'.$id.'/id_c/'.$this->getRequest()->getPost('fkId'));
                $commentModel->setFKId($this->getRequest()->getPost('fkId'));
            } else {
                $commentModel->setKey('downloads/index/showfile/downloads/'.$downloadsId.'/id/'.$id);
            }
            $commentModel->setText($this->getRequest()->getPost('comment_text'));
            $commentModel->setDateCreated($date);
            $commentModel->setUserId($this->getUser()->getId());
            $commentMapper->save($commentModel);
        }

        $this->getView()->set('commentMapper', $commentMapper);
        $this->getView()->set('userMapper', $userMapper);
        $this->getView()->set('config', $config);
        $this->getView()->set('file', $fileMapper->getFileById($id));
        $this->getView()->set('comments', $comments);
    }
}
