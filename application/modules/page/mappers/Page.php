<?php
/**
 * Holds Page_PageMapper.
 *
 * @author Meyer Dominik
 * @copyright Ilch Pluto
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * The page mapper class.
 *
 * @author Meyer Dominik
 * @package ilch
 */
class Page_PageMapper extends Ilch_Mapper
{
	/**
	 * Returns page model found by the key.
	 *
	 * @param string $key
	 * @return Page_PageModel|null
	 */
	public function getPageByKey($key = '')
	{
		$sql = 'SELECT * FROM [prefix]_pages as p
				INNER JOIN [prefix]_pages_content as pc ON p.id = pc.page_id
				WHERE pc.`key` = "'.$this->getDatabase()->escape($key).'"';
		$pageRow = $this->getDatabase()->queryRow($sql);

		if(empty($pageRow))
		{
			return null;
		}

		$pageModel = new Page_PageModel(); 
		$pageModel->setId($pageRow['id']);
		$pageModel->setTitle($pageRow['title']);
		$pageModel->setContent($pageRow['content']);
		$pageModel->setLocale($pageRow['locale']);
		$pageModel->setKey($pageRow['key']);

		return $pageModel;
	}
	/**
	 * Inserts or updates a page model in the database.
	 *
	 * @param Page_PageModel $page
	 */
	public function save(Page_PageModel $page)
	{
		$fields = array
		(
			'title' => $page->getTitle(),
			'content' => $page->getContent(),
			'date_created' => $page->getDateCreated(),
		);

		$pageId = $page->getId();

		if($pageId && $this->getPageById($pageId))
		{
			/*
			 * Page does exist already, update.
			 */
			$this->getDatabase()->update
			(
				$fields,
				'pages',
				array
				(
					'id' => $pageId,
				)
			);
		}
		else
		{
			/*
			 * Page does not exist yet, insert.
			 */
			$pageId = $this->getDatabase()->insert
			(
				$fields,
				'pages'
			);
		}
	}
}