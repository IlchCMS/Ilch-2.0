<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Boxes;

use Modules\Jobs\Mappers\Jobs as JobsMapper;

defined('ACCESS') or die('no direct access');

class Jobs extends \Ilch\Box
{
    public function render()
    {
        $jobsMapper = new JobsMapper();

        $this->getView()->set('jobs', $jobsMapper->getJobs(array('show' => 1)));
    }
}
