<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Shoutbox\Controllers;

use Shoutbox\Mappers\Shoutbox as ShoutboxMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuShoutbox'), array('action' => 'index'));

        $shoutboxMapper = new ShoutboxMapper();
        $shoutbox = $shoutboxMapper->getShoutbox();
        $this->getView()->set('shoutbox', $shoutbox);
    }
}


