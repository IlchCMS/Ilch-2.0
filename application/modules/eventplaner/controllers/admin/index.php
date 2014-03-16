<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */

namespace Eventplaner\Controllers\Admin;

defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;
use Eventplaner\Models\Eventplaner as EventModel;
use User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Admin
{

    public function init()
    {
        $this->getLayout()->addMenu
        (
            'eventplaner',
            array
            (
                array
                (
                    'name' => 'listView',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'index'))
                ),
				array
                (
                    'name' => 'calenderView',
                    'active' => true,
                    'icon' => 'fa fa-th-list',
                    'url' => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'calender'))
                ),
            )
        );

        $this->getLayout()->addMenuAction
        (
            array
            (
                'name' => 'createEvent',
                'icon' => 'fa fa-plus-circle',
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'treat'))
            )
        );
    }
	
    public function indexAction()
    {
        $eventMapper = new EventMapper();
        $this->getView()->set('eventList', $eventMapper->getEventList());
    }
	
    public function calenderAction()
    {
        $this->addMessage('comming soon', 'info');
        $this->getView();
    }
	
    public function treatAction()
    {		
        $user = new UserMapper;
        $eventMapper = new EventMapper();
        
        if($this->getRequest()->isPost()) {
            $model = new EventModel();
            
            if ($this->getRequest()->getParam('id')) {
                $model->setId($this->getRequest()->getParam('id'));
            }
            
            $status = $this->getRequest()->getPost('status');
            $start = $this->getRequest()->getPost('start');
            $ends = $this->getRequest()->getPost('ends');
            $registrations = $this->getRequest()->getPost('registrations');
            $organizer = $this->getRequest()->getPost('organizer');
            $event = $this->getRequest()->getPost('event');

            $this->arPrint($_POST);
            
            
            if($status == '') {
                $this->addMessage('missingStatus', 'danger');
            } elseif(empty($start)) {
                $this->addMessage('missingStart', 'danger');
            } elseif(empty($ends)) {
                $this->addMessage('missingEnds', 'danger');
            } elseif(empty($registrations)) {
                $this->addMessage('missingRegistrations', 'danger');
            } elseif(empty($organizer)) {
                $this->addMessage('missingOrganizer', 'danger');
            } elseif(empty($event)) {
                $this->addMessage('missingEvent', 'danger');
            } else {
                $model->setStatus($status);
                $model->setStart($start);
                $model->setEnds($ends);
                $model->setRegistrations($registrations);
                $model->setOrganizer($organizer);
                $model->setEvent($event);
                $model->setTitle($this->getRequest()->getPost('title'));
                $model->setMessage($this->getRequest()->getPost('message'));
                $eventMapper->save($model);
                
                $this->addMessage('saveSuccess');
                
                $this->redirect(array('action' => 'index'));
            }
        }
        
        
        

        $this->getView()->set('users', $user->getUserList(  ) );
        $this->getView()->set('status', $this->getStatusArray() );
        $this->getView()->set('eventNames', $eventMapper->getEventNames() );
    }
	
    public function getStatusArray()
    {
        return array(
            0 => $this->getTranslator()->trans('active'),
            1 => $this->getTranslator()->trans('closed'),
            2 => $this->getTranslator()->trans('canceled'),
            3 => $this->getTranslator()->trans('removed')
        );
    }
	
    public static function arPrint($res)
    {	
        ?>
        <br /> <br />
        <script>$(document).ready(function(){ $( "#accordion" ).accordion({heightStyle: "content"}); });</script>

        <div id="accordion">
            <?php foreach(func_get_args() as $key => $val ) : ?>
            <h3><?=$key;?></h3>
            <div>
                <pre>
                            <?php print_r( $val );?>
                </pre>
            </div>
            <?php endforeach; ?>
        </div>

        <?php
}
}
?>