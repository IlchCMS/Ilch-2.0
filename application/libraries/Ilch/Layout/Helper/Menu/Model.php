<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;

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
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
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
        $html = '';
        $locale = '';
        $htmlMenuItems = '';

        $menuMapper = new \Modules\Admin\Mappers\Menu();
        $items = $menuMapper->getMenuItemsByParent($this->getId(), 0);
        $boxMapper = new \Modules\Admin\Mappers\Box();
        $config = \Ilch\Registry::get('config');

        if ((bool)$config->get('multilingual_acp')) {
            if ($this->layout->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->layout->getTranslator()->getLocale();
            }
        }

        if (!empty($items)) {
            foreach ($items as $item) {

                // Do not render boxes if boxes.render is set to false
                if ($item->isBox() && array_dot($options, 'boxes.render') === false) {
                    continue;
                }

                if ($item->isMenu() || $item->isBox()) {
                    $html = str_replace('%c', $htmlMenuItems, $html);
                    $htmlMenuItems = '';
                    $html .= str_replace('%s', $item->getTitle(), $tpl);

                    if ($item->isBox()) {
                        if ($item->getBoxId()) {
                            $box = $boxMapper->getBoxByIdLocale($item->getBoxId(), $locale);
                        } else {
                            $parts = explode('_', $item->getBoxKey());
                            $moduleKey = $parts[0];
                            $boxKey = $parts[1];

                            $class = '\\Modules\\'.ucfirst($moduleKey).'\\Boxes\\'.ucfirst($boxKey);
                            $view = new \Ilch\View($this->layout->getRequest(), $this->layout->getTranslator(), $this->layout->getRouter());
                            $this->layout->getTranslator()->load(APPLICATION_PATH.'/modules/'.$moduleKey.'/translations');
                            $boxObj = new $class($this->layout, $view, $this->layout->getRequest(), $this->layout->getRouter(), $this->layout->getTranslator());
                            $boxObj->render();

                            if (file_exists(APPLICATION_PATH.'/'.dirname($this->layout->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php')) {
                                $viewPath = APPLICATION_PATH.'/'.dirname($this->layout->getFile()).'/views/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
                            } else {
                                $viewPath = APPLICATION_PATH.'/modules/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';
                            }

                            $view->setLayoutKey($this->layout->getLayoutKey());

                            $output = $view->loadScript($viewPath);
                            $box = new \Modules\Admin\Models\Box();
                            $box->setContent($output);
                        }

                        $html = str_replace('%c', $box->getContent(), $html);
                    } else {
                        $htmlMenuItems .= $this->recGetItems($item, $locale, $options);
                    }
                }
            }

            $html = str_replace('%c', $htmlMenuItems, $html);
            $htmlMenuItems = '';
        }

        return $html;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param \Modules\Admin\Models\MenuItem $item
     * @param array $options
     * @return string
     */
    protected function recGetItems($item, $locale, $options = [], $parentType = null)
    {
        $menuMapper = new \Modules\Admin\Mappers\Menu();
        $pageMapper = new \Modules\Admin\Mappers\Page();
        $subItems = $menuMapper->getMenuItemsByParent($item->getMenuId(), $item->getId());
        $html = '';

        if ($item->isLink()) {

            if ($parentType === 0 || array_dot($options, 'menus.allow-nesting') === false) {
                $html = '<li class="' . array_dot($options, 'menus.li-class-root') . '">';
            } else {
                $html = '<li class="' . array_dot($options, 'menus.li-class-child') . '">';
            }
        }

        if ($item->isExternalLink()) {
            $html .= '<a href="'.$item->getHref().'">'.$item->getTitle().'</a>';
        } elseif ($item->isPageLink()) {
            $page = $pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $html .= '<a href="'.$this->layout->getUrl($page->getPerma()).'">'.$item->getTitle().'</a>';
        } elseif ($item->isModuleLink()) {
            $html .= '<a href="'.$this->layout->getUrl(['module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index']).'">'.$item->getTitle().'</a>';
        }

        if (!empty($subItems)) {
            if ($item->isMenu()) {
                $html .= '<ul class="' . array_dot($options, 'menus.ul-class-root') . '">';
            } else {
                if (array_dot($options, 'menus.allow-nesting') === true) {
                    $html .= '<ul class="' . array_dot($options, 'menus.ul-class-child') . '">';
                }
            }

            foreach ($subItems as $subItem) {
                $html .= $this->recGetItems($subItem, $locale, $options, $item->getType());
            }

            if ((! $item->isMenu() && array_dot($options, 'menus.allow-nesting') === true) || $item->isMenu()) {
                $html .= '</ul>';
            }
        }

        if (in_array($item->getType(), [1,2,3])) {
            $html .= '</li>';
        }

        return $html;
    }
}
