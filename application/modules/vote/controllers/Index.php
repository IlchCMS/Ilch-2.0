<?php
/**
 * @copyright Ilch 2.0
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

        if ($this->getRequest()->getPost('saveVote')) {
            $resultModel = new ResultModel();
            $ipModel = new IpModel();
            $resultMapper = new ResultMapper();
            $ipMapper = new IpMapper();

            $result = $resultMapper->getResultByIdAndReply($this->getRequest()->getPost('id'), $this->getRequest()->getPost('reply'));
            $resultModel->setPollId($this->getRequest()->getPost('id'))
                ->setReply($this->getRequest()->getPost('reply'))
                ->setResult($result + 1);
            $resultMapper->saveResult($resultModel);

            if (! isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $clientIP = $_SERVER['REMOTE_ADDR'];
            } else {
                $clientIP = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }

            $userId = null;
            if ($this->getUser()) {
                $userId = $this->getUser()->getId();
            }
            $ipModel->setPollId($this->getRequest()->getPost('id'))
                ->setIP($clientIP)
                ->setUserId($userId);
            $ipMapper->saveIP($ipModel);

            $this->redirect()
                ->withMessage('saveSuccess')
                ->to(['action' => 'index']);
        }

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

        $this->getView()->set('voteMapper', $voteMapper)
            ->set('resultMapper', $resultMapper)
            ->set('ipMapper', $ipMapper)
            ->set('userMapper', $userMapper)
            ->set('vote', $voteMapper->getVotes())
            ->set('readAccess', $readAccess);
    }
}
