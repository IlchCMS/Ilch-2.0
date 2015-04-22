<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\History\Controllers;

use Modules\History\Mappers\History as HistoryMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuHistorys'), array('action' => 'index'));
        $historyMapper = new HistoryMapper();

        $this->getView()->set('historys', $historyMapper->getEntries());
    }
}
