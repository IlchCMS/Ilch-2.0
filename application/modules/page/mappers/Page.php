<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Mappers;

use Modules\Page\Models\Page as PageModel;

/**
 * The page mapper class.
 *
 * @package ilch
 */
class Page extends \Ilch\Mapper
{
    /**
     * Get page lists for overview.
     *
     * @param  string $locale
     * @return PageModel[]|array
     */
    public function getPageList($locale = '')
    {
        $sql = 'SELECT pc.title, pc.perma, p.id FROM `[prefix]_pages` as p
                LEFT JOIN `[prefix]_pages_content` as pc ON p.id = pc.page_id
                    AND pc.locale = "'.$this->db()->escape($locale).'"
                GROUP BY p.id';
        $pageArray = $this->db()->queryArray($sql);

        if (empty($pageArray)) {
            return array();
        }

        $pages = array();

        foreach ($pageArray as $pageRow) {
            $pageModel = new PageModel();
            $pageModel->setId($pageRow['id']);
            $pageModel->setTitle($pageRow['title']);
            $pageModel->setPerma($pageRow['perma']);
            $pages[] = $pageModel;
        }

        return $pages;
    }

    /**
     * Returns page model found by the key.
     *
     * @param  string              $id
     * @param  string              $locale
     * @return PageModel|null
     */
    public function getPageByIdLocale($id, $locale = '')
    {
            $sql = 'SELECT * FROM [prefix]_pages as p
                    INNER JOIN [prefix]_pages_content as pc ON p.id = pc.page_id
                    WHERE p.`id` = "'.(int) $id.'" AND pc.locale = "'.$this->db()->escape($locale).'"';
        $pageRow = $this->db()->queryRow($sql);

        if (empty($pageRow)) {
            return null;
        }

        $pageModel = new PageModel();
        $pageModel->setId($pageRow['id']);
        $pageModel->setDescription($pageRow['description']);
        $pageModel->setTitle($pageRow['title']);
        $pageModel->setContent($pageRow['content']);
        $pageModel->setLocale($pageRow['locale']);
        $pageModel->setPerma($pageRow['perma']);

        return $pageModel;
    }

    /**
     * Returns all page permas.
     *
     * @return array|null
     */
    public function getPagePermas()
    {
        $sql = 'SELECT page_id, locale, perma FROM `[prefix]_pages_content`';
        $permas = $this->db()->queryArray($sql);
        $permaArray = array();

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
     * @param PageModel $page
     */
    public function save(PageModel $page)
    {
        if ($page->getId()) {
            if ($this->getPageByIdLocale($page->getId(), $page->getLocale())) {
                $this->db()->update('pages_content')
                    ->values(array(
                        'title' => $page->getTitle(),
                        'description' => $page->getDescription(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma(),
                    ))
                    ->where(array(
                        'page_id' => $page->getId(),
                        'locale' => $page->getLocale(),
                    ))
                    ->execute();
            } else {
                $this->db()->insert('pages_content')
                    ->values
                    (
                        array
                        (
                            'page_id' => $page->getId(),
                            'description' => $page->getDescription(),
                            'title' => $page->getTitle(),
                            'content' => $page->getContent(),
                            'perma' => $page->getPerma(),
                            'locale' => $page->getLocale()
                        )
                    )
                    ->execute();
            }
        } else {
            $date = new \Ilch\Date();
            $pageId = $this->db()->insert('pages')
                ->values(array('date_created' => $date->toDb()))
                ->execute();

            $this->db()->insert('pages_content')
                ->values
                (
                    array
                    (
                        'page_id' => $pageId,
                        'description' => $page->getDescription(),
                        'title' => $page->getTitle(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma(),
                        'locale' => $page->getLocale()
                    )  
                )
                ->execute();
        }
    }

    public function delete($id)
    {
        $this->db()->delete('pages')
            ->where(array('id' => $id))
            ->execute();
        
        $this->db()->delete('pages_content')
            ->where(array('page_id' => $id))
            ->execute();
    }
}
