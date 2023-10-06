<?php

/**
 * @copyright Ilch 2
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


        $job = $jobsMapper->getJobsById($this->getRequest()->getParam('id') ?? 0);
        if (!$job) {
            $this->redirect()
                ->to(['action' => 'index']);
        }

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuJobs'), ['action' => 'index'])
                ->add($job->getTitle(), ['action' => 'show', 'id' => $job->getId()]);

        if ($this->getRequest()->getPost('saveApply') && $this->getUser()) {
            $post = [
                'title' => trim($this->getRequest()->getPost('title')),
                'text' => trim($this->getRequest()->getPost('text'))
            ];

            $validation = Validation::create($post, [
                'title' => 'required',
                'text' => 'required'
            ]);

            if ($validation->isValid()) {
                $siteTitle = $this->getLayout()->escape($this->getConfig()->get('page_title'));
                $jobTitle = $this->getLayout()->escape($post['title']);
                $date = new \Ilch\Date();
                $user = $userMapper->getUserById($this->getUser()->getId());

                $layout = '';
                if (!empty($_SESSION['layout'])) {
                    $layout = $_SESSION['layout'];
                }

                if ($layout == $this->getConfig()->get('default_layout') && file_exists(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/jobs/layouts/mail/apply.php')) {
                    $messageTemplate = file_get_contents(APPLICATION_PATH . '/layouts/' . $this->getConfig()->get('default_layout') . '/views/modules/jobs/layouts/mail/apply.php');
                } else {
                    $messageTemplate = file_get_contents(APPLICATION_PATH . '/modules/jobs/layouts/mail/apply.php');
                }

                $messageReplace = [
                    '{applyAs}' => $this->getTranslator()->trans('applyAs') . ' ' . $jobTitle,
                    '{from}' => $this->getTranslator()->trans('mailFrom'),
                    '{content}' => $this->getLayout()->alwaysPurify($post['text']),
                    '{senderMail}' => $this->getLayout()->escape($user->getEmail()),
                    '{senderName}' => $this->getLayout()->escape($user->getName()),
                    '{sitetitle}' => $siteTitle,
                    '{date}' => $date->format('l, d. F Y', true),
                    '{writeBackLink}' => $this->getTranslator()->trans('mailWriteBackLink'),
                    '{reply}' => $this->getTranslator()->trans('reply'),
                    '{footer}' => $this->getTranslator()->trans('noReplyMailFooter')
                ];
                $message = str_replace(array_keys($messageReplace), array_values($messageReplace), $messageTemplate);

                $mail = new \Ilch\Mail();
                $mail->setFromName($siteTitle)
                    ->setFromEmail($this->getLayout()->escape($this->getConfig()->get('standardMail')))
                    ->setToName('')
                    ->setToEmail($this->getLayout()->escape($job->getEmail()))
                    ->setSubject($this->getTranslator()->trans('applyAs') . ' ' . $jobTitle)
                    ->setMessage($message)
                    ->send();

                $this->addMessage('sendSuccess');
                $this->redirect(['action' => 'index']);
            } else {
                $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
                $this->redirect()
                    ->withInput()
                    ->withErrors($validation->getErrorBag())
                    ->to(['action' => 'show', 'id' => $job->getId()]);
            }
        }

        $this->getView()->set('job', $job);
        $this->getView()->set('jobs', $jobsMapper->getJobs(['show' => 1]));
    }
}
