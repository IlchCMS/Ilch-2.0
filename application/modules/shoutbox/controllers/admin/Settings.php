<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Modules\Shoutbox\Libs\DesignCss;
use Modules\User\Mappers\Group as UserGroupMapper;
use Ilch\Validation;

class Settings extends \Ilch\Controller\Admin
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
                'name' => 'reset',
                'active' => false,
                'icon' => 'fa-solid fa-trash-can',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'reset'])
            ],
            [
                'name' => 'settings',
                'active' => true,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        $this->getLayout()->addMenu(
            'menuShoutbox',
            $items
        );
    }

    public function indexAction()
    {
        $userGroupMapper = new UserGroupMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShoutbox'), ['controller' => 'index', 'action' => 'index'])
            ->add($this->getTranslator()->trans('settings'), ['action' => 'index']);

        if ($this->getRequest()->isPost()) {
            $validation = Validation::create($this->getRequest()->getPost(), [
                'limit'         => 'required|integer|min:1',
                'maxtextlength' => 'required|integer|min:20',
                'messagesPerPage' => 'required|integer|min:1',
                'messagesPerPageAdmincenter' => 'required|integer|min:1',
                'floodInterval' => 'required|integer|min:0',
                'autoRefreshInterval' => 'required|integer|min:0',
                'designFontSize' => 'required|integer|min:0|max:50',
            ]);

            if ($validation->isValid()) {
                if (empty($this->getRequest()->getPost('writeAccess'))) {
                    $writeAccess = '';
                } else {
                    $writeAccess = implode(',', $this->getRequest()->getPost('writeAccess'));
                }

                // Prevent breaking out of the style block the custom CSS gets rendered into.
                $customCss = trim(str_ireplace('</style', '', (string)$this->getRequest()->getPost('customCss')));

                $this->getConfig()->set('shoutbox_limit', $this->getRequest()->getPost('limit'))
                    ->set('shoutbox_messagesPerPage', $this->getRequest()->getPost('messagesPerPage'))
                    ->set('shoutbox_messagesPerPageAdmincenter', $this->getRequest()->getPost('messagesPerPageAdmincenter'))
                    ->set('shoutbox_maxtextlength', $this->getRequest()->getPost('maxtextlength'))
                    ->set('shoutbox_floodInterval', $this->getRequest()->getPost('floodInterval'))
                    ->set('shoutbox_autoRefreshInterval', $this->getRequest()->getPost('autoRefreshInterval'))
                    ->set('shoutbox_writeaccess', $writeAccess)
                    ->set('shoutbox_designBackgroundColor', $this->getColorFromPost('designBackgroundColor'))
                    ->set('shoutbox_designTextColor', $this->getColorFromPost('designTextColor'))
                    ->set('shoutbox_designNameColor', $this->getColorFromPost('designNameColor'))
                    ->set('shoutbox_designBoxBackgroundColor', $this->getColorFromPost('designBoxBackgroundColor'))
                    ->set('shoutbox_designButtonColor', $this->getColorFromPost('designButtonColor'))
                    ->set('shoutbox_designButtonTextColor', $this->getColorFromPost('designButtonTextColor'))
                    ->set('shoutbox_designInputBackgroundColor', $this->getColorFromPost('designInputBackgroundColor'))
                    ->set('shoutbox_designInputTextColor', $this->getColorFromPost('designInputTextColor'))
                    ->set('shoutbox_designFontSize', $this->getRequest()->getPost('designFontSize'))
                    ->set('shoutbox_showAvatars', $this->getRequest()->getPost('showAvatars') ? '1' : '0')
                    ->set('shoutbox_customCss', $customCss);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'index']);
        }

        $this->getView()->set('limit', $this->getConfig()->get('shoutbox_limit'))
            ->set('messagesPerPage', $this->getConfig()->get('shoutbox_messagesPerPage'))
            ->set('messagesPerPageAdmincenter', $this->getConfig()->get('shoutbox_messagesPerPageAdmincenter'))
            ->set('maxtextlength', $this->getConfig()->get('shoutbox_maxtextlength'))
            ->set('floodInterval', $this->getConfig()->get('shoutbox_floodInterval'))
            ->set('autoRefreshInterval', $this->getConfig()->get('shoutbox_autoRefreshInterval'))
            ->set('userGroupList', $userGroupMapper->getGroupList())
            ->set('writeAccess', $this->getConfig()->get('shoutbox_writeaccess'))
            ->set('designBackgroundColor', (string)$this->getConfig()->get('shoutbox_designBackgroundColor'))
            ->set('designTextColor', (string)$this->getConfig()->get('shoutbox_designTextColor'))
            ->set('designNameColor', (string)$this->getConfig()->get('shoutbox_designNameColor'))
            ->set('designBoxBackgroundColor', (string)$this->getConfig()->get('shoutbox_designBoxBackgroundColor'))
            ->set('designButtonColor', (string)$this->getConfig()->get('shoutbox_designButtonColor'))
            ->set('designButtonTextColor', (string)$this->getConfig()->get('shoutbox_designButtonTextColor'))
            ->set('designInputBackgroundColor', (string)$this->getConfig()->get('shoutbox_designInputBackgroundColor'))
            ->set('designInputTextColor', (string)$this->getConfig()->get('shoutbox_designInputTextColor'))
            ->set('designFontSize', (int)$this->getConfig()->get('shoutbox_designFontSize'))
            ->set('showAvatars', $this->getConfig()->get('shoutbox_showAvatars') !== '0')
            ->set('customCss', (string)$this->getConfig()->get('shoutbox_customCss'));
    }

    /**
     * Gets a design color from the post data. Returns an empty string (theme default)
     * if the corresponding default checkbox is set or the color is invalid.
     *
     * @param string $field
     * @return string
     */
    private function getColorFromPost(string $field): string
    {
        if ($this->getRequest()->getPost($field . 'Default')) {
            return '';
        }

        return DesignCss::sanitizeColor((string)$this->getRequest()->getPost($field));
    }
}
