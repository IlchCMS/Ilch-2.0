<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Vote\Controllers;

use Modules\Vote\Mappers\Vote as VoteMapper;
use Modules\Vote\Mappers\Result as ResultMapper;
use Modules\Vote\Models\Result as ResultModel;
use Modules\Vote\Mappers\Ip as IpMapper;
use Modules\Vote\Models\Ip as IpModel;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $voteMapper = new VoteMapper();
        $resultMapper = new ResultMapper();
        $ipMapper = new IpMapper();
        $userMapper = new UserMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuVote'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuVote'), ['action' => 'index']);

        $userId = 0;
        $readAccess = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());
            if ($user) {
                $userId = $this->getUser()->getId();
                foreach ($user->getGroups() as $us) {
                    $readAccess[] = $us->getId();
                }
            }
        }

        if ($this->getRequest()->getPost('saveVote')) {
            $resultModel = new ResultModel();
            $ipModel = new IpModel();
            $resultMapper = new ResultMapper();
            $ipMapper = new IpMapper();

            foreach ($this->getRequest()->getPost('reply') as $reply) {
                $result = $resultMapper->getResultByIdAndReply($this->getRequest()->getPost('id'), $reply);
                $resultModel->setPollId($this->getRequest()->getPost('id'))
                    ->setReply($reply)
                    ->setResult($result + 1);
                $resultMapper->saveResult($resultModel);
            }

            if (!isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $clientIP = $_SERVER['REMOTE_ADDR'];
            } else {
                $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            $ipModel->setPollId($this->getRequest()->getPost('id'))
                ->setIP($clientIP)
                ->setUserId($userId);
            $ipMapper->saveIP($ipModel);

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

        $this->getView()->set('voteMapper', $voteMapper)
            ->set('resultMapper', $resultMapper)
            ->set('ipMapper', $ipMapper)
            ->set('userMapper', $userMapper)
            ->set('votes', $voteMapper->getVotes([], $readAccess))
            ->set('readAccess', $readAccess);
    }
}
