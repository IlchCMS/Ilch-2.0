<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();

        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuShoutbox'), array('action' => 'index'));

        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutbox());
    }

    /**
     * For use of ajax shoutbox
     */
    public function ajaxAction()
    {
        echo $this->getLayout()->getBox('Shoutbox', 'Shoutbox');
    }
}
