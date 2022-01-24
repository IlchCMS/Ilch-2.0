<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Article\Plugins;

use Modules\Article\Mappers\Article as ArticleMapper;

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
        $request = $pluginData['request'];
        $router = $pluginData['router'];

        if (!$request->isAdmin()) {
            $articleMapper = new ArticleMapper();
            $permas = $articleMapper->getArticlePermas();
            $url = $router->getQuery();

            if (isset($permas[$url])) {
                $request->setModuleName('article');
                $request->setControllerName('index');
                $request->setActionName('show');
                $request->setParam('id', $permas[$url]['article_id']);
                $request->setParam('locale', $permas[$url]['locale']);
            }
        }
    }
}
