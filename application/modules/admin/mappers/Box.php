<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Box as EntriesModel;

class Box extends \Ilch\Mapper
{
    public string $tablename = 'modules_boxes_content';
    public string $tablenameSelfBox = 'boxes';
    public string $tablenameSelfBoxContent = 'boxes_content';

    /**
     * Returns whether the module is installed.
     *
     * @return bool
     */
    public function checkDBSelfBox(): bool
    {
        return $this->db()->ifTableExists($this->tablenameSelfBox) && $this->db()->ifTableExists($this->tablenameSelfBoxContent);
    }

    /**
     * Gets the entries by param.
     *
     * No GROUP BY on purpose: each (box, locale) content row is returned as its own
     * row, so same titles in different locales don't collapse into one row.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]
     */
    public function getSelfBoxEntriesBy(array $where = [], array $orderBy = ['b.id' => 'DESC'], ?\Ilch\Pagination $pagination = null): array
    {
        $select = $this->db()->select()
            ->fields(['b.id', 'b.date_created'])
            ->from(['b' => $this->tablenameSelfBox])
            ->join(['bc' => $this->tablenameSelfBoxContent], 'b.id = bc.box_id', 'LEFT', ['bc.box_id', 'bc.content', 'bc.locale', 'bc.title'])
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
            return [];
        }
        $entryModels = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entryModels[] = $entryModel;
        }
        return $entryModels;
    }

    /**
     * Gets the box lists for overview.
     *
     * @param string $locale
     * @param array $orderBy
     * @return EntriesModel[]
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxList(string $locale, array $orderBy = ['b.id' => 'DESC']): array
    {
        return $this->getSelfBoxEntriesBy(['bc.locale' => $this->db()->escape($locale)], $orderBy);
    }

    /**
     * Returns the box model found by the id and locale.
     *
     * @param int $id
     * @param string $locale
     * @return EntriesModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxByIdLocale(int $id, string $locale = ''): ?EntriesModel
    {
        $entries = $this->getSelfBoxEntriesBy(['b.id' => $id, 'bc.locale' => $this->db()->escape($locale)], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }

    /**
     * Inserts or updates a box model in the database.
     *
     * @param EntriesModel $box
     * @throws \Ilch\Database\Exception
     * @return int The id of the box, or 0 if the box does not exist in the boxes table.
     */
    public function save(EntriesModel $box): int
    {
        if ($box->getId()) {
            // Make sure the box actually exists in the boxes table, otherwise we
            // would create an orphaned boxes_content row.
            $boxExists = $this->db()->select('id')
                ->from($this->tablenameSelfBox)
                ->where(['id' => $box->getId()])
                ->execute()
                ->fetchCell();

            if (empty($boxExists)) {
                return 0;
            }

            if ($this->getSelfBoxByIdLocale($box->getId(), $box->getLocale())) {
                $this->db()->update($this->tablenameSelfBoxContent)
                    ->values(['title' => $box->getTitle(), 'content' => $box->getContent()])
                    ->where(['box_id' => $box->getId(), 'locale' => $box->getLocale()])
                    ->execute();
            } else {
                $this->db()->insert($this->tablenameSelfBoxContent)
                    ->values(['box_id' => $box->getId(), 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()])
                    ->execute();
            }
            return $box->getId();
        } else {
            $date = new \Ilch\Date();
            $boxId = $this->db()->insert($this->tablenameSelfBox)
                ->values(['date_created' => $date->toDb()])
                ->execute();

            $box->setId($boxId);

            $this->db()->insert($this->tablenameSelfBoxContent)
                ->values(['box_id' => $boxId, 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()])
                ->execute();
            return $boxId;
        }
    }

    /**
     * Deletes a box with the given id.
     *
     * @param int $id
     * @return bool True if a box row was deleted.
     */
    public function delete(int $id): bool
    {
        $deleted = $this->db()->delete($this->tablenameSelfBox)
            ->where(['id' => $id])
            ->execute();

        $this->db()->delete($this->tablenameSelfBoxContent)
            ->where(['box_id' => $id])
            ->execute();

        return (bool)$deleted;
    }

    /**
     * Returns whether the module is installed.
     *
     * @return bool
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return EntriesModel[]
     */
    public function getEntriesBy(array $where = [], array $orderBy = ['key' => 'DESC'], ?\Ilch\Pagination $pagination = null): array
    {
        $select = $this->db()->select()
            ->fields(['key', 'module', 'locale', 'name'])
            ->from([$this->tablename])
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
            return [];
        }
        $entryModels = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entryModels[] = $entryModel;
        }
        return $entryModels;
    }

    /**
     * Inserts a box model in the database.
     *
     * @param EntriesModel $box
     * @return bool
     */
    public function install(EntriesModel $box): bool
    {
        if (!is_array($box->getContent())) {
            return false;
        }
        foreach ($box->getContent() as $key => $content) {
            if (!is_array($content)) {
                continue;
            }
            foreach ($content as $lang => $value) {
                if (!is_array($value) || !isset($value['name'])) {
                    continue;
                }
                $this->db()->insert($this->tablename)
                    ->values([
                        'key' => $key,
                        'module' => $box->getModule(),
                        'locale' => $lang,
                        'name' => $value['name']
                    ])
                    ->execute();
            }
        }
        return true;
    }

    /**
     * Returns true if there is a module box with a specific value for key and module.
     *
     * @param string $key
     * @param string $module
     * @return bool
     * @since 2.1.19
     */
    public function modulesBoxExists(string $key, string $module): bool
    {
        return (bool)$this->db()->select('COUNT(*)')
            ->from($this->tablename)
            ->where(['key' => $key, 'module' => $module])
            ->execute()
            ->fetchCell();
    }

    /**
     * Gets the box lists for overview.
     *
     * @param string $locale
     * @return EntriesModel[]
     * @throws \Ilch\Database\Exception
     */
    public function getBoxList(string $locale): array
    {
        return $this->getEntriesBy(['locale' => $this->db()->escape($locale)], []);
    }

    /**
     * Returns the box model with the specific locale found by the key.
     *
     * @param string $key
     * @param string $locale
     * @return EntriesModel|null
     */
    public function getBoxByIdLocale(string $key, string $locale): ?EntriesModel
    {
        $entries = $this->getEntriesBy(['key' => $key, 'locale' => $this->db()->escape($locale)], []);

        if (!empty($entries)) {
            return reset($entries);
        }

        return null;
    }
}
