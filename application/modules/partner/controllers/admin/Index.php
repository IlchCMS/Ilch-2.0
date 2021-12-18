<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Partner\Controllers\Admin;

use Modules\Partner\Mappers\Partner as PartnerMapper;
use Modules\Partner\Models\Partner as PartnerModel;
use Modules\Admin\Mappers\Notifications as NotificationsMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa fa-th-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa fa-plus-circle',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ],
            [
                'name' => 'settings',
                'active' => false,
                'icon' => 'fa fa-cogs',
                'url' => $this->getLayout()->getUrl(['controller' => 'settings', 'action' => 'index'])
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuPartner',
            $items
        );
    }

    public function indexAction()
    {
        $partnerMapper = new PartnerMapper();

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') === 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $partnerId) {
                    $partnerMapper->delete($partnerId);
                }
            }

            if ($this->getRequest()->getPost('action') === 'setfree') {
                foreach ($this->getRequest()->getPost('check_entries') as $entryId) {
                    $model = new PartnerModel();
                    $model->setId($entryId)
                        ->setFree(1);
                    $partnerMapper->save($model);
                }

                $badge = count($partnerMapper->getEntries(['setfree' => 0]));
                if ($badge > 0) {
                    $this->redirect(['action' => 'index', 'showsetfree' => 1]);
                } else {
                    $this->redirect(['action' => 'index']);
                }
            }
        } elseif ($this->getRequest()->getPost('save') && $this->getRequest()->getPost('positions')) {
            $postData = $this->getRequest()->getPost('positions');
            $positions = explode(',', $postData);

            foreach ($positions as $x => $xValue) {
                $partnerMapper->updatePositionById($xValue, $x);
            }

            $this->addMessage('saveSuccess');
            $this->redirect(['action' => 'index']);
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $entries = $partnerMapper->getEntries(['setfree' => 0]);
        } else {
            $entries = $partnerMapper->getPartnersBy(['setfree' => 1], ['pos' => 'ASC', 'id' => 'ASC']);
        }

        $badge = count($partnerMapper->getEntries(['setfree' => 0]));

        if ($badge == 0) {
            $notificationsMapper = new NotificationsMapper();
            $notificationsMapper->deleteNotificationsByType('partnerEntryAwaitingApproval');
        }

        $this->getView()->set('entries', $entries)
            ->set('badge', $badge);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $partnerMapper = new PartnerMapper();
            $partnerMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(['action' => 'index', 'showsetfree' => 1]);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function setfreeAction()
    {
        if ($this->getRequest()->isSecure()) {
            $partnerMapper = new PartnerMapper();

            $model = new PartnerModel();
            $model->setId($this->getRequest()->getParam('id'))
                ->setFree(1);
            $partnerMapper->save($model);

            $this->addMessage('freeSuccess');
        }

        if ($this->getRequest()->getParam('showsetfree')) {
            $this->redirect(['action' => 'index', 'showsetfree' => 1]);
        } else {
            $this->redirect(['action' => 'index']);
        }
    }

    public function treatAction() 
    {
        $partnerMapper = new PartnerMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $this->getView()->set('partner', $partnerMapper->getPartnerById($this->getRequest()->getParam('id')));
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuPartner'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            $banner = trim($this->getRequest()->getPost('banner'));
            if (!empty($banner) && strncmp($banner, 'application', 11) === 0) {
                $banner = BASE_URL.'/'.$banner;
            }

            $post = [
                'name' => trim($this->getRequest()->getPost('name')),
                'link' => trim($this->getRequest()->getPost('link')),
                'target' => $this->getRequest()->getPost('target'),
                'banner' => $banner
            ];

            $validation = Validation::create($post, [
                'name' => 'required',
                'link' => 'required|url',
                'target' => 'numeric|min:0|max:1',
                'banner' => 'required|url'
            ]);

            $post['banner'] = trim($this->getRequest()->getPost('banner'));

            if ($validation->isValid()) {
                $model = new PartnerModel();
                if ($this->getRequest()->getParam('id')) {
                    $model->setId($this->getRequest()->getParam('id'));
                } else {
                    $model->setFree(1);
                }
                $model->setName($post['name'])
                    ->setLink($post['link'])
                    ->setTarget($post['target'])
                    ->setBanner($post['banner']);
                $partnerMapper->save($model);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                if ($this->getRequest()->getParam('id')) {
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
                } else {
                    $this->redirect()
                        ->withInput()
                        ->withErrors($validation->getErrorBag())
                        ->to(['action' => 'treat']);
                }
            }
        }
    }
}
