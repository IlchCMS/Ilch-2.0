<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Admin\Controllers\Admin;

use Modules\Admin\Mappers\Emails as EmailsMapper;
use Modules\Admin\Models\Emails as EmailsModel;
use Modules\Admin\Mappers\Module as ModuleMapper;
use Ilch\Validation;

class Emails extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ],
            [
                'name' => 'menuMaintenance',
                'active' => false,
                'icon' => 'fa fa-wrench',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'maintenance'])
            ],
            [
                'name' => 'menuCustomCSS',
                'active' => false,
                'icon' => 'fa fa-file-code-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'customcss'])
            ],
            [
                'name' => 'menuBackup',
                'active' => false,
                'icon' => 'fa fa-download',
                'url' => $this->getLayout()->getUrl(['controller' => 'backup', 'action' => 'index'])
            ],
            [
                'name' => 'menuUpdate',
                'active' => false,
                'icon' => 'fa fa-refresh',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'update'])
            ],
            [
                'name' => 'menuNotifications',
                'active' => false,
                'icon' => 'fa fa-envelope-o',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'notifications'])
            ],
            [
                'name' => 'menuEmails',
                'active' => true,
                'icon' => 'fa fa-envelope',
                'url' => $this->getLayout()->getUrl(['controller' => 'emails', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu
        (
            'menuSettings',
            $items
        );
    }

    public function indexAction()
    {
        $emailsMapper = new EmailsMapper();
        $moduleMapper = new ModuleMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'settings', 'action' => 'index'])
            ->add($this->getTranslator()->trans('hmenuEmails'), ['action' => 'index']);

        $this->getView()->set('emailsMapper', $emailsMapper)
            ->set('moduleMapper', $moduleMapper)
            ->set('emailModules', $emailsMapper->getEmailsModule())
            ->set('multilingual', (bool)$this->getConfig()->get('multilingual_acp'))
            ->set('contentLanguage', $this->getConfig()->get('content_language'));
    }

    public function treatAction()
    {
        $emailsMapper = new EmailsMapper();
        $moduleMapper = new ModuleMapper();

        if ($this->getRequest()->getParam('locale') == '') {
            $locale = $this->getConfig()->get('locale');
        } else {
            $locale = $this->getRequest()->getParam('locale');
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuSettings'), ['controller' => 'settings', 'action' => 'index'])
            ->add($this->getTranslator()->trans('hmenuEmails'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'key' => $this->getRequest()->getParam('key'), 'type' => $this->getRequest()->getParam('type'), 'locale' => $locale]);


        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'desc' => 'emailDesc',
                'text' => 'emailText'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'desc' => 'required',
                'text' => 'required',
            ]);

            if ($validation->isValid()) {
                $emailsModel = new EmailsModel();
                $emailsModel->setModuleKey($this->getRequest()->getParam('key'))
                    ->setType($this->getRequest()->getParam('type'))
                    ->setDesc($this->getRequest()->getPost('desc'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setLocale($locale);
                $emailsMapper->save($emailsModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'key' => $this->getRequest()->getParam('key'), 'type' => $this->getRequest()->getParam('type'), 'locale' => $this->getRequest()->getParam('locale')]);
        }

        $this->getView()->set('moduleMapper', $moduleMapper)
            ->set('emailContent', $emailsMapper->getEmail($this->getRequest()->getParam('key'), $this->getRequest()->getParam('type'), $locale));
    }
}
