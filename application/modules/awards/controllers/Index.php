<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Controllers;

use Modules\Awards\Mappers\Awards as AwardsMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\Teams\Mappers\Teams as TeamsMapper;

class Index extends \Ilch\Controller\Frontend
{    
    public function indexAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $userIds = [];
        $users = [];
        $teams = [];
        $teamIds = [];

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'index']);

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
            ->set('awardsCount', count($awards))
            ->set('users', $users)
            ->set('teams', $teams);
    }

    public function showAction()
    {
        $awardsMapper = new AwardsMapper();
        $userMapper = new UserMapper();

        $userIds = [];
        $users = [];
        $teams = [];
        $teamIds = [];
        $award = null;

        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuAwards'), ['action' => 'show', 'id' => $this->getRequest()->getParam('id')]);

        if ($this->getRequest()->getParam('id') && is_numeric($this->getRequest()->getParam('id'))) {
            $award = $awardsMapper->getAwardsById($this->getRequest()->getParam('id'));

            if ($award) {
                foreach($award->getRecipients() as $recipient) {
                    if ($recipient->getTyp() == 1) {
                        $userIds[] = $recipient->getUtId();
                    } else {
                        $teamIds[] = $recipient->getUtId();
                    }
                }

                if (!empty($userIds)) {
                    $users = $userMapper->getUserList(['id' => $userIds]);
                }

                if ($awardsMapper->existsTable('teams') && !empty($teamIds)) {
                    $teamsMapper = new TeamsMapper();
                    $teams = $teamsMapper->getTeams(['id' => $teamIds]);
                }
            } else {
                $this->redirect()
                    ->withMessage('awardNotFound', 'danger')
                    ->to(['action' => 'index']);
            }
        } else {
            $this->redirect()
                ->withMessage('awardNotFound', 'danger')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('users', $users)
            ->set('teams', $teams)
            ->set('award', $award);
    }
}
