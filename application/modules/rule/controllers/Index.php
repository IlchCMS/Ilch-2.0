<?php
/**
 * @copyright Ilch 2.0
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

        $this->getView()->set('rules', $ruleMapper->getRulesItemsByParent(0));
        $this->getView()->set('rulesMapper', $ruleMapper);

        $userId = null;
        $groupIds = [3];
        if ($this->getUser()) {
            $userId = $this->getUser()->getId();
            $user = $userMapper->getUserById($userId);

            $groupIds = [];
            foreach ($user->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }
        $this->getView()->set('groupIdsArray', $groupIds);
        $this->getView()->set('showallonstart', $this->getConfig()->get('rule_showallonstart'));
    }
}
