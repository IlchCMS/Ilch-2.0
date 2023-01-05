<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Controllers;

use Modules\Admin\Mappers\Page as PageMapper;

class Page extends \Ilch\Controller\Frontend
{
    public function showAction()
    {
        $pageMapper = new PageMapper();
        $id = $this->getRequest()->getParam('id');
        $locale = $this->getRequest()->getParam('locale') ?? '';
        $page = $pageMapper->getPageByIdLocale($id, $locale);

        if ($page == null) {
            $this->getView()->set('content', 'page not found');
        } else {
            $this->getLayout()->getTitle()
                    ->add($this->getLayout()->escape($page->getTitle()));
            $this->getLayout()->set('metaDescription', $this->getLayout()->escape($page->getDescription()));
            $this->getLayout()->set('metaKeywords', $this->getLayout()->escape($page->getKeywords()));
            $this->getLayout()->getHmenu()
                    ->add($this->getLayout()->escape($page->getTitle()), $this->getLayout()->escape($page->getPerma()));

            $this->getView()->set('content', $this->getLayout()->purify($page->getContent()));
        }
    }
}
