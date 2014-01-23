<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Admin\Controllers\Admin;
defined('ACCESS') or die('no direct access');

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $this->getLayout()->removeSidebar();
    }

    public function indexAction()
    {
        $moduleMapper = new \Admin\Mappers\Module();
        $pageMapper = new \Page\Mappers\Page();

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('maintenance_mode', $this->getRequest()->getPost('maintenanceMode'));
            $this->getConfig()->set('multilingual_acp', $this->getRequest()->getPost('multilingualAcp'));
            $this->getConfig()->set('content_language', $this->getRequest()->getPost('contentLanguage'));
            $this->getConfig()->set('page_title', $this->getRequest()->getPost('pageTitle'));
            $this->getConfig()->set('start_page', $this->getRequest()->getPost('startPage'));
            $this->getConfig()->set('mod_rewrite', (int)$this->getRequest()->getPost('modRewrite'));
            
            if ((int)$this->getRequest()->getPost('modRewrite')) {
                $htaccess = '<IfModule mod_rewrite.c>
                                RewriteEngine On
                                RewriteBase '.REWRITE_BASE.'
                                RewriteRule ^index\.php$ - [L]
                                RewriteCond %{REQUEST_FILENAME} !-f
                                RewriteCond %{REQUEST_FILENAME} !-d
                                RewriteRule . '.REWRITE_BASE.'/index.php [L]
                            </IfModule>';
                file_put_contents(APPLICATION_PATH.'/../.htaccess', $htaccess);
            } elseif(file_exists(APPLICATION_PATH.'/../.htaccess')) {
                file_put_contents(APPLICATION_PATH.'/../.htaccess', '');
            }

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('maintenanceMode', $this->getConfig()->get('maintenance_mode'));
        $this->getView()->set('multilingualAcp', $this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('pageTitle', $this->getConfig()->get('page_title'));
        $this->getView()->set('startPage', $this->getConfig()->get('start_page'));
        $this->getView()->set('modRewrite', $this->getConfig()->get('mod_rewrite'));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('pages', $pageMapper->getPageList());
    }
}
