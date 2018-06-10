<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Controllers;

class Index extends \Ilch\Controller\Frontend
{
    public function init()
    {
    }

    public function indexAction()
    {
        $this->redirect(['module' => 'privacy', 'controller' => 'index', 'action' => 'index']);
    }
}
