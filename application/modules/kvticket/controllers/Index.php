<?php
/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvticket\Controllers;

use Modules\Kvticket\Mappers\Ticket as TicketMapper;
use Modules\Kvticket\Mappers\Category as CategoryMapper;
use Modules\Kvticket\Models\Ticket as TicketModel;
use Modules\User\Mappers\User as UserMapper;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $ticketMapper = new TicketMapper();
        $userMapper = new UserMapper();
        $catMapper = new CategoryMapper();

        $this->getLayout()->header()
            ->css('static/css/ticket.css')
            ->js('static/js/ticket.js');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTickets'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTickets'), ['action' => 'index']);

        $columns = ['updated_at', 'created_at', 'title', 'creator', 'editor', 'status', 'cat'];
        $column = $this->getRequest()->getParam('column') && in_array($this->getRequest()->getParam('column'), $columns) ? $this->getRequest()->getParam('column') : $columns[0];
        $sort_order = $this->getRequest()->getParam('order') && strtolower($this->getRequest()->getParam('order')) == 'asc' ? 'ASC' : 'DESC';

        $this->getView()->set('userMapper', $userMapper)
            ->set('tickets', $ticketMapper->getTickets([], [$column => $sort_order]))
            ->set('openTickets', $ticketMapper->getTickets(['status' => '0']))
            ->set('editTickets', $ticketMapper->getTickets(['status' => '1']))
            ->set('compTickets', $ticketMapper->getTickets(['status' => '2']))
            ->set('closeTickets', $ticketMapper->getTickets(['status' => '3']))
            ->set('catMapper', $catMapper)
            ->set('sort_column', $column)
            ->set('sort_order', $sort_order);
    }

    public function showAction()
    {
        $ticketMapper = new TicketMapper();
        $userMapper = new UserMapper();
        $catMapper = new CategoryMapper();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTickets'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTickets'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('show'), ['action' => 'show']);

        $ticketId = $this->getRequest()->getParam('id');
        $checkTicket = $ticketMapper->getTicketById($ticketId);
        if ($checkTicket) {
            $this->getView()->set('userMapper', $userMapper)
                ->set('ticket', $ticketMapper->getTicketById($ticketId))
                ->set('catMapper', $catMapper);
        } else {
            $this->redirect()
                ->withMessage('errorTicket', 'danger')
                ->to(['action' => 'index']);
        }
    }

    public function newAction()
    {
        $ticketMapper = new TicketMapper();
        $catMapper = new CategoryMapper();
        $captchaNeeded = captchaNeeded();

        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTickets'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTickets'), ['action' => 'index'])
            ->add($this->getTranslator()->trans('entry'), ['action' => 'new']);

        $this->getView()->set('cats', $catMapper->getCategorys());
        $this->getView()->set('captchaNeeded', $captchaNeeded);

        if ($this->getRequest()->getPost('saveTicket')) {
            $validationRules = [
                'title' => 'required',
                'text' => 'required'
            ];

            if ($captchaNeeded) {
                $validationRules['captcha'] = 'captcha';
            }

            $validation = Validation::create($this->getRequest()->getPost(), $validationRules);

            if ($validation->isValid()) {
                $ticketModel = new TicketModel();
                $ticketModel->setTitle($this->getRequest()->getPost('title'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setCat($this->getRequest()->getPost('cat'));
                if ($this->getUser()) {
                    $ticketModel->setCreator($this->getUser()->getId());
                }
                $ticketMapper->save($ticketModel);

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'new']);
        }
    }
}
