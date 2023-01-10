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
            $urlParts = explode('/', $url);

            if (isset($permas[$urlParts[0]])) {
                $request->setModuleName('article');
                $request->setControllerName('index');
                $request->setActionName('show');
                $request->setParam('id', $permas[$urlParts[0]]['article_id']);
                if ($permas[$urlParts[0]]['locale']) {
                    $request->setParam('locale', $permas[$urlParts[0]]['locale']);
                }
                unset($urlParts[0]);
                if (isset($urlParts[0]) && $urlParts[0] === 'locale') {
                    unset($urlParts[0]);
                }

                $result = $router->convertParamStringIntoArray(implode('/', $urlParts));
                foreach ($result as $key => $value) {
                    $request->setParam($key, $value);
                }
            }
        }
    }
}
