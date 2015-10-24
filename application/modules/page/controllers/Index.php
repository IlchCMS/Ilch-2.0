<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Page\Controllers;

use Modules\Page\Mappers\Page as PageMapper;

class Index extends \Ilch\Controller\Frontend
{
    public function showAction()
    {
        $pageMapper = new PageMapper();
        $id = $this->getRequest()->getParam('id');
        $locale = $this->getRequest()->getParam('locale');
        $page = $pageMapper->getPageByIdLocale($id, $locale);

        if ($page == null) {
            $this->getView()->set('content', 'page not found');
        } else {
            $this->getLayout()->set('metaTitle', $page->getTitle());
            $this->getLayout()->set('metaDescription', $page->getDescription());
            $this->getLayout()->getHmenu()->add($page->getTitle(), $page->getPerma());
            $this->getView()->set('content', $page->getContent());
        }
    }
}
