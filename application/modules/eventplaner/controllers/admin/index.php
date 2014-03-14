<?php
namespace Eventplaner\Controllers\Admin;

defined('ACCESS') or die('no direct access');

use Eventplaner\Mappers\Eventplaner as EventMapper;
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
                'url'  => $this->getLayout()->getUrl(array('controller' => 'index', 'action' => 'createEvent'))
            )
        );
    }
	
    public function indexAction()
    {
		$eventMapper = new EventMapper();
		$this->getView()->set('eventList', $eventMapper->getEventList());
		//$this->arPrint( $eventMapper->getEventList() );
    }
	
	public function calenderAction()
	{
		$this->addMessage('comming soon', 'info');
		$this->getView();
	}
	
	public function createEventAction()
    {		
		$user = new UserMapper;
                $eventMapper = new EventMapper();
                
                $this->getView()->set('titleJSON', $eventMapper->getTitleJSON());
		$this->getView()->set('users', $user->getUserList(  ) );
		$this->getView()->set('status', $this->getStatusArray() );
                $this->getView()->set('eventNames', $eventMapper->getEventNames() );
    }
	
	public function getStatusArray()
	{
		return array(
			0 => $this->getTranslator()->trans('active'),
			1 => $this->getTranslator()->trans('closed'),
			2 => $this->getTranslator()->trans('canceled')
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