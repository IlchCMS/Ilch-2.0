<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Controllers;

use Modules\Jobs\Mappers\Jobs as JobsMapper;

defined('ACCESS') or die('no direct access');

class Index extends \Ilch\Controller\Frontend
{    
    public function indexAction()
    {
        $this->getLayout()->getHmenu()->add($this->getTranslator()->trans('menuJobs'), array('action' => 'index'));
        $jobsMapper = new JobsMapper();

        $this->getView()->set('jobs', $jobsMapper->getJobs(array('show' => 1)));
    }
}


