<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Rule\Controllers;

use Modules\Rule\Mappers\Rule as RuleMapper;
use Modules\User\Mappers\User as UserMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $userMapper = new UserMapper();
        $ruleMapper = new RuleMapper();
        $this->getLayout()->getHmenu()
                ->add($this->getTranslator()->trans('menuRules'), ['action' => 'index']);

        $groupIds = [3];
        if ($this->getUser()) {
            $user = $userMapper->getUserById($this->getUser()->getId());

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $this->getView()->set('rules', $ruleMapper->getRulesItemsByParent(0, $groupIds));
        $this->getView()->set('rulesMapper', $ruleMapper);

        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('showallonstart', $this->getConfig()->get('rule_showallonstart'));
    }
}
