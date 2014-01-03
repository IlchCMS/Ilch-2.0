<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Controller;
defined('ACCESS') or die('no direct access');

class Frontend extends Base
{
    public function __construct(\Ilch\Layout\Base $layout, \Ilch\View $view, \Ilch\Request $request, \Ilch\Router $router, \Ilch\Translator $translator)
    {
        parent::__construct($layout, $view, $request, $router, $translator);

        if (!empty($_SESSION['layout'])) {
            $this->getLayout()->setFile('layouts/'.$_SESSION['layout'].'/index');
        } else {
            $defaultLayout = $this->getConfig()->get('default_layout');
            $this->getLayout()->setFile('layouts/'.$defaultLayout.'/index');
        }
    }
}
