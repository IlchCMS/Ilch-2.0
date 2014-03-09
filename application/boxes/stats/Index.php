<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Boxes\Stats;
defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Box
{
    public function render()
    {
        $date = new \Ilch\Date();
        $userMapper = new \User\Mappers\User();
        $this->getView()->set('visitsToday', $userMapper->getVisitsCount($date->format('Y-m-d')));
        $date->modify('-1 day');
        $this->getView()->set('visitsYesterday', $userMapper->getVisitsCount($date->format('Y-m-d')));
        $this->getView()->set('visitsTotal', $userMapper->getVisitsCount());
    }
}

