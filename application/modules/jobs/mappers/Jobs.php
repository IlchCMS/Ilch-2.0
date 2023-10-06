<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Jobs\Mappers;

use Modules\Jobs\Models\Jobs as JobsModel;

/**
 * Jobs mapper
 */
class Jobs extends \Ilch\Mapper
{
    /**
     * Gets the Jobs entries.
     *
     * @param array $where
     * @return JobsModel[]|null
     */
    public function getJobs(array $where = []): ?array
    {
        $jobsArray = $this->db()->select('*')
            ->from('jobs')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($jobsArray)) {
            return null;
        }

        $jobs = [];
        foreach ($jobsArray as $entries) {
            $jobsModel = new JobsModel();
            $jobsModel->setId($entries['id'])
                ->setTitle($entries['title'])
                ->setText($entries['text'])
                ->setEmail($entries['email'])
                ->setShow($entries['show']);
            $jobs[] = $jobsModel;
        }

        return $jobs;
    }

    /**
     * Gets jobs.
     *
     * @param int $id
     * @return JobsModel|null
     */
    public function getJobsById(int $id): ?JobsModel
    {
        $jobsRow = $this->db()->select('*')
            ->from('jobs')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (empty($jobsRow)) {
            return null;
        }

        $jobsModel = new JobsModel();
        $jobsModel->setId($jobsRow['id'])
            ->setTitle($jobsRow['title'])
            ->setText($jobsRow['text'])
            ->setEmail($jobsRow['email'])
            ->setShow($jobsRow['show']);

        return $jobsModel;
    }

    /**
     * Inserts or updates jobs model.
     *
     * @param JobsModel $jobs
     * @return int
     */
    public function save(JobsModel $jobs): int
    {
        $fields = [
            'title' => $jobs->getTitle(),
            'text' => $jobs->getText(),
            'email' => $jobs->getEmail(),
            'show' => $jobs->getShow()
        ];

        if ($jobs->getId()) {
            $this->db()->update('jobs')
                ->values($fields)
                ->where(['id' => $jobs->getId()])
                ->execute();
            return $jobs->getId();
        } else {
            return $this->db()->insert('jobs')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Updates jobs with given id.
     *
     * @param int $id
     */
    public function update(int $id)
    {
        $show = (int) $this->db()->select('show')
                        ->from('jobs')
                        ->where(['id' => $id])
                        ->execute()
                        ->fetchCell();

        if ($show == 1) {
            $this->db()->update('jobs')
                ->values(['show' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            $this->db()->update('jobs')
                ->values(['show' => 1])
                ->where(['id' => $id])
                ->execute();
        }
    }

    /**
     * Deletes jobs with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->db()->delete('jobs')
            ->where(['id' => $id])
            ->execute();
    }
}
