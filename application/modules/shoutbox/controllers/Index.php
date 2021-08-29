<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Controllers;

use Modules\Shoutbox\Mappers\Shoutbox as ShoutboxMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $shoutboxMapper = new ShoutboxMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuShoutbox'), ['action' => 'index']);

        $this->getView()->set('shoutbox', $shoutboxMapper->getShoutbox());
    }

    /**
     * For use of ajax shoutbox
     */
    public function ajaxAction()
    {
        echo $this->getLayout()->getBox('shoutbox', 'shoutbox');
    }
}
