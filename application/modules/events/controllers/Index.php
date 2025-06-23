<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Events\Controllers;

use Modules\Events\Mappers\Events as EventMapper;
use Modules\Events\Models\Events as EventModel;
use Modules\Events\Mappers\Entrants as EntrantsMapper;
use Modules\Events\Mappers\Currency as CurrencyMapper;
use Modules\User\Mappers\Setting as SettingMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Group as GroupMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $eventMapper = new EventMapper();
        $entrantsMapper = new EntrantsMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuEvents'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuEvents'), ['controller' => 'index']);

        $upcomingLimit = (empty($this->getConfig()->get('event_upcoming_event_limit'))) ? '5' : $this->getConfig()->get('event_upcoming_event_limit');
        $currentLimit = (empty($this->getConfig()->get('event_current_event_limit'))) ? '5' : $this->getConfig()->get('event_current_event_limit');
        $pastLimit = (empty($this->getConfig()->get('event_past_event_limit'))) ? '5' : $this->getConfig()->get('event_past_event_limit');

        $user = null;
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
        }

        $readAccess = [3];
        if ($user) {
            foreach ($user->getGroups() as $us) {
                $readAccess[] = $us->getId();
            }
        }

        $this->getView()->set('entrantsMapper', $entrantsMapper)
            ->set('eventListUpcomings', $eventMapper->getEventListUpcoming($upcomingLimit))
            ->set('eventListCurrents', $eventMapper->getEventListCurrent($currentLimit))
            ->set('eventListPasts', $eventMapper->getEventListPast($pastLimit))
            ->set('readAccess', $readAccess);
    }

    public function treatAction()
    {
        $eventMapper = new EventMapper();
        $eventModel = new EventModel();
        $settingMapper = new SettingMapper();
        $currencyMapper = new CurrencyMapper();
        $userMapper = new UserMapper();
        $groupMapper = new GroupMapper();

        if ($this->getRequest()->getParam('id')) {
            $eventModel = $eventMapper->getEventById($this->getRequest()->getParam('id'));

            if (!$eventModel) {
                $this->redirect()
                    ->withMessage('entryNotFound')
                    ->to(['controller' => 'index', 'action' => 'index']);
            }

            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuEvents'))
                ->add($eventModel->getTitle())
                ->add($this->getTranslator()->trans('edit'));
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                ->add($eventModel->getTitle(), ['controller' => 'show', 'action' => 'event', 'id' => $eventModel->getId()])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat', 'id' => $eventModel->getId()]);

        } else {
            $this->getLayout()->getTitle()
                ->add($this->getTranslator()->trans('menuEvents'))
                ->add($this->getTranslator()->trans('add'));
            $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuEvents'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }
        $this->getView()->set('event', $eventModel);

        $imageAllowedFiletypes = $this->getConfig()->get('event_filetypes');
        $imageHeight = $this->getConfig()->get('event_height');
        $imageWidth = $this->getConfig()->get('event_width');
        $imageSize = $this->getConfig()->get('event_size');

        if ($this->getRequest()->isPost()) {
            $image = '';

            Validation::setCustomFieldAliases([
                'start' => 'startTime',
                'end'   => 'endTime'
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'start' => 'required',
                'title' => 'required',
                'place' => 'required',
                'website' => 'url',
                'text' => 'required',
                'userLimit' => 'numeric|min:0',
                'calendarShow' => 'numeric|min:0|max:1'
            ]);

            if ($validation->isValid()) {
                $imageError = false;

                if (!empty($_FILES['image']['name'])) {
                    $path = $this->getConfig()->get('event_uploadpath');
                    $file = $_FILES['image']['name'];
                    $file_tmpe = $_FILES['image']['tmp_name'];
                    $endung = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    $file_size = $_FILES['image']['size'];

                    if (in_array($endung, explode(' ', $imageAllowedFiletypes))) {
                        $size = getimagesize($file_tmpe);
                        $width = $size[0];
                        $height = $size[1];

                        if ($file_size <= $imageSize) {
                            $image = $path . time() . '.' . $endung;

                            if ($eventModel->getImage() != '') {
                                $eventMapper->delImageById($eventModel->getId());
                            }

                            $eventModel->setImage($image);

                            if (move_uploaded_file($file_tmpe, $image)) {
                                if ($width > $imageWidth || $height > $imageHeight) {
                                    $upload = new \Ilch\Upload();

                                    if ($upload->enoughFreeMemory($image)) {
                                        $thumb = new \Thumb\Thumbnail();
                                        $thumb -> Thumbsize = ($imageWidth <= $imageHeight) ? $imageWidth : $imageHeight;
                                        $thumb -> Square = true;
                                        $thumb -> Thumblocation = $path;
                                        $thumb -> Cropimage = [3,1,50,50,50,50];
                                        $thumb -> Createthumb($image, 'file');
                                    } else {
                                        unlink($image);
                                        $imageError = true;
                                        $this->addMessage('failedFilesize', 'warning');
                                    }
                                }
                            }
                        } else {
                            $this->addMessage('failedFilesize', 'warning');
                            $imageError = true;
                        }
                    } else {
                        $this->addMessage('failedFiletypes', 'warning');
                        $imageError = true;
                    }
                }

                if (!$imageError) {
                    if ($this->getConfig()->get('event_google_maps_api_key') != '') {
                        $eventModel->setLatLong($eventMapper->getLatLongFromAddress($this->getRequest()->getPost('place'), $this->getConfig()->get('event_google_maps_api_key')));
                    }

                    $groups = '';
                    if (!empty($this->getRequest()->getPost('groups'))) {
                        $groups = implode(',', $this->getRequest()->getPost('groups'));
                    }

                    $eventModel->setUserId($this->getRequest()->getPost('creator'))
                        ->setTitle($this->getRequest()->getPost('title'))
                        ->setStart(new \Ilch\Date($this->getRequest()->getPost('start')))
                        ->setEnd(new \Ilch\Date($this->getRequest()->getPost('end')))
                        ->setPlace($this->getRequest()->getPost('place'))
                        ->setType($this->getRequest()->getPost('type'))
                        ->setWebsite($this->getRequest()->getPost('website'))
                        ->setText($this->getRequest()->getPost('text'))
                        ->setCurrency($this->getRequest()->getPost('currency'))
                        ->setPrice($this->getRequest()->getPost('price'))
                        ->setPriceArt($this->getRequest()->getPost('priceArt'))
                        ->setShow((int)$this->getRequest()->getPost('calendarShow', 0))
                        ->setUserLimit((int)$this->getRequest()->getPost('userLimit', 0))
                        ->setReadAccess($groups);
                    $eventMapper->save($eventModel);

                    if ($this->getRequest()->getPost('image_delete') != '' && $eventModel->getId()) {
                        $eventMapper->delImageById($eventModel->getId());

                        $this->redirect()
                            ->withMessage('saveSuccess')
                            ->to(['action' => 'treat', 'id' => $eventModel->getId()]);
                    }

                    $this->redirect()
                        ->withMessage('saveSuccess')
                        ->to(array_merge(['controller' => 'index', 'action' => 'index'], $eventModel->getId() ? ['id' => $eventModel->getId()] : []));
                }
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat']);
        }

        if ($eventMapper->existsTable('calendar')) {
            $this->getView()->set('calendarShow', 1);
        }

        if ($eventModel->getId()) {
            $groups = explode(',', $eventModel->getReadAccess());
        } else {
            $groups = [2,3];
        }

        $this->getView()->set('settingMapper', $settingMapper)
            ->set('userMapper', $userMapper)
            ->set('image_height', $imageHeight)
            ->set('image_width', $imageWidth)
            ->set('image_size', $imageSize)
            ->set('image_filetypes', $imageAllowedFiletypes)
            ->set('currencies', $currencyMapper->getCurrencies())
            ->set('types', $eventMapper->getListOfTypes())
            ->set('event_google_maps_api_key', $this->getConfig()->get('event_google_maps_api_key'))
            ->set('userGroupList', $groupMapper->getGroupList())
            ->set('groups', $groups);
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure() && $this->getRequest()->getParam('id')) {
            $eventMapper = new EventMapper();

            $eventMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['controller' => 'index', 'action' => 'index']);
    }
}
