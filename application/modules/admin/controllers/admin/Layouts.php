<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
defined('ACCESS') or die('no direct access');

class Layouts extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->addMenu
        (
            'Layouts',
            array
            (
                array
                (
                    'name' => 'list',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->url(array('controller' => 'layouts', 'action' => 'index'))
                ),
            )
        );
    }

    public function indexAction()
    {
        $layouts = array();

        foreach (glob(APPLICATION_PATH.'/layouts/*') as $layoutPath) {
            $model = new \Admin\Models\Layout();
            $model->setKey(basename($layoutPath));
            include_once $layoutPath.'/config/config.php';
            $model->setAuthor($config['author']);
            $model->setDesc($config['desc']);
            $layouts[] = $model;
        }

        $this->getView()->set('defaultLayout', $this->getConfig()->get('default_layout'));
        $this->getView()->set('layouts', $layouts);
    }
    
    public function defaultAction()
    {
        $this->getConfig()->set('default_layout', $this->getRequest()->getParam('key'));
        $this->redirect(array('action' => 'index'));
    }
    
    public function deleteAction()
    {
        if ($this->getConfig()->get('default_layout') == $this->getRequest()->getParam('key')) {
            $this->addMessage('cantDeleteDefaultLayout');
        } else {
            $this->rmDir(APPLICATION_PATH.'/layouts/'.$this->getRequest()->getParam('key'));
            $this->addMessage('deleteSuccess');
        }

        $this->redirect(array('action' => 'index'));
    }
    
    /**
     * Delete directory recursive.
     *
     * @param string $dir
     */
    function rmDir($dir)
    {
        if (is_dir($dir)) {
            $dircontent = scandir($dir);

            foreach ($dircontent as $c) {
                if ($c != '.' && $c != '..' && is_dir($dir.'/'.$c)) {
                    $this->rmDir($dir.'/'.$c);
                } else if ($c != '.' && $c != '..') {
                    unlink($dir.'/'.$c);
                }
            }

            rmdir($dir);
        } else {
            unlink($dir);
        }
    }
}
