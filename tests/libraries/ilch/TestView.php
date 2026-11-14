<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

/**
 * Stub for the view passed to Pagination::getHtml(). Renders deterministic URLs.
 */
class TestView
{
    public function getUrl(array $urlArray): string
    {
        $query = http_build_query($urlArray);

        return 'http://localhost/index.php' . ($query === '' ? '' : '?' . $query);
    }
}
