<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Faq\Mappers;

use Modules\Faq\Models\Faq as FaqModel;
use Modules\Faq\Mappers\Category as CategoryMapper;

class Faq extends \Ilch\Mapper
{
    /**
     * @var string
     * @since 1.9.0
     */
    public $tablename = 'faqs';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     * @throws \Ilch\Database\Exception
     * @since 1.9.0
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
     * @return FaqModel[]|null
     * @since 1.9.0
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['id' => 'ASC'], ?\Ilch\Pagination $pagination = null): ?array
    {
        $categoryMapper = new CategoryMapper();

        $read_access = '';
        if (isset($where['ra.group_id'])) {
            $read_access = $where['ra.group_id'];
            unset($where['ra.group_id']);
        }

        $select = $this->db()->select();
        $select->fields(['f.id', 'f.cat_id', 'f.question', 'f.answer'])
            ->from(['f' => $this->tablename])
            ->join(['ra' => $categoryMapper->tablenameAccess], 'f.cat_id = ra.cat_id', 'LEFT', ['read_access' => 'GROUP_CONCAT(ra.group_id)'])
            ->join(['c' => $categoryMapper->tablename], 'f.cat_id = c.id', 'LEFT', ['c.read_access_all'])
            ->where(array_merge($where, ($read_access ? [$select->orX(['ra.group_id' => $read_access, 'c.read_access_all' => '1'])] : [])))
            ->order($orderBy)
            ->group(['f.id']);

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
            $entryModel = new FaqModel();
            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Full-text search of a question.
     *
     * @param string $searchTerm
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return array|null
     */
    public function search(string $searchTerm, $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge(['f.question LIKE' => '%' . $this->db()->escape($searchTerm) . '%'], ($groupIds ? ['ra.group_id' => $groupIds] : [])), []);
    }

    /**
     * Gets faqs.
     *
     * @param array $where
     * @param array $orderBy
     * @param string|array|null $groupIds A string like '1,2,3' or an array like [1,2,3]
     * @return FaqModel[]|null
     */
    public function getFaqs(array $where = [], array $orderBy = ['f.id' => 'ASC'], $groupIds = '3'): ?array
    {
        if (\is_string($groupIds)) {
            $groupIds = explode(',', $groupIds);
        }

        return $this->getEntriesBy(array_merge($where, ($groupIds ? ['ra.group_id' => $groupIds] : [])), $orderBy);
    }

    /**
     * Gets faq by id.
     *
     * @param int $id
     * @return FaqModel|null
     */
    public function getFaqById(int $id): ?FaqModel
    {
        $entrys = $this->getEntriesBy(['f.id' => $id], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Gets faqs by catId.
     *
     * @param int $catId
     * @return FaqModel[]|null
     */
    public function getFaqsByCatId(int $catId): ?array
    {
        return $this->getEntriesBy(['f.cat_id' => $catId], []);
    }

    /**
     * Inserts or updates faq model.
     *
     * @param FaqModel $faq
     * @return int
     */
    public function save(FaqModel $faq): int
    {
        $fields = $faq->getArray();

        if ($faq->getId()) {
            $this->db()->update($this->tablename)
                ->values($fields)
                ->where(['id' => $faq->getId()])
                ->execute();
            $result = $faq->getId();
        } else {
            $result = $this->db()->insert($this->tablename)
                ->values($fields)
                ->execute();
        }

        return $result;
    }

    /**
     * Deletes faq with given id.
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
}
