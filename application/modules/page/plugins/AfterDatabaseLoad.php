<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Plugins;

use Modules\Page\Mappers\Page as PageMapper;

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];
        $router = $pluginData['router'];

        $pageMapper = new PageMapper();
        $permas = $pageMapper->getPagePermas();
        $url = $router->getQuery();

        if (isset($permas[$url])) {
            $request->setModuleName('page');
            $request->setControllerName('index');
            $request->setActionName('show');
            $request->setParam('id', $permas[$url]['page_id']);
            $request->setParam('locale', $permas[$url]['locale']);
        }
    }
}
