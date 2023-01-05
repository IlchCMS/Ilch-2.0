<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Plugins;

use Modules\Admin\Mappers\Page as PageMapper;
use Modules\Admin\Mappers\Logs as LogsMapper;

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];
        $router = $pluginData['router'];

        $pageMapper = new PageMapper();
        $permas = $pageMapper->getPagePermas();
        $url = $router->getQuery();
        $urlParts = explode('/', $url);

        if (isset($permas[$urlParts[0]])) {
            $request->setModuleName('admin');
            $request->setControllerName('page');
            $request->setActionName('show');
            $request->setParam('id', $permas[$urlParts[0]]['page_id']);
            if ($permas[$urlParts[0]]['locale']) {
                $request->setParam('locale', $permas[$urlParts[0]]['locale']);
            }
            unset($urlParts[0]);
            if ($urlParts[1] === 'locale') {
                unset($urlParts[1]);
            }

            $result = $router->convertParamStringIntoArray(implode('/', $urlParts));
            foreach ($result as $key => $value) {
                $request->setParam($key, $value);
            }
        }

        // Log the entries
        $logsMapper = new LogsMapper();
        $currentUrl = $_SERVER['REQUEST_URI'];

        if (strpos($currentUrl, '/admin/') && !empty($_SESSION['user_id'])) {
            $userId = (int) $_SESSION['user_id'];

            $logsMapper->saveLog($userId, $currentUrl);
        }
    }
}
