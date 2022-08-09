<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Box as EntriesModel;

class Box extends \Ilch\Mapper
{
    public $tablename = 'modules_boxes_content';
    public $tablenameSelfBox = 'boxes';
    public $tablenameSelfBoxContent = 'boxes_content';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     */
    public function checkDBSelfBox(): bool
    {
        return $this->db()->ifTableExists($this->tablenameSelfBox) && $this->db()->ifTableExists($this->tablenameSelfBoxContent);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getSelfBoxEntriesBy($where = [], $orderBy = ['b.id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['b.id', 'b.date_created'])
            ->from(['b' => $this->tablenameSelfBox])
            ->join(['bc' => $this->tablenameSelfBoxContent], 'b.id = bc.box_id', 'LEFT', ['bc.box_id', 'bc.content', 'bc.locale', 'bc.title'])
            ->where($where)
            ->group(['b.id', 'bc.title'])
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
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Get box lists for overview.
     *
     * @param string $locale
     * @param array $orderBy
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxList(string $locale, $orderBy = ['b.id' => 'DESC'])
    {
        return $this->getSelfBoxEntriesBy(['bc.locale' => $this->db()->escape($locale)], $orderBy);

    }

    /**
     * Returns box model found by the key.
     *
     * @param int $id
     * @param string $locale
     * @return EntriesModel|null
     * @throws \Ilch\Database\Exception
     */
    public function getSelfBoxByIdLocale(int $id, string $locale = '')
    {
        $entrys = $this->getSelfBoxEntriesBy(['b.id' => $id, 'bc.locale' => $this->db()->escape($locale)], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Inserts or updates a box model in the database.
     *
     * @param EntriesModel $box
     * @throws \Ilch\Database\Exception
     * @return int
     */
    public function save(EntriesModel $box): int
    {
        if ($box->getId()) {
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

            $this->db()->insert($this->tablenameSelfBoxContent)
                ->values(['box_id' => $boxId, 'title' => $box->getTitle(), 'content' => $box->getContent(), 'locale' => $box->getLocale()])
                ->execute();
            return $boxId;
        }
    }

    /**
     * Delete box with specific id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $this->db()->delete($this->tablenameSelfBox)
            ->where(['id' => $id])
            ->execute();

        return $this->db()->delete($this->tablenameSelfBoxContent)
            ->where(['box_id' => $id])
            ->execute();
    }

    /**
     * returns if the module is installed.
     *
     * @return boolean
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['key' => 'DESC'], $pagination = null)
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
            return null;
        }
        $entrys = [];

        foreach ($entryArray as $entries) {
            $entryModel = new EntriesModel();

            $entryModel->setByArray($entries);

            $entrys[] = $entryModel;
        }
        return $entrys;
    }

    /**
     * Inserts box model in the database.
     *
     * @param EntriesModel $box
     * @return bool
     */
    public function install(EntriesModel $box): bool
    {
        if (is_array($box->getContent())) {
            return false;
        }
        foreach ($box->getContent() ?? [] as $key => $content) {
            foreach ($content as $lang => $value) {
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
        return (boolean)$this->db()->select('COUNT(*)')
            ->from($this->tablename)
            ->where(['key' => $key, 'module' => $module])
            ->execute()
            ->fetchCell();
    }

    /**
     * Get box lists for overview.
     *
     * @param string $locale
     * @return array
     * @throws \Ilch\Database\Exception
     */
    public function getBoxList(string $locale)
    {
        return getEntriesBy(['locale' => $locale], []);
    }

    /**
     * Returns box model with specific locale found by the key.
     *
     * @param string $key
     * @param string $locale
     * @return EntriesModel|null
     */
    public function getBoxByIdLocale(string $key, string $locale)
    {
        $entrys = getEntriesBy(['key' => $key, 'locale' => $locale], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }
}
