<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Controllers;

use Modules\Jobs\Mappers\Jobs as JobsMapper;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $jobsMapper = new JobsMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index']);

        $this->getView()->set('jobs', $jobsMapper->getJobs(['show' => 1]));
    }

    public function showAction()
    {
        $jobsMapper = new JobsMapper();
        $userMapper = new UserMapper();

        $id = $this->getRequest()->getParam('id');

        $job = $jobsMapper->getJobsById($id);
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                ->add($job->getTitle(), ['action' => 'show', 'id' => $id]);
           
        $post = [
            'title' => '',
            'text' => ''
        ];

        if ($this->getRequest()->getPost('saveApply')) {
            $post = [
                'title' => trim($this->getRequest()->getPost('title')),
                'text' => trim($this->getRequest()->getPost('text'))
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'text' => 'required'
            ]);

            if ($validation->isValid()) {
                $date = new \Ilch\Date();
                $job = $jobsMapper->getJobsById($id);
                $user = $userMapper->getUserById($this->getUser()->getId());

                $layout = '';
                if (!empty($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/jobs/layouts/mail/apply.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/layouts/'.$this->getConfig()->get('default_layout').'/views/modules/jobs/layouts/mail/apply.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH.'/modules/jobs/layouts/mail/apply.php');
                }

                $messageReplace = [
                    '{applyAs}' => $this->getTranslator()->trans('applyAs').' '.$post['title'],
                    '{from}' => $this->getTranslator()->trans('mailFrom'),
                    '{content}' => $post['text'],
                    '{senderMail}' => $user->getEmail(),
                    '{senderName}' => $user->getName(),
                    '{sitetitle}' => $this->getConfig()->get('page_title'),
                    '{date}' => $date->format("l, d. F Y", true),
                    '{writeBackLink}' => $this->getTranslator()->trans('mailWriteBackLink'),
                    '{reply}' => $this->getTranslator()->trans('reply'),
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($this->getConfig()->get('page_title'))
                    ->setFromEmail($this->getConfig()->get('standardMail'))
                    ->setToName('')
                    ->setToEmail($job->getEmail())
                    ->setSubject($this->getTranslator()->trans('applyAs').' '.$post['title'])
                    ->setMessage($message)
                    ->sent();

                $this->addMessage('sendSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            }
        }

        $this->getView()->set('job', $job);
        $this->getView()->set('jobs', $jobsMapper->getJobs(['show' => 1]));
    }
}
