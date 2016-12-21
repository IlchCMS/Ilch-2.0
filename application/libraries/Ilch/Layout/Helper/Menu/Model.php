<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;

use Ilch\Layout\Base as Layout;
use Modules\Admin\Models\MenuItem;

class Model
{
    /**
     * Id of the menu.
     *
     * @var integer
     */
    protected $id;

    /**
     * Title of the menu.
     *
     * @var string
     */
    protected $title;

    /**
     * @var \Modules\Admin\Mappers\Menu
     */
    protected $menuMapper;
    /**
     * @var \Modules\Admin\Mappers\Box
     */
    protected $boxMapper;
    /**
     * @var \Modules\Admin\Mappers\Page
     */
    protected $pageMapper;
    /**
     * @var string
     */
    protected $currentUrl;

    /**
     * Injects the layout.
     *
     * @param Layout $layout
     */
    public function __construct(Layout $layout)
    {
        $this->layout = $layout;

        $this->menuMapper = new \Modules\Admin\Mappers\Menu();
        $this->boxMapper = new \Modules\Admin\Mappers\Box();
        $this->pageMapper = new \Modules\Admin\Mappers\Page();
        $this->currentUrl = $layout->getCurrentUrl();
    }

    /**
     * Sets the menu id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->id = (int)$id;
    }

    /**
     * Gets the menu id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = (string)$title;
    }

    /**
     * Gets the menu title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param string $tpl
     * @param array $options
     * @return string
     */
    public function getItems($tpl = '', $options = [])
    {
        $groupIds = [3];

        if ($this->layout->getUser()) {
            $groupIds = [];
            foreach ($this->layout->getUser()->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $items = $this->menuMapper->getMenuItemsByParent($this->getId(), 0);
        if (empty($items)) {
            return '';
        }

        $config = \Ilch\Registry::get('config');

        $html = '';
        $locale = '';

        if ((bool)$config->get('multilingual_acp')) {
            if ($this->layout->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->layout->getTranslator()->getLocale();
            }
        }

        /** @var MenuItem $item */
        foreach ($items as $item) {
            if (!is_in_array($groupIdsArray, explode(',', $item->getAccess()))) {
                if ($item->isBox()) {
                    // Do not render boxes if boxes.render is set to false
                    if (array_dot($options, 'boxes.render') === false) {
                        continue;
                    }
                    if ($item->getBoxId()) {
                        $box = $this->boxMapper->getSelfBoxByIdLocale($item->getBoxId(), $locale);
                    } else {
                        $box = $this->loadBoxFromModule($item);
                    }

                    $contentHtml = $box->getContent();
                } else { //is Menu

                    $contentHtml = '<ul' . $this->createClassAttribute(array_dot($options, 'menus.ul-class-root')) . '>';

                    foreach ($this->menuMapper->getMenuItemsByParent($item->getMenuId(), $item->getId()) as $menuItem) {
                        $contentHtml .= $this->recGetItems($menuItem, $locale, $options, $item->getType());
                    }

                    $contentHtml .= '</ul>';
                }

                $html .= str_replace(['%s', '%c'], [$this->layout->escape($item->getTitle()), $contentHtml], $tpl);
            }
        }

        return $html;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param MenuItem $item
     * @param $locale
     * @param array $options
     * @param int|null $parentType
     * @return string
     */
    protected function recGetItems($item, $locale, $options = [], $parentType = null)
    {
        $groupIds = [3];

        if ($this->layout->getUser()) {
            $groupIds = [];
            foreach ($this->layout->getUser()->getGroups() as $groups) {
                $groupIds[] = $groups->getId();
            }
        }

        $groupIdsArray = explode(',',implode(',', $groupIds));

        $subItems = $this->menuMapper->getMenuItemsByParent($item->getMenuId(), $item->getId());
        $liClasses = [];

        if ($parentType === MenuItem::TYPE_MENU || array_dot($options, 'menus.allow-nesting') === false) {
            $liClasses[] = array_dot($options, 'menus.li-class-root');
        } else {
            $liClasses[] = array_dot($options, 'menus.li-class-child');
        }

        if ($item->isExternalLink()) {
            $href = $item->getHref();
        } elseif ($item->isPageLink()) {
            $page = $this->pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $href = $this->layout->getUrl($page->getPerma());
        } elseif ($item->isModuleLink() && !is_in_array($groupIdsArray, explode(',', $item->getAccess()))) {
            $href = $this->layout->getUrl(
                ['module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index']
            );
        } else {
            return '';
        }

        // add active class if configured and the link matches the origin source
        if ($href === $this->currentUrl) {
            $liClasses[] = array_dot($options, 'menus.li-class-active');
        }

        $contentHtml = '<a href="' . $href . '">' . $this->layout->escape($item->getTitle()) . '</a>';
        $subItemsHtml = '';

        if (!empty($subItems)) {
            foreach ($subItems as $subItem) {
                $subItemsHtml .= $this->recGetItems($subItem, $locale, $options, $item->getType());
            }

            if (array_dot($options, 'menus.allow-nesting') === true) {
                $liClasses[] = array_dot($options, 'menus.li-class-root-nesting');
                $contentHtml .= '<ul' . $this->createClassAttribute(array_dot($options, 'menus.ul-class-child'))
                    . '>' . $subItemsHtml . '</ul>';
                $subItemsHtml = '';
            }
        }

        return '<li' . $this->createClassAttribute($liClasses) . '>' . $contentHtml . '</li>' . $subItemsHtml;
    }

    /**
     * @param array|string $classes
     * @return string
     */
    private function createClassAttribute($classes)
    {
        if (is_array($classes)) {
            $classes = array_filter($classes);
        }

        if (empty($classes)) {
            return '';
        }

        if (is_string($classes)) {
            $classes = [$classes];
        }

        return ' class="' . implode(' ', array_filter($classes)) . '"';
    }

    /**
     * @param MenuItem $item
     * @return \Modules\Admin\Models\Box
     */
    protected function loadBoxFromModule($item)
    {
        $parts = explode('_', $item->getBoxKey());
        $moduleKey = $parts[0];
        $boxKey = $parts[1];

        $class = '\\Modules\\' . ucfirst($moduleKey) . '\\Boxes\\' . ucfirst($boxKey);
        $view = new \Ilch\View($this->layout->getRequest(), $this->layout->getTranslator(), $this->layout->getRouter());
        $this->layout->getTranslator()->load(APPLICATION_PATH . '/modules/' . $moduleKey . '/translations');
        /** @var \Ilch\Box $boxObj */
        $boxObj = new $class(
            $this->layout,
            $view,
            $this->layout->getRequest(),
            $this->layout->getRouter(),
            $this->layout->getTranslator()
        );
        $boxObj->render();

        $layoutBoxFile = APPLICATION_PATH . '/' . dirname($this->layout->getFile()) . '/views/modules/'
            . $moduleKey . '/boxes/views/' . $boxKey . '.php';
        if (file_exists($layoutBoxFile)) {
            $viewPath = $layoutBoxFile;
        } else {
            $viewPath = APPLICATION_PATH . '/modules/' . $moduleKey . '/boxes/views/' . $boxKey . '.php';
        }

        $view->setLayoutKey($this->layout->getLayoutKey());
        $view->setBoxUrl('application/modules/' . $moduleKey);

        $output = $view->loadScript($viewPath);
        $box = new \Modules\Admin\Models\Box();
        $box->setContent($output);
        return $box;
    }
}
