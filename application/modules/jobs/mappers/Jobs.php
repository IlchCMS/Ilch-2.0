<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Jobs\Mappers;

use Modules\Jobs\Models\Jobs as JobsModel;

class Jobs extends \Ilch\Mapper
{
    /**
     * Gets the Jobs entries.
     *
     * @param array $where
     * @return JobsModel[]|array
     */
    public function getJobs($where = array())
    {
        $jobsArray = $this->db()->select('*')
            ->from('jobs')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($jobsArray)) {
            return null;
        }

        $jobs = array();

        foreach ($jobsArray as $entries) {
            $jobsModel = new JobsModel();
            $jobsModel->setId($entries['id']);
            $jobsModel->setTitle($entries['title']);
            $jobsModel->setText($entries['text']);
            $jobsModel->setEmail($entries['email']);
            $jobsModel->setShow($entries['show']);
            $jobs[] = $jobsModel;
        }

        return $jobs;
    }

    /**
     * Gets jobs.
     *
     * @param integer $id
     * @return JobsModel|null
     */
    public function getJobsById($id)
    {
        $jobsRow = $this->db()->select('*')
            ->from('jobs')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($jobsRow)) {
            return null;
        }

        $jobsModel = new JobsModel();
        $jobsModel->setId($jobsRow['id']);
        $jobsModel->setTitle($jobsRow['title']);
        $jobsModel->setText($jobsRow['text']);
        $jobsModel->setEmail($jobsRow['email']);
        $jobsModel->setShow($jobsRow['show']);

        return $jobsModel;
    }

    /**
     * Inserts or updates jobs model.
     *
     * @param JobsModel $jobs
     */
    public function save(JobsModel $jobs)
    {
        $fields = array
        (
            'title' => $jobs->getTitle(),
            'text' => $jobs->getText(),
            'email' => $jobs->getEmail(),
            'show' => $jobs->getShow(),
        );

        if ($jobs->getId()) {
            $this->db()->update('jobs')
                ->values($fields)
                ->where(array('id' => $jobs->getId()))
                ->execute();
        } else {
            $this->db()->insert('jobs')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Updates jobs with given id.
     *
     * @param integer $id
     */
    public function update($id)
    {
        $show = (int) $this->db()->select('show')
                        ->from('jobs')
                        ->where(array('id' => $id))
                        ->execute()
                        ->fetchCell();

        if ($show == 1) {
            $this->db()->update('jobs')
                ->values(array('show' => 0))
                ->where(array('id' => $id))
                ->execute();
        } else {
            $this->db()->update('jobs')
                ->values(array('show' => 1))
                ->where(array('id' => $id))
                ->execute();
        }
    }

    /**
     * Deletes jobs with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('jobs')
            ->where(array('id' => $id))
            ->execute();
    }
}
