<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Guestbook\Controllers;

use Modules\Guestbook\Mappers\Guestbook as GuestbookMapper;
use Modules\Guestbook\Models\Entry as GuestbookModel;
use Ilch\Validation;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $pagination = new \Ilch\Pagination();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index']);

        $pagination->setRowsPerPage(!$this->getConfig()->get('gbook_entriesPerPage') ? $this->getConfig()->get('defaultPaginationObjects') : $this->getConfig()->get('gbook_entriesPerPage'));
        $pagination->setPage($this->getRequest()->getParam('page'));

        $this->getView()->set('entries', $guestbookMapper->getEntries(['setfree' => 1], $pagination));
        $this->getView()->set('pagination', $pagination);
    }

    public function newEntryAction()
    {
        $guestbookMapper = new GuestbookMapper();
        $ilchdate = new \Ilch\Date;

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('guestbook'), ['action' => 'index'])
                ->add($this->getTranslator()->trans('entry'), ['action' => 'newentry']);

        if ($this->getRequest()->getPost('saveGuestbook') and ($this->getRequest()->getPost('bot') === '')) {

            Validation::setCustomFieldAliases([
                'homepage' => 'page',
            ]);

            $validation = Validation::create($this->getRequest()->getPost(), [
                'name'      => 'required',
                'email'     => 'required|email',
                'homepage'  => 'url',
                'text'      => 'required',
                'captcha'   => 'captcha'
            ]);

            if ($validation->isValid()) {
                $model = new GuestbookModel();
                $model->setName($this->getRequest()->getPost('name'))
                    ->setEmail($this->getRequest()->getPost('email'))
                    ->setText($this->getRequest()->getPost('text'))
                    ->setHomepage($this->getRequest()->getPost('homepage'))
                    ->setDatetime($ilchdate->toDb())
                    ->setFree($this->getConfig()->get('gbook_autosetfree'));

                $guestbookMapper->save($model);

                if ($this->getConfig()->get('gbook_autosetfree') == 0) {
                    $this->addMessage('check', 'info');
                }

                $this->redirect()
                    ->withMessage('saveSuccess')
                    ->to(['action' => 'index']);
            }
            $this->addMessage($validation->getErrorBag()->getErrorMessages(), 'danger', true);
            $this->redirect()
                ->withInput()
                ->withErrors($validation->getErrorBag())
                ->to(['action' => 'newEntry']);
        }
    }
}
