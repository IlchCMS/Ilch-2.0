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
     * @var string
     * @since 1.7.2
     */
    public $tablename = 'jobs';

    /**
     * returns if the module is installed.
     *
     * @return bool
     * @throws \Ilch\Database\Exception
     * @since 1.7.2
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return JobsModel[]|null
     * @since 1.7.2
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'DESC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select('*')
            ->from($this->tablename)
            ->where($where)
            ->order($orderBy);

        if ($pagination !== null) {
            $select->limit($pagination->getLimit())
                ->useFoundRows();
            $result = $select->execute();
            $pagination->setRows($result->getFoundRows());
        } else {
            $result = $select->execute();
        }

        $entryArray = $result->fetchRows();
        if (empty($entryArray)) {
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new JobsModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Jobs entries.
     *
     * @param array $where
     * @return JobsModel[]|null
     */
    public function getJobs(array $where = []): ?array
    {
        return $this->getEntriesBy($where, []);
    }

    /**
     * Gets jobs.
     *
     * @param int $id
     * @return JobsModel|null
     */
    public function getJobsById(int $id): ?JobsModel
    {
        $jobsRow = $this->getEntriesBy(['id' => $id], []);

        if ($jobsRow) {
            return reset($jobsRow);
        }
        return null;
    }

    /**
     * Inserts or updates jobs model.
     *
     * @param JobsModel $jobs
     * @return int
     */
    public function save(JobsModel $jobs): int
    {
        $fields = $jobs->getArray(false);

        if ($jobs->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $jobs->getId()])
                ->execute();
            return $jobs->getId();
        } else {
            return $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Updates jobs with given id.
     *
     * @param int $id
     * @return bool
     */
    public function update(int $id): bool
    {
        $show = (int) $this->db()->select('show')
                        ->from($this->tablename)
                        ->where(['id' => $id])
                        ->execute()
                        ->fetchCell();

        if ($show == 1) {
            return $this->db()->update($this->tablename)
                ->values(['show' => 0])
                ->where(['id' => $id])
                ->execute();
        } else {
            return $this->db()->update($this->tablename)
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
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.7.2
     */
    public function truncate(): bool
    {
        return (bool)$this->db()->truncate($this->tablename);
    }
}
