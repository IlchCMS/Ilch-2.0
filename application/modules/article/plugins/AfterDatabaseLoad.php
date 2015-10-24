<?php
/**
 * @copyright Ilch 2.0
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