<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Calendar\Controllers\Admin;

use Modules\Calendar\Mappers\Calendar as CalendarMapper;
use Modules\Calendar\Models\Calendar as CalendarModel;
use Modules\User\Mappers\Group as GroupMapper;
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
            ]
        ];

        if ($this->getRequest()->getActionName() == 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu(
            'menuCalendar',
            $items
        );
    }

    public function indexAction()
    {
        $calendarMapper = new CalendarMapper();

        $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries')) {
            if ($this->getRequest()->getPost('action') == 'delete') {
                foreach ($this->getRequest()->getPost('check_entries') as $calendarId) {
                    $calendarMapper->delete($calendarId);
                }
                $this->redirect()
                    ->withMessage('deleteSuccess')
                    ->to(['action' => 'index']);
            }
        }

        $this->getView()->set('calendar', $calendarMapper->getEntries())
            ->set('calendarMapper', $calendarMapper);
    }

    public function treatAction()
    {
        $calendarMapper = new CalendarMapper();
        $calendarModel = new CalendarModel();
        $groupMapper = new GroupMapper();
        
        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $calendarModel = $calendarMapper->getCalendarById($this->getRequest()->getParam('id'));
        } else {
            $this->getLayout()->getAdminHmenu()
                    ->add($this->getTranslator()->trans('menuCalendar'), ['action' => 'index'])
                    ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            Validation::setCustomFieldAliases([
                'periodDay' => 'periodEntry',
                'periodDays' => 'periodEntry',
                'periodType' => 'periodEntry',
            ]);

            $validator = [
                'title' => 'required',
                'start' => 'required|date:d.m.Y H\:i',
                'color' => 'required',
            ];

            if ($this->getRequest()->getPost('periodType') == 'days') {
                $_POST['periodDay'] = $this->getRequest()->getPost('periodDays');
                $validator['periodDay'] = 'required|numeric|min:1|max:7';
            } elseif ($this->getRequest()->getPost('periodType') != '') {
                $validator['periodDay'] = 'required|numeric|min:1';
            }

            if ($this->getRequest()->getPost('end')) {
                $validator['end']= 'required|date:d.m.Y H\:i';
            }

            $validation = Validation::create(
                $this->getRequest()->getPost(),
                $validator
            );

            if ($validation->isValid()) {
                $groups = '';
                if (!empty($this->getRequest()->getPost('groups'))) {
                    if (in_array('all', $this->getRequest()->getPost('groups'))) {
                        $groups = 'all';
                    } else {
                        $groups = implode(',', $this->getRequest()->getPost('groups'));
                    }
                }

                $calendarModel->setUid(($this->getRequest()->getParam('id')) ? $calendarModel->getUid() : generateUUID())
                    ->setTitle($this->getRequest()->getPost('title'))
                    ->setPlace($this->getRequest()->getPost('place'))
                    ->setStart(new \Ilch\Date($this->getRequest()->getPost('start')))
                    ->setEnd($this->getRequest()->getPost('end') ? new \Ilch\Date($this->getRequest()->getPost('end')) : '1000-01-01 00:00:00')
                    ->setText($this->getRequest()->getPost('text'))
                    ->setColor($this->getRequest()->getPost('color'))
                    ->setPeriodDay($this->getRequest()->getPost('periodDay'))
                    ->setPeriodType($this->getRequest()->getPost('periodType'))
                    ->setRepeatUntil($this->getRequest()->getPost('repeatUntil') ? new \Ilch\Date($this->getRequest()->getPost('repeatUntil')) : '1000-01-01 00:00:00')
                    ->setReadAccess($groups);
                $calendarMapper->save($calendarModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(array_merge(['action' => 'treat'], ($calendarModel->getId()?['id' => $calendarModel->getId()]:[])));
        }

        if ($calendarModel->getId()) {
            $groups = explode(',', $calendarModel->getReadAccess());
        } else {
            $groups = [1, 2, 3];
        }

        $this->getView()->set('calendar', $calendarModel)
            ->set('userGroupList', $groupMapper->getGroupList())
            ->set('groups', $groups);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $calendarMapper = new CalendarMapper();
            $calendarMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
