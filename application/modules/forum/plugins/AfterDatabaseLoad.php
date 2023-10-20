<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Forum\Plugins;

use Modules\Forum\Mappers\Forum as ForumMapper;

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];

        if ($request->getModuleName() === 'forum') {
            $router = $pluginData['router'];

            $forumMapper = new ForumMapper();
            $permas = $forumMapper->getForumPermas();
            $url = $router->getQuery();
            $urlParts = explode('/', $url);

            if (isset($permas[$urlParts[0]])) {
                $request->setModuleName('forum');
                $request->setControllerName('showtopics');
                $request->setActionName('index');
                $request->setParam('forumid', $permas[$urlParts[0]]['id']);
                unset($urlParts[0]);

                $result = $router->convertParamStringIntoArray(implode('/', $urlParts));
                foreach ($result as $key => $value) {
                    $request->setParam($key, $value);
                }
            }
        }
    }
}
