<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Jobs\Boxes;

use Modules\Jobs\Mappers\Jobs as JobsMapper;

class Jobs extends \Ilch\Box
{
    public function render()
    {
        $jobsMapper = new JobsMapper();

        $this->getView()->set('jobs', $jobsMapper->getJobs(['show' => 1]));
    }
}
