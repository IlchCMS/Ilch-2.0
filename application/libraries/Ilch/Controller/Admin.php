<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;
defined('ACCESS') or die('no direct access');

class Admin extends Base
{
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
        parent::__construct($layout, $view, $request, $router, $translator);

        $this->getLayout()->set('menu', array());
        $this->getLayout()->setFile('modules/admin/index');

        $moduleMapper = new \Admin\Mappers\Module();
        $this->getLayout()->set('modules', $moduleMapper->getModules());
        
        $messages = array();

        if(!empty($_SESSION['messages']))
        {
            $messages = $_SESSION['messages'];
        }

        $this->getLayout()->set('messages', $messages);
    }
 
    /**
     * Adds a flash message.
     *
     * @param string $message
     * @param string|null $type
     */
    public function addMessage($message, $type = 'success')
    {
        $_SESSION['messages'][] = array('text' => $message, 'type' => $type);
    }
}