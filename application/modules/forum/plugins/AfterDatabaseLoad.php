<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Forum\Plugins;

use Modules\Forum\Mappers\Forum as ForumMapper;

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];
        $router = $pluginData['router'];

        $forumMapper = new ForumMapper();
        $permas = $forumMapper->getForumPermas();
        $url = $router->getQuery();
        if (isset($permas[$url])) {
            $request->setModuleName('forum');
            $request->setControllerName('showtopics');
            $request->setActionName('index');
            $request->setParam('forumid', $permas[$url]['id']);
        }
    }
}