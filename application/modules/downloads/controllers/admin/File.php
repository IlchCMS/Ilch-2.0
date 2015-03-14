<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Downloads\Controllers\Admin;

use Modules\Downloads\Mappers\File as FileMapper;
use Modules\Downloads\Controllers\Admin\Base as BaseController;

defined('ACCESS') or die('no direct access');

class File extends BaseController 
{
    public function init()
    {
        parent::init();
        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'menuDownloadsBack',
                'icon' => 'fa fa-arrow-left',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'downloads', 'action' => 'treatdownloads', 'id' => $this->getRequest()->getParam('downloads')))
            )
        );
    }

    public function indexAction() 
    {
        
    }

    public function treatFileAction() 
    {
        $fileMapper = new FileMapper();
        $id = (int)$this->getRequest()->getParam('id');

        if ($this->getRequest()->getPost()) {
            $fileTitle = $this->getRequest()->getPost('fileTitle');
            $fileDesc = $this->getRequest()->getPost('fileDesc');

            $model = new \Modules\Downloads\Models\File();
            $model->setId($id);
            $model->setFileTitle($fileTitle);
            $model->setFileDesc($fileDesc);
            $fileMapper->saveFileTreat($model);

            $this->addMessage('Success');
        }

        $this->getView()->set('file', $fileMapper->getFileById($id));
    }
}
