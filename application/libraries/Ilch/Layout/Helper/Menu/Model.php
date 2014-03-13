<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\Layout\Helper\Menu;
defined('ACCESS') or die('no direct access');

class Model
{
    /**
     * Id of the menu.
     *
     * @var integer
     */
    protected $_id;
    
    /**
     * Title of the menu.
     *
     * @var string
     */
    protected $_title;

    /**
     * Injects the layout.
     *
     * @param Ilch\Layout $layout
     */
    public function __construct($layout)
    {
        $this->_layout = $layout;
    }

    /**
     * Sets the menu id.
     *
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = (int)$id;
    }

    /**
     * Gets the menu id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Sets the menu title.
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->_title = (string)$title;
    }

    /**
     * Gets the menu title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
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

        $menuMapper = new \Admin\Mappers\Menu();
        $items = $menuMapper->getMenuItemsByParent($this->getId(), 0);
        $boxMapper = new \Admin\Mappers\Box();
        $config = \Ilch\Registry::get('config');

        if ((bool)$config->get('multilingual_acp')) {
            if ($this->_layout->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->_layout->getTranslator()->getLocale();
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
                            $class = 'Boxes\\'.ucfirst($item->getBoxKey()).'\\Index';
                            $view = new \Ilch\View($this->_layout->getRequest(), $this->_layout->getTranslator(), $this->_layout->getRouter());
                            $this->_layout->getTranslator()->load(APPLICATION_PATH.'/boxes/'.$item->getBoxKey().'/translations');
                            $boxObj = new $class($this->_layout, $view, $this->_layout->getRequest(), $this->_layout->getRouter(), $this->_layout->getTranslator());
                            $boxObj->render();
                            $viewPath = APPLICATION_PATH.'/'.dirname($this->_layout->getFile()).'/views/boxes/'.$item->getBoxKey().'/render.php';

                            if (!file_exists($viewPath)) {
                                $viewPath = APPLICATION_PATH.'/boxes/'.$item->getBoxKey().'/render.php';
                            }

                            $output = $view->loadScript($viewPath);
                            $box = new \Admin\Models\Box();
                            $box->setContent($output);
                        }

                        $html = str_replace('%c', $box->getContent(), $html);
                    } else {
                        $htmlMenuItems .= $this->_recGetItems($item, $locale, $options);
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
     * @param \Admin\Models\MenuItem $item
     * @param array $options
     * @return string
     */
    protected function _recGetItems($item, $locale, $options = array())
    {
        $menuMapper = new \Admin\Mappers\Menu();
        $pageMapper = new \Page\Mappers\Page();
        $subItems = $menuMapper->getMenuItemsByParent($item->getMenuId(), $item->getId());
        $html = '';

        if(in_array($item->getType(), array(1,2,3))) {
            $html = '<li>';
        }

        if ($item->getType() == 1) {
            $html .= '<a href="'.$item->getHref().'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 2) {
            $page = $pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $html .= '<a href="'.$this->_layout->getUrl($page->getPerma()).'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 3) {
            $html .= '<a href="'.$this->_layout->getUrl(array('module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index')).'">'.$item->getTitle().'</a>';
        }
        
        if (!empty($subItems)) {
            if (isset($options['class_ul'])) {
                $html .= '<ul class="'.$options['class_ul'].'">';
            } else {
                $html .= '<ul class="list-unstyled ilch_menu_ul">';
            }

            foreach ($subItems as $subItem) {
                $html .= $this->_recGetItems($subItem, $locale, $options);
            }

            $html .= '</ul>';
        }

        if(in_array($item->getType(), array(1,2,3))) {
            $html .= '</li>';
        }

        return $html;
    }
}
