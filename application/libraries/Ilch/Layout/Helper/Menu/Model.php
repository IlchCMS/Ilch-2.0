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
    public function getItems($tpl = '', $options = array())
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
                if ($item->getType() == 0 || $item->getType() == 4) {
                    $html = str_replace('%c', $htmlMenuItems, $html);
                    $htmlMenuItems = '';
                    $html .= str_replace('%s', $item->getTitle(), $tpl);

                    if ($item->getType() == 4) {
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
                            $viewPath = APPLICATION_PATH.'/'.dirname($this->layout->getFile()).'/override/'.$moduleKey.'/boxes/views/'.$boxKey.'.php';

                            if (!file_exists($viewPath)) {
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
    protected function recGetItems($item, $locale, $options = array())
    {
        $menuMapper = new \Modules\Admin\Mappers\Menu();
        $pageMapper = new \Modules\Page\Mappers\Page();
        $subItems = $menuMapper->getMenuItemsByParent($item->getMenuId(), $item->getId());
        $html = '';

        if(in_array($item->getType(), array(1,2,3))) {
            $html = '<li>';
        }

        if ($item->getType() == 1) {
            $html .= '<a href="'.$item->getHref().'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 2) {
            $page = $pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $html .= '<a href="'.$this->layout->getUrl($page->getPerma()).'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 3) {
            $html .= '<a href="'.$this->layout->getUrl(array('module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index')).'">'.$item->getTitle().'</a>';
        }
        
        if (!empty($subItems)) {
            if (isset($options['class_ul'])) {
                $html .= '<ul class="'.$options['class_ul'].'">';
            } else {
                $html .= '<ul class="list-unstyled ilch_menu_ul">';
            }

            foreach ($subItems as $subItem) {
                $html .= $this->recGetItems($subItem, $locale, $options);
            }

            $html .= '</ul>';
        }

        if(in_array($item->getType(), array(1,2,3))) {
            $html .= '</li>';
        }

        return $html;
    }
}
