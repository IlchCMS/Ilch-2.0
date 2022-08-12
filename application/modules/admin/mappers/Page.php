<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\Page as EntriesModel;

class Page extends \Ilch\Mapper
{
    public $tablename = 'pages';
    public $tablenameContent = 'pages_content';

    /**
     * returns if the module is installed.
     *
     * @return boolean
     */
    public function checkDB(): bool
    {
        return $this->db()->ifTableExists($this->tablename) && $this->db()->ifTableExists($this->tablenameContent);
    }

    /**
     * Gets the Entries by param.
     *
     * @param array $where
     * @param array $orderBy
     * @param \Ilch\Pagination|null $pagination
     * @return array|null
     */
    public function getEntriesBy($where = [], $orderBy = ['p.id' => 'DESC'], $pagination = null)
    {
        $select = $this->db()->select()
            ->fields(['p.id', 'p.date_created'])
            ->from(['p' => $this->tablename])
            ->join(['pc' => $this->tablenameContent], 'p.id = pc.page_id', 'LEFT', ['pc.page_id', 'pc.content', 'pc.description', 'pc.keywords', 'pc.locale', 'pc.title', 'pc.perma'])
            ->where($where)
            ->group(['p.id', 'pc.title', 'pc.perma'])
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
     * Get page lists for overview.
     *
     * @param string $locale
     * @param array $orderBy
     * @return array
     */
    public function getPageList(string $locale = '', $orderBy = ['p.id' => 'DESC'])
    {
        return $this->getEntriesBy(['pc.locale' => $this->db()->escape($locale)], $orderBy);
    }

    /**
     * Returns page model found by the key.
     *
     * @param int $id
     * @param string $locale
     * @return EntriesModel|null
     */
    public function getPageByIdLocale(int $id, string $locale = '')
    {
        $entrys = $this->getEntriesBy(['p.id' => $id, 'pc.locale' => $this->db()->escape($locale)], []);

        if (!empty($entrys)) {
            return reset($entrys);
        }

        return null;
    }

    /**
     * Returns all page permas.
     *
     * @return array|null
     */
    public function getPagePermas()
    {
        $permas = $this->db()->select()
            ->fields(['page_id', 'locale', 'perma'])
            ->from([$this->tablenameContent])
            ->execute()
            ->fetchRows();

        $permaArray = [];

        if (empty($permas)) {
            return null;
        }

        foreach ($permas as $perma) {
            $permaArray[$perma['perma']] = $perma;
        }

        return $permaArray;
    }

    /**
     * Inserts or updates a page model in the database.
     *
     * @param EntriesModel $page
     * @return int
     */
    public function save(EntriesModel $page): int
    {
        if ($page->getId()) {
            if ($this->getPageByIdLocale($page->getId(), $page->getLocale())) {
                $this->db()->update($this->tablenameContent)
                    ->values([
                        'title' => $page->getTitle(),
                        'description' => $page->getDescription(),
                        'keywords' => $page->getKeywords(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma()
                    ])
                    ->where([
                        'page_id' => $page->getId(),
                        'locale' => $page->getLocale()
                    ])
                    ->execute();
            } else {
                $this->db()->insert($this->tablenameContent)
                    ->values([
                        'page_id' => $page->getId(),
                        'description' => $page->getDescription(),
                        'keywords' => $page->getKeywords(),
                        'title' => $page->getTitle(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma(),
                        'locale' => $page->getLocale()
                    ])
                    ->execute();
            }
            return $page->getId();
        } else {
            $date = new \Ilch\Date();
            $pageId = $this->db()->insert($this->tablename)
                ->values(['date_created' => $date->toDb()])
                ->execute();

            $this->db()->insert($this->tablenameContent)
                ->values([
                    'page_id' => $pageId,
                    'description' => $page->getDescription(),
                    'keywords' => $page->getKeywords(),
                    'title' => $page->getTitle(),
                    'content' => $page->getContent(),
                    'perma' => $page->getPerma(),
                    'locale' => $page->getLocale()
                ])
                ->execute();
            return $pageId;
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
        $this->db()->delete($this->tablename)
            ->where(['id' => $id])
            ->execute();
        
        return $this->db()->delete($this->tablenameContent)
            ->where(['page_id' => $id])
            ->execute();
    }
}
