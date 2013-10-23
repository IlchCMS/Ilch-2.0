<?php
/**
 * Holds Page_PageMapper.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page\Mappers;
use Page\Models\Page as PageModel;

defined('ACCESS') or die('no direct access');

/**
 * The page mapper class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Page extends \Ilch\Mapper
{
    /**
     * Get page lists for overview.
     *
     * @return Page_PageModel[]|null
     */
    public function getPageList()
    {
        $sql = 'SELECT * FROM [prefix]_pages as p
                INNER JOIN [prefix]_pages_content as pc ON p.id = pc.page_id
                GROUP BY p.id';
        $pageArray = $this->db()->queryArray($sql);

        if (empty($pageArray)) {
            return null;
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
     * @return Page_PageModel|null
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
        $sql = 'SELECT page_id, locale, perma FROM [prefix]_pages_content';
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
        if ($page->getId() && $page->getLocale()) {
            if ($this->getPageByIdLocale($page->getId(), $page->getLocale())) {
                $this->db()->update
                (
                    array
                    (
                        'title' => $page->getTitle(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma(),
                    ),
                    'pages_content',
                    array
                    (
                        'page_id' => $page->getId(),
                        'locale' => $page->getLocale(),
                    )
                );
            } else {
                $this->db()->insert
                (
                    array
                    (
                        'page_id' => $page->getId(),
                        'title' => $page->getTitle(),
                        'content' => $page->getContent(),
                        'perma' => $page->getPerma(),
                        'locale' => $page->getLocale()
                    ),
                    'pages_content'
                );
            }
        } else {
            $date = new \Ilch\Date();
            $pageId = $this->db()->insert
            (
                array
                (
                    'date_created' => $date->toDb()
                ),
                'pages'
            );

            $this->db()->insert
            (
                array
                (
                    'page_id' => $pageId,
                    'title' => $page->getTitle(),
                    'content' => $page->getContent(),
                    'perma' => $page->getPerma(),
                    'locale' => $page->getLocale()
                ),
                'pages_content'
            );
        }
    }

    public function delete($id)
    {
        $this->db()->delete
        (
            'pages',
            array('id' => $id)
        );

        $this->db()->delete
        (
            'pages_content',
            array('page_id' => $id)
        );
    }
}
