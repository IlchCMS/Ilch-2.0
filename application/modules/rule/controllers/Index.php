<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Rule\Controllers;

use Modules\Rule\Mappers\Rule as RuleMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $ruleMapper = new RuleMapper();
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index']);

        $this->getView()->set('rules', $ruleMapper->getEntries());
    }
}
