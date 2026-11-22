<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\Boxes;

/**
 * Search box for the layout menu builder (box key "search_search").
 *
 * Rendered through \Ilch\Layout\Helper\Menu\Model::loadBoxFromModule(); the
 * admin places it on a menu item of type "box". No layout edits are needed.
 */
class Search extends \Ilch\Box
{
    public function render()
    {
        $this->getView()->set('searchUrl', $this->getLayout()->getUrl([
            'module' => 'search',
            'controller' => 'index',
            'action' => 'index',
        ]));
    }
}
