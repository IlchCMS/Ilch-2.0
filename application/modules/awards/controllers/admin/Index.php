<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Controllers\Admin;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Mappers\Recipients as RecipientsMapper;
use Modules\Awards\Models\Recipient as RecipientModel;
use Modules\User\Mappers\User as UserMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Admin
{
    public function init()
    {
        $items = [
            [
                'name' => 'manage',
                'active' => false,
                'icon' => 'fa-solid fa-table-list',
                'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'index']),
                [
                    'name' => 'add',
                    'active' => false,
                    'icon' => 'fa-solid fa-circle-plus',
                    'url' => $this->getLayout()->getUrl(['controller' => 'index', 'action' => 'treat'])
                ]
            ]
        ];

        if ($this->getRequest()->getActionName() === 'treat') {
            $items[0][0]['active'] = true;
        } else {
            $items[0]['active'] = true;
        }

        $this->getLayout()->addMenu
        (
            'menuAwards',
            $items
        );
    }

    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $userIds = [];
        $users = [];
        $teams = [];
        $teamIds = [];

        $this->getLayout()->getAdminHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('manage'), ['action' => 'index']);

        if ($this->getRequest()->getPost('check_entries') && $this->getRequest()->getPost('action') === 'delete') {
            foreach ($this->getRequest()->getPost('check_entries') as $awardsId) {
                $awardsMapper->delete($awardsId);
            }
        }

        $awards = $awardsMapper->getAwards();
        foreach ($awards as $award) {
            foreach($award->getRecipients() as $recipient) {
                if ($recipient->getTyp() == 1) {
                    $userIds[] = $recipient->getUtId();
                } else {
                    $teamIds[] = $recipient->getUtId();
                }
            }

            if (!empty($userIds)) {
                $users[$award->getId()] = $userMapper->getUserList(['id' => $userIds]);
            }

            if ($awardsMapper->existsTable('teams') && !empty($teamIds)) {
                $teamsMapper = new TeamsMapper();
                $teams[$award->getId()] = $teamsMapper->getTeams(['id' => $teamIds]);
            }
        }

        $this->getView()->set('awards', $awards)
            ->set('users', $users)
            ->set('teams', $teams);
    }

    public function treatAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        if ($this->getRequest()->getParam('id')) {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('edit'), ['action' => 'treat']);

            $awards = $awardsMapper->getAwardsById($this->getRequest()->getParam('id'));

            if (!$awards) {
                $this->redirect()
                    ->withMessage('awardNotFound', 'danger')
                    ->to(['action' => 'index']);
            }

            $this->getView()->set('awards', $awards);
        } else {
            $this->getLayout()->getAdminHmenu()
                ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('add'), ['action' => 'treat']);
        }

        if ($this->getRequest()->isPost()) {
            // Add BASE_URL if image starts with application to get a complete URL for validation
            $image = trim($this->getRequest()->getPost('image'));
            if (!empty($image) && strncmp($image, 'application', 11) === 0) {
                $image = BASE_URL.'/'.$image;
            }

            $post = [
                'date' => trim($this->getRequest()->getPost('date')),
                'rank' => trim($this->getRequest()->getPost('rank')),
                'image' => $image,
                'utId' => $this->getRequest()->getPost('utId'),
                'event' => trim($this->getRequest()->getPost('event')),
                'page' => trim($this->getRequest()->getPost('page'))
            ];

            Validation::setCustomFieldAliases([
                'utId' => 'invalidUserTeam',
            ]);

            $validation = Validation::create($post, [
                'date'  => 'required',
                'rank'  => 'required|numeric|integer|min:1',
                'image' => 'url',
                'utId'  => 'required',
                'event' => 'required',
                'page' => 'url'
            ]);

            $post['image'] = trim($this->getRequest()->getPost('image'));

            if ($validation->isValid()) {
                $recipientMapper = new RecipientsMapper();

                $awardsModel = new AwardsModel();
                if ($this->getRequest()->getParam('id')) {
                    $awardsModel->setId($this->getRequest()->getParam('id'));
                }
                $awardsModel->setDate(new \Ilch\Date($post['date']))
                    ->setRank($post['rank'])
                    ->setImage($post['image'])
                    ->setEvent($post['event'])
                    ->setURL($post['page']);
                $idOrAffectedRows = $awardsMapper->save($awardsModel);

                $recipientModels = [];
                foreach($post['utId'] as $value) {
                    $recipientModel = new RecipientModel();
                    $recipientModel->setAwardId((!$this->getRequest()->getParam('id')) ? $idOrAffectedRows : $awardsModel->getId())
                        ->setUtId(substr($value, 2))
                        ->setTyp(substr($value, 0, 1));
                    $recipientModels[] = $recipientModel;
                }
                $recipientMapper->saveMulti($recipientModels);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }

            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'treat', 'id' => $this->getRequest()->getParam('id')]);
        }

        if ($awardsMapper->existsTable('teams')) {
            $teamsMapper = new TeamsMapper();
            $this->getView()->set('teams', $teamsMapper->getTeams());
        }

        $this->getView()->set('awardsMapper', $awardsMapper)
            ->set('users', $userMapper->getUserList(['confirmed' => 1]));
    }

    public function delAction()
    {
        if ($this->getRequest()->isSecure()) {
            $awardsMapper = new AwardsMapper();
            $awardsMapper->delete($this->getRequest()->getParam('id'));

            $this->addMessage('deleteSuccess');
        }

        $this->redirect(['action' => 'index']);
    }
}
