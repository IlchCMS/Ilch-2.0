<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers\Admin;

use Ilch\Validation;
use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
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
                'active' => false,
                'icon' => 'fa-solid fa-gears',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() != 'reset') {
            $items[0]['active'] = true;
        } else {
            $items[1]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuShoutbox',
            $items
        );
    }

    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $userMapper = new UserMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('action') == 'delete' && $this->getRequest()->getPost('check_entries')) {
            foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                $shoutboxMapper->delete($entryId);
            }
        }

        // Search term either from the form submit (query string) or from a pagination link (path param).
        $search = $this->getRequest()->getQuery('search');
        if ($search === null) {
            $search = $this->getRequest()->getParam('search');
        }
        $search = trim((string)$search);

        $pagination->setRowsPerPage($this->getConfig()->get('shoutbox_messagesPerPageAdmincenter') ?: $this->getConfig()->get('defaultPaginationObjects'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $shoutboxEntries = $shoutboxMapper->getEntriesBy([], ['id' => 'DESC'], $pagination, $search);
        $userNames = [];

        foreach ($shoutboxMapper->getUsersOfEntries($shoutboxEntries) as $userId => $user) {
            $userNames[$userId] = $user->getName();
        }

        $this->getView()->set('dummyUserName', $userMapper->getDummyUser()->getName());
        $this->getView()->set('userNames', $userNames);
        $this->getView()->set('search', $search);
        $this->getView()->set('shoutbox', $shoutboxEntries);
        $this->getView()->set('pagination', $pagination);
    }

    /**
     * Edits a shoutbox entry. The name is only editable for guest entries.
     *
     * @since 1.8.0
     */
    public function treatAction()
    {
        $shoutboxMapper = new ShoutboxMapper();
        $entry = $shoutboxMapper->getEntryById((int)$this->getRequest()->getParam('id'));

        if ($entry === null) {
            $this->redirect()
                ->withMessage('entryNotFound', 'danger')
                ->to(['action' => 'index']);
        }

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('editEntry'), ['action' => 'treat', 'id' => $entry->getId()]);

        if ($this->getRequest()->isPost()) {
            $post = [
                'shoutbox_textarea' => trim((string)$this->getRequest()->getPost('shoutbox_textarea')),
            ];
            $validationRules = [
                'shoutbox_textarea' => 'required',
            ];

            if (!$entry->getUid()) {
                $post['shoutbox_name'] = trim((string)$this->getRequest()->getPost('shoutbox_name'));
                $validationRules['shoutbox_name'] = 'required|max:100,string';
            }

            $validation = Validation::create($post, $validationRules);

            if ($validation->isValid()) {
                $entry->setTextarea($post['shoutbox_textarea']);
                if (!$entry->getUid()) {
                    $entry->setName($post['shoutbox_name']);
                }
                $shoutboxMapper->save($entry);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $entry->getId()]);
        }

        if ($entry->getUid()) {
            $userMapper = new UserMapper();
            $user = $userMapper->getUserById($entry->getUid());
            $this->getView()->set('authorName', $user ? $user->getName() : $userMapper->getDummyUser()->getName());
        }

        $this->getView()->set('entry', $entry);
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isSecure() && !empty($this->getRequest()->getParam('id'))) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }

    public function resetAction()
    {
        if ($this->getRequest()->isSecure()) {
            $shoutboxMapper = new ShoutboxMapper();
            $shoutboxMapper->truncate();

            $this->addMessage('deleteSuccess');
            $this->redirect(['action' => 'index']);
        }
    }
}
