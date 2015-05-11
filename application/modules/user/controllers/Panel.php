<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Controllers;

use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\Dialog as DialogMapper;
use Modules\User\Controllers\Base as BaseController;
use Ilch\Date as IlchDate;

defined('ACCESS') or die('no direct access');

class Panel extends BaseController
{
    public function indexAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getUser()->getId());

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), array('controller' => 'panel', 'action' => 'index', 'user' => $this->getUser()->getId()));

        $this->getView()->set('profil', $profil);
    }

    public function settingsAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getUser()->getId());

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), array('controller' => 'panel', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuSettings'), array('controller' => 'panel', 'action' => 'settings'));

        $this->getView()->set('profil', $profil);
    }

    public function editprofileAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getUser()->getId());
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), array('controller' => 'panel', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuSettings'), array('controller' => 'panel', 'action' => 'settings'))
                ->add($this->getTranslator()->trans('menuEditProfile'), array('controller' => 'panel', 'action' => 'editprofile'));

        $errors = array();
        if ($this->getRequest()->isPost()) {
            $email = trim($this->getRequest()->getPost('email'));
            $firstname = trim($this->getRequest()->getPost('first-name'));
            $lastname = trim($this->getRequest()->getPost('last-name'));
            $homepage = trim($this->getRequest()->getPost('homepage'));
            $city = trim($this->getRequest()->getPost('city'));
            $birthday = new \Ilch\Date(trim($this->getRequest()->getPost('birthday')));

            if (empty($email)) {
                $this->addMessage('emailEmpty');
                $this->redirect(array('action' => 'editprofile'));
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->addMessage('emailError');
                $this->redirect(array('action' => 'editprofile'));
            }
            
            if (empty($errors)) {
                    $model = new \Modules\User\Models\User();
                    $model->setId($this->getUser()->getId());
                    $model->setEmail($email);
                    $model->setFirstName($firstname);
                    $model->setLastName($lastname);
                    $model->setHomepage($homepage);
                    $model->setCity($city);
                    $model->setBirthday($birthday);

                $profilMapper->save($model);                   

                $this->redirect(array('action' => 'editprofile'));
            }
        }

        $this->getView()->set('profil', $profil);
    }

    public function avatarAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getUser()->getId());

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), array('controller' => 'panel', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuSettings'), array('controller' => 'panel', 'action' => 'settings'))
                ->add($this->getTranslator()->trans('menuAvatar'), array('controller' => 'panel', 'action' => 'avatar'));

        if ($this->getRequest()->isPost() & !empty($_FILES['avatar']['name'])) {

            $path = $this->getConfig()->get('avatar_uploadpath');
            $file = $_FILES['avatar']['name'];
            $endung = pathinfo($file, PATHINFO_EXTENSION);
            $name = pathinfo($file, PATHINFO_FILENAME);
            $avatar = $path.$name.'.'.$endung;

            $model = new \Modules\User\Models\User();
            $model->setId($this->getUser()->getId());
            $model->setAvatar($avatar);
            $profilMapper->save($model);

            if(move_uploaded_file($_FILES['avatar']['tmp_name'], $path.$name.'.'.$endung)){

            }

            $this->addMessage('successAvatar');
            $this->redirect(array('action' => 'avatar'));
        }

        $this->getView()->set('profil', $profil);
    }

    public function signatureAction()
    {
        $profilMapper = new UserMapper();
        $profil = $profilMapper->getUserById($this->getUser()->getId());
        
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuPanel'), array('controller' => 'panel', 'action' => 'index'))
                ->add($this->getTranslator()->trans('menuSettings'), array('controller' => 'panel', 'action' => 'settings'))
                ->add($this->getTranslator()->trans('menuSignature'), array('controller' => 'panel', 'action' => 'signature'));

        if ($this->getRequest()->isPost()) {            
            $model = new \Modules\User\Models\User();
            $model->setId($this->getUser()->getId());
            $model->setSignature(trim($this->getRequest()->getPost('signature')));
            $profilMapper->save($model);                   

            $this->redirect(array('action' => 'signature'));
        }

        $this->getView()->set('profil', $profil);
    }

    public function dialogAction()
    {
        $profilMapper = new UserMapper();
        $dialogMapper = new DialogMapper();

        $profil = $profilMapper->getUserById($this->getUser()->getId());
        $dialog = $dialogMapper->getDialog($this->getUser()->getId());

        $this->getView()->set('dialog', $dialog);
        $this->getView()->set('profil', $profil);
    }

    public function dialogviewmessageAction()
    {
        if ($this->getRequest()->isPost('fetch')) {
            $dialogMapper = new DialogMapper();
            $c_id = $this->getRequest()->getParam('id');
            $user = $dialogMapper->getDialogCheckByCId($c_id);

            if($this->getUser()->getId() != $user->getUserTwo()){
                $user_two = $user->getUserOne();
            } else {
                $user_two = $user->getUserTwo();
            }
            
            $this->getView()->set('inbox', $dialogMapper->getDialogMessage($c_id));
        }
    }
    
    public function dialogviewAction()
    {
        $profilMapper = new UserMapper();
        $DialogMapper = new DialogMapper();
        $ilchdate = new IlchDate;

        $profil = $profilMapper->getUserById($this->getUser()->getId());
        $c_id = $this->getRequest()->getParam('id');
        $user = $DialogMapper->getDialogCheckByCId($c_id);

        if($this->getUser()->getId() != $user->getUserTwo()){
            $user_two = $user->getUserOne();
        } else {
            $user_two = $user->getUserTwo();
        }

        

        if($this->getUser()->getId() == $user_two){
            if ($this->getRequest()->isPost()) {

                $u_id_fk = $this->getUser()->getId();
                $text = trim($this->getRequest()->getPost('text'));

                $model = new \Modules\User\Models\Dialog();
                $model->setCId($c_id);
                $model->setId($u_id_fk);
                $model->setTime($ilchdate->toDb());
                $model->setText($text);
                $DialogMapper->save($model);

                $this->redirect(array('action' => 'dialogview','id'=> $c_id));
            }

            $this->getView()->set('inbox', $DialogMapper->getDialogMessage($c_id));
            $this->getView()->set('profil', $profil);

        } else {
            $this->redirect(array('action' => 'dialog'));
        }
    }

    public function dialognewAction()
    {
        $DialogMapper = new DialogMapper();
        $ilchdate = new IlchDate;

        $user_one = $this->getUser()->getId();
        $user_two = $this->getRequest()->getParam('id');

        if($user_one != $user_two){
            $c_exist = $DialogMapper->getDialogCheck($user_one, $user_two);
            if($c_exist==0){
                $model = new \Modules\User\Models\Dialog();
                $model->setUserOne($user_one);
                $model->setUserTwo($user_two);
                $model->setTime($ilchdate->toDb());
                $DialogMapper->save($model);   

                $c_id = $DialogMapper->getDialogId($user_one);
                $this->redirect(array('action' => 'dialogview', 'id' => $c_id->getCId()));
            }

            $c_id = $DialogMapper->getDialogId($user_one);
            $this->redirect(array('action' => 'dialogview', 'id' => $c_id->getCId()));
        }
    }
}
