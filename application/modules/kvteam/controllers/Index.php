<?php

/**
 * @copyright Kevin Veldscholten
 * @package ilch
 */

namespace Modules\Kvteam\Controllers;

use Modules\Kvteam\Mappers\Team as TeamMapper;
use Modules\User\Mappers\User as UserMapper;
use Modules\User\Mappers\ProfileFields as ProfileFieldsMapper;
use Modules\User\Mappers\ProfileFieldsContent as ProfileFieldsContentMapper;
use Modules\User\Mappers\ProfileFieldsTranslation as ProfileFieldsTranslationMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function indexAction()
    {
        $teamMapper = new TeamMapper();
        $userMapper = new UserMapper();
        $profileFieldsMapper = new ProfileFieldsMapper();
        $profileFieldsContentMapper = new ProfileFieldsContentMapper();
        $profileFieldsTranslationMapper = new ProfileFieldsTranslationMapper();

        $this->getLayout()->header()
            ->css('static/css/team.css');
        $this->getLayout()->getTitle()
            ->add($this->getTranslator()->trans('menuTeam'));
        $this->getLayout()->getHmenu()
            ->add($this->getTranslator()->trans('menuTeam'), ['action' => 'index']);

        $profileIconFields = $profileFieldsMapper->getProfileFields(['type' => 2]);
        $profileFieldsTranslation = $profileFieldsTranslationMapper->getProfileFieldTranslationByLocale($this->getTranslator()->getLocale());

        $this->getView()->set('userMapper', $userMapper)
            ->set('profileFieldsContentMapper', $profileFieldsContentMapper)
            ->set('teams', $teamMapper->getTeams())
            ->set('profileIconFields', $profileIconFields)
            ->set('profileFieldsTranslation', $profileFieldsTranslation);
    }
}
