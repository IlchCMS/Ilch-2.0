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
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuRules'), array('action' => 'index'));
        $ruleMapper = new RuleMapper();
        
        $rules = $ruleMapper->getEntries();

        $this->getView()->set('rules', $rules);
    }
}


