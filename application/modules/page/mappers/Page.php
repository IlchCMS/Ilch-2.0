<?php
/**
 * Holds Page_PageMapper.
 *
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
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
	 * Returns page model found by the id.
	 *
	 * @param integer $id
	 * @return Page_PageModel|null
	 */
	public function getPageById($id)
	{
		$where = array
		(
			'id' => (int)$id,
		);

		$pages = $this->_getBy($where);

		if(!empty($pages))
		{
			return reset($pages);
		}

		return null;
	}

	/**
	 * Returns an array with page models found by the where clause.
	 *
	 * @param mixed[] $where
	 * @return Page_PageModel[]|null
	 */
	protected function _getBy($where = null)
	{
		$pageRows = $this->getDatabase()->selectArray
		(
			'*',
			'pages',
			$where
		);

		if(!empty($pageRows))
		{
			$pages = array();

			foreach($pageRows as $pageRow)
			{
				$pages[] = $this->loadFromArray($pageRow);
			}

			return $pages;
		}

		return null;
	}

	/**
	 * Returns a page model created using an array with page data.
	 *
	 * @param mixed[] $pageRow
	 * @return Page_PageModel
	 */
	public function loadFromArray($pageRow = array())
	{
		$page = new Page_PageModel();

		if(isset($pageRow['id']))
		{
			$page->setId($pageRow['id']);
		}

		if(isset($pageRow['title']))
		{
			$page->setTitle($pageRow['title']);
		}

		if(isset($pageRow['content']))
		{
			$page->setContent($pageRow['content']);
		}

		if(isset($pageRow['date_created']))
		{
			$page->setDateCreated($pageRow['date_created']);
		}

		return $page;
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