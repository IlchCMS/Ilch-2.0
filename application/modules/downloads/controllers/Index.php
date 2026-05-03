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
use Modules\Downloads\Models\DownloadsItem;
use Modules\Downloads\Models\File as FileModel;

class Index extends Frontend
{
    public function indexAction()
    {
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();

        $downloadsItems = $downloadsMapper->getDownloadsItemsByParent(0);

        $downloadsItems = $this->checkAccess($downloadsItems);
        $subItems = [];
        foreach ($downloadsItems as $downloadItem) {
            $subItems[$downloadItem->getId()] = $downloadsMapper->getDownloadsItemsByParent($downloadItem->getId());
        }
        $subItems = $this->checkAccess($subItems);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        $this->getView()->set('downloadsItems', $downloadsItems);
        $this->getView()->set('subItems', $subItems);
        $this->getView()->set('fileMapper', $fileMapper);
    }

    /**
     *  Check if the user or guest has access to the downloadsItems.
     *
     * @param DownloadsItem[]|FileModel $downloadsItems
     * @return array
     */
    private function checkAccess($downloadsItems)
    {
        if (!($this->getUser() && $this->getUser()->isAdmin())) {
            // Check which downloadsItems should be visible for the user or guest.
            $downloadsItemsVisible = [];
            foreach ($downloadsItems ?? [] as $key => $downloadsItem) {
                if (!is_array($downloadsItem)) {
                    if (empty($downloadsItem->getAccess())) {
                        // Visible for everyone.
                        $downloadsItemsVisible[$key] = $downloadsItem;
                        continue;
                    }

                    if (is_in_array(explode(',', $downloadsItem->getAccess()) ? : [], $this->getUser() && $this->getUser()->getGroups() ?: [3])) {
                        $downloadsItemsVisible[$key] = $downloadsItem;
                    }
                } else {
                    // Subitems
                    foreach ($downloadsItem as $downloadItem) {
                        if (empty($downloadItem->getAccess())) {
                            // Visible for everyone.
                            $downloadsItemsVisible[$key][] = $downloadItem;
                            continue;
                        }

                        if (is_in_array(explode(',', $downloadItem->getAccess()) ? : [], $this->getUser() && $this->getUser()->getGroups() ?: [3])) {
                            $downloadsItemsVisible[$key][] = $downloadItem;
                        }
                    }
                }
            }

            $downloadsItems = $downloadsItemsVisible;
        }

        return $downloadsItems;
    }

    public function showAction()
    {
        $fileMapper = new FileMapper();
        $pagination = new Pagination();
        $downloadsMapper = new DownloadsMapper();

        $id = $this->getRequest()->getParam('id');
        $downloads = $downloadsMapper->getDownloadsById($id);
        $downloads = $this->checkAccess($downloads);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if (!empty($downloads)) {
            $this->getLayout()->getTitle()
                    ->add($downloads->getTitle());
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads') . ' - ' . $downloads->getDesc());
            $this->getLayout()->getHmenu()
                    ->add($downloads->getTitle(), ['action' => 'show', 'id' => $id]);
        }

        $pagination->setRowsPerPage(!$this->getConfig()->get('downloads_downloadsPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('downloads_downloadsPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('files', ($downloads) ? $fileMapper->getFilesByItemId($id, $pagination) : []);
        $this->getView()->set('pagination', $pagination);
    }

    public function showFileAction()
    {
        $downloadsMapper = new DownloadsMapper();
        $fileMapper = new FileMapper();

        $id = $this->getRequest()->getParam('id');
        $file = $fileMapper->getFileById($id);
        $file = $this->checkAccess($file);

        $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('downloads'));
        $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads'));
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuDownloadsOverview'), ['action' => 'index']);

        if (!empty($file)) {
            $download = $downloadsMapper->getDownloadsById($file->getItemId());

            $this->getLayout()->getTitle()
                    ->add($download->getTitle())
                    ->add($file->getFileTitle());
            $this->getLayout()->set('metaDescription', $this->getTranslator()->trans('downloads') . ' - ' . $file->getFileDesc());
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
                $key = 'downloads/index/showfile/id/' . $id;

                if ($this->getRequest()->getPost('fkId')) {
                    $key .= '/id_c/' . $this->getRequest()->getPost('fkId');
                }

                $comments->saveComment($key, $this->getRequest()->getPost('comment_text'), $this->getUser()->getId());
                $this->redirect(['action' => 'showFile', 'id' => $id]);
            }
            if ($this->getRequest()->getParam('commentId') && ($this->getRequest()->getParam('key') === 'up' || $this->getRequest()->getParam('key') === 'down')) {
                $commentId = $this->getRequest()->getParam('commentId');
                $comments = new Comments();

                $comments->saveVote($commentId, $this->getUser()->getId(), ($this->getRequest()->getParam('key') === 'up'));
                $this->redirect(['action' => 'showFile', 'id' => $id . '#comment_' . $commentId]);
            }
        }

        $this->getView()->set('file', ($file) ?: null);
        $this->getView()->set('commentsKey', 'downloads/index/showfile/id/' . $id);
    }
}
