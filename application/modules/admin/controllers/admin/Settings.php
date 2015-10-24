<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

class Settings extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = array
        (
            array
            (
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'index'))
            ),
            array
            (
                'name' => 'menuMaintenance',
                'active' => false,
                'icon' => 'fa fa-wrench',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'maintenance'))
            ),
            array
            (
                'name' => 'menuBackup',
                'active' => false,
                'icon' => 'fa fa-download',
                'url' => $this->getLayout()->getUrl(array('controller' => 'settings', 'action' => 'backup'))
            ),
        );

        if ($this->getRequest()->getActionName() == 'backup') {
            $items[2]['active'] = true; 
        } elseif ($this->getRequest()->getActionName() == 'maintenance') {
            $items[1]['active'] = true; 
        } else {
            $items[0]['active'] = true; 
        }

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $moduleMapper = new \Modules\Admin\Mappers\Module();
        $pageMapper = new \Modules\Page\Mappers\Page();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('systemSettings'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('multilingual_acp', $this->getRequest()->getPost('multilingualAcp'));
            $this->getConfig()->set('content_language', $this->getRequest()->getPost('contentLanguage'));
            $this->getConfig()->set('description', $this->getRequest()->getPost('description'));
            $this->getConfig()->set('page_title', $this->getRequest()->getPost('pageTitle'));
            $this->getConfig()->set('start_page', $this->getRequest()->getPost('startPage'));
            $this->getConfig()->set('mod_rewrite', (int)$this->getRequest()->getPost('modRewrite'));
            $this->getConfig()->set('standardMail', $this->getRequest()->getPost('standardMail'));
            $this->getConfig()->set('timezone', $this->getRequest()->getPost('timezone'));
            $this->getConfig()->set('locale', $this->getRequest()->getPost('locale'));
            if ($this->getRequest()->getPost('navbarFixed') === '1') {
                $this->getConfig()->set('admin_layout_top_nav', 'navbar-fixed-top');
            }
            if ($this->getRequest()->getPost('navbarFixed') === '0') {
                $this->getConfig()->set('admin_layout_top_nav', '');
            }

            if ((int)$this->getRequest()->getPost('modRewrite')) {
                $htaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase %1$s/
    RewriteRule ^index\.php$ - [L]
    RewriteCond %%{REQUEST_FILENAME} !-f
    RewriteCond %%{REQUEST_FILENAME} !-d
    RewriteRule . %1$s/index.php [L]
</IfModule>
HTACCESS;
                file_put_contents(APPLICATION_PATH.'/../.htaccess', sprintf($htaccess, REWRITE_BASE));
            } elseif(file_exists(APPLICATION_PATH.'/../.htaccess')) {
                file_put_contents(APPLICATION_PATH.'/../.htaccess', '');
            }

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('languages', $this->getTranslator()->getLocaleList());
        $this->getView()->set('multilingualAcp', $this->getConfig()->get('multilingual_acp'));
        $this->getView()->set('contentLanguage', $this->getConfig()->get('content_language'));
        $this->getView()->set('description', $this->getConfig()->get('description'));
        $this->getView()->set('pageTitle', $this->getConfig()->get('page_title'));
        $this->getView()->set('startPage', $this->getConfig()->get('start_page'));
        $this->getView()->set('modRewrite', $this->getConfig()->get('mod_rewrite'));
        $this->getView()->set('standardMail', $this->getConfig()->get('standardMail'));
        $this->getView()->set('timezones', \DateTimeZone::listIdentifiers());
        $this->getView()->set('timezone', $this->getConfig()->get('timezone'));
        $this->getView()->set('locale', $this->getConfig()->get('locale'));
        $this->getView()->set('modules', $moduleMapper->getModules());
        $this->getView()->set('pages', $pageMapper->getPageList());
        $this->getView()->set('navbarFixed', $this->getConfig()->get('admin_layout_top_nav'));
    }

    public function maintenanceAction()
    {
        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuMaintenance'), array('action' => 'index'));

        if ($this->getRequest()->isPost()) {
            $this->getConfig()->set('maintenance_mode', $this->getRequest()->getPost('maintenanceMode'));
            $this->getConfig()->set('maintenance_date', new \Ilch\Date(trim($this->getRequest()->getPost('maintenanceDateTime'))));
            $this->getConfig()->set('maintenance_status', $this->getRequest()->getPost('maintenanceStatus'));
            $this->getConfig()->set('maintenance_text', $this->getRequest()->getPost('maintenanceText'));

            $this->addMessage('saveSuccess');
        }

        $this->getView()->set('maintenanceMode', $this->getConfig()->get('maintenance_mode'));
        $this->getView()->set('maintenanceDate', $this->getConfig()->get('maintenance_date'));
        $this->getView()->set('maintenanceStatus', $this->getConfig()->get('maintenance_status'));
        $this->getView()->set('maintenanceText', $this->getConfig()->get('maintenance_text'));
    }

    public function backupAction()
    {
        
    }
}
