<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Controllers;

use Modules\Jobs\Mappers\Jobs as JobsMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $jobsMapper = new JobsMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'));

        $this->getView()->set('jobs', $jobsMapper->getJobs(array('show' => 1)));
    }

    public function showAction()
    {
        $jobsMapper = new JobsMapper();
        $userMapper = new UserMapper();

        $id = $this->getRequest()->getParam('id');

        $job = $jobsMapper->getJobsById($id);
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'))
                ->add($job->getTitle(), array('action' => 'show', 'id' => $id));

        if ($this->getRequest()->getPost('saveApply')) {
            $title = trim($this->getRequest()->getPost('title'));
            $text = trim($this->getRequest()->getPost('text'));
            
            echo $title;

            if (empty($text)) {
                $this->addMessage('missingText', 'danger');
            } else {
                $date = new \Ilch\Date();
                $job = $jobsMapper->getJobsById($id);            
                $user = $userMapper->getUserById($this->getUser()->getId());

                if ($_SESSION['layout'] == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/jobs/layouts/mail/apply.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/jobs/layouts/mail/apply.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/jobs/layouts/mail/apply.php');
                }

                $messageReplace = array(
                        '{applyAs}' => $this->getTranslator()->trans('applyAs').' '.$title,
                        '{content}' => $text,
                        '{sitetitle}' => $this->getConfig()->get('page_title'),
                        '{date}' => $date->format("l, d. F Y", true),
                );
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setTo($job->getEmail(), '')
                        ->setSubject($this->getTranslator()->trans('applyAs').' '.$title)
                        ->setFrom($user->getEmail(), $user->getName())
                        ->setMessage($message)
                        ->addGeneralHeader('Content-type', 'text/html; charset="utf-8"');
                $mail->send();

                $this->addMessage('sendSuccess');

                $this->redirect(array('action' => 'index'));                
            }
        }

        $this->getView()->set('job', $job);
        $this->getView()->set('jobs', $jobsMapper->getJobs(array('show' => 1)));
    }
}
