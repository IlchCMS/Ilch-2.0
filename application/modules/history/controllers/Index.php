<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\History\Controllers;

use Modules\History\Mappers\History as HistoryMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $historyMapper = new HistoryMapper();

        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuHistorys'), ['action' => 'index']);

        $this->getView()->set('historys', $historyMapper->getHistorysBy([], ['date' => ($this->getConfig()->get('history_desc_order') == 1?'DESC':'ASC')]));
    }
}
