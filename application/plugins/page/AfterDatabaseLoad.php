<?php
/**
 * Holds Page_AfterDatabaseLoadPlugin.
 *
 * @author Meyer Dominik
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Page;
defined('ACCESS') or die('no direct access');

class AfterDatabaseLoadPlugin
{
	public function __construct(array $pluginData)
	{
		$request = $pluginData['request'];
		$router = $pluginData['router'];

		$pageMapper = new PageMapper();
		$permas = $pageMapper->getPagePermas();
		$url = $router->getQuery();

		if(isset($permas[$url]))
		{
			$request->setModuleName('page');
			$request->setControllerName('index');
			$request->setActionName('show');
			$request->setParam('id', $permas[$url]['page_id']);
			$request->setParam('locale', $permas[$url]['locale']);
		}
	}
}