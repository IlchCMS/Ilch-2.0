<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers\Admin;

use Modules\User\Mappers\AuthProvider;

class Providers extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index'])
            ],
            [
                'name' => 'menuGroup',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'group', 'action' => 'index'])
            ],
            [
                'name' => 'menuProfileFields',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url'  => $this->getLayout()->getUrl(['controller' => 'profilefields', 'action' => 'index'])
            ],
            [
                'name' => 'menuAuthProviders',
                'active' => true,
                'icon' => 'fa-solid fa-key',
                'url'  => $this->getLayout()->getUrl(['controller' => 'providers', 'action' => 'index'])
            ],
            [
                'name' => 'menuSettings',
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url'  => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuUser',
            $items
        );
    }

    public function indexAction()
    {
        $this->getLayout()
            ->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuAuthProviders'), ['action' => 'index']);

        $authProvider = new AuthProvider();

        $this->getView()->set('providers', $authProvider->getProviders(false));
    }

    public function editAction()
    {
        $authProvider = new AuthProvider();
        $provider = $authProvider->getProvider($this->getRequest()->getParam('key'));

        if ($provider === null) {
            $this->addMessage('providerNotFound', 'danger');
            $this->redirect(['action' => 'index']);
        }

        $this->getLayout()
            ->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuAuthProviders'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('Edit'), ['action' => 'index']);

        $this->getView()->set(
            'modules',
            $authProvider->getProviderModulesByProvider($this->getRequest()->getParam('key'))
        );

        $this->getView()->set('provider', $provider);
    }

    public function saveAction()
    {
        if (! $this->getRequest()->isPost()) {
            $this->addMessage('badRequest');
            $this->redirect(['action' => 'index']);
        }

        $authProvider = new AuthProvider();
        $provider = $authProvider->getProvider($this->getRequest()->getParam('key'));

        if ($provider === null) {
            $this->addMessage('providerNotFound');
            $this->redirect(['action' => 'index']);
        }

        $module = $this->getRequest()->getPost('module');

        $valid = $authProvider->authProvidersModuleExistsForProvider($provider->getKey(), $module);

        if ($valid) {
            $update = $authProvider->updateModule($provider->getKey(), $module);

            if ($update === true) {
                $this->addMessage('moduleUpdated');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage('couldntUpdateModule', 'danger');
                $this->redirect(['action' => 'edit', 'key' => $provider->getKey()]);
            }
        } else {
            $this->addMessage('invalidModule', 'danger');
            $this->redirect(['action' => 'edit', 'key' => $provider->getKey()]);
        }
    }

    public function deleteAction()
    {
        $key = $this->getRequest()->getParam('key');

        if ($key && $this->getRequest()->isSecure()) {
            $result = (new AuthProvider())->deleteProvider($key);

            if ($result) {
                $this->addMessage('providerDeleted');
            } else {
                $this->addMessage('providerNotDeleted', 'danger');
            }

            $this->redirect(['action' => 'index']);
        }
    }
}
