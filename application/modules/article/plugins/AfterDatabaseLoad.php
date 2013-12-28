<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Article\Plugins;
use Article\Mappers\Article as ArticleMapper;
defined('ACCESS') or die('no direct access');

class AfterDatabaseLoad
{
    public function __construct(array $pluginData)
    {
    }
}
