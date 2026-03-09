<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Awards\Mappers;

use Ilch\Database\Exception;
use Ilch\Database\Mysql\Result;
use Ilch\Mapper;
use Modules\Awards\Models\Awards as AwardsModel;
use Modules\Awards\Models\Recipient as RecipientModel;
use InvalidArgumentException;

/**
 * The awards mapper.
 */
class Awards extends Mapper
{
    /**
     * @var string
     * @since 1.12.2
     */
    public $tablename = 'awards';
    /**
     * @var string
     * @since 1.12.2
     */
    public $tablenameRecipients = 'awards_recipients';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.12.2
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename) && $this->db()->ifTableExists($this->tablenameRecipients);
    }

    /**
     * Gets the Entries by params.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return AwardsModel[]|null
     * @since 1.12.2
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['a.id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $select = $this->db()->select();
        $select->fields(['a.id', 'a.date', 'a.rank', 'a.image', 'a.event', 'a.url'])
            ->from(['a' => $this->tablename])
            ->join(['r' => $this->tablenameRecipients], 'a.id = r.award_id', 'INNER', ['utIds' => 'GROUP_CONCAT(r.ut_id)', 'types' => 'GROUP_CONCAT(r.typ)'])
            ->where($where)
            ->group(['a.id'])
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
            $entryModel = new AwardsModel();
            $entryModel->setByArray($entries);

            $recipients = [];
            $types = explode(',', $entries['types']);
            foreach (explode(',', $entries['utIds']) as $key => $id) {
                $recipientModel = new RecipientModel();
                $recipientModel->setAwardId($entries['id'])
                    ->setUtId($id)
                    ->setTyp($types[$key]);
                $recipients[] = $recipientModel;
            }
            $entryModel->setRecipients($recipients);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Gets the Awards entries.
     *
     * @param array $where
     * @param array $orderBy
     * @return AwardsModel[]
     */
    public function getAwards(array $where = [], array $orderBy = ['a.date' => 'DESC']): array
    {
        $awardsArray = $this->getEntriesBy($where, $orderBy);

        if (empty($awardsArray)) {
            return [];
        }
        return $awardsArray;
    }

    /**
     * Gets awards.
     *
     * @param int $id
     * @return AwardsModel|null
     */
    public function getAwardsById(int $id): ?AwardsModel
    {
        $awards = $this->getAwards(['a.id' => $id], []);

        if ($awards) {
            return reset($awards);
        }
        return null;
    }

    /**
     * Inserts or updates awards model.
     *
     * @param AwardsModel $awards
     * @return int
     */
    public function save(AwardsModel $awards): int
    {
        $fields = $awards->getArray(false);

        if ($awards->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $awards->getId()])
                ->execute();

            $id = $awards->getId();
        } else {
            $id = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        $this->saveRecipientsMulti($id, $awards->getRecipients());

        return $id;
    }

    /**
     * Check if a table exists. This is used to look for the teams table of the teams module.
     *
     * @param $table
     * @return bool
     * @throws Exception
     */
    public function existsTable($table): bool
    {
        return $this->db()->ifTableExists('[prefix]_' . $table);
    }

    /**
     * Deletes awards with given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id)
    {
        return $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
    }

    /**
     * Saves a single or multiple recipients of an award at once.
     * Don't supply more than 1000 at a time.
     *
     * @param int $awardId
     * @param RecipientModel[] $recipients
     * @return int number of affected rows.
     */
    public function saveRecipientsMulti(int $awardId, array $recipients): int
    {
        if (count($recipients) > 1000) {
            throw new InvalidArgumentException('Too many recipients. There is a limit of 1000.');
        }

        $fields = [];
        foreach ($recipients as $recipient) {
            $fields[] = $recipient->getArray();
        }

        if (empty($fields)) {
            return 0;
        }

        $this->db()->delete()->from($this->tablenameRecipients)
            ->where(['award_id' => $awardId])
            ->execute();

        return $this->db()->insert($this->tablenameRecipients)
            ->columns(['award_id', 'ut_id', 'typ'])
            ->values($fields)
            ->execute();
    }

    /**
     * Deletes all entries.
     *
     * @return bool
     * @since 1.12.2
     */
    public function truncate(): bool
    {
        return  $this->db()->truncate($this->tablenameRecipients) && $this->db()->truncate($this->tablename);
    }
}
