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
     * @return string
     */
    public function getItems($tpl = '')
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
                        if (is_int($item->getBoxKey())) {
                            $box = $boxMapper->getBoxByIdLocale($item->getBoxKey(), $locale);
                        } else {
                            $class = 'Boxes\\'.ucfirst($item->getBoxKey()).'\\Index';
                            $view = new \Ilch\View($this->_layout->getRequest(), $this->_layout->getTranslator(), $this->_layout->getRouter());
                            $boxObj = new $class($this->_layout, $view, $this->_layout->getRequest(), $this->_layout->getRouter(), $this->_layout->getTranslator());
                            $boxObj->render();
                            $output = $view->loadScript(APPLICATION_PATH.'/boxes/'.$item->getBoxKey().'/render.php');

                            $box = new \Admin\Models\Box();
                            $box->setContent($output);
                        }

                        $html = str_replace('%c', $box->getContent(), $html);
                    } else {
                        $htmlMenuItems .= $this->_recGetItems($item, $locale);
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
     * @return string
     */
    protected function _recGetItems($item, $locale)
    {
        $menuMapper = new \Admin\Mappers\Menu();
        $pageMapper = new \Page\Mappers\Page();
        $subItems = $menuMapper->getMenuItemsByParent(1, $item->getId());

        $html = '<ul class="list-unstyled"><li>';

        if ($item->getType() == 1) {
            $html .= '<a href="'.$item->getHref().'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 2) {
            $page = $pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $html .= '<a href="'.$this->_layout->url($page->getPerma()).'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 3) {
            $html .= '<a href="'.$this->_layout->url(array('module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index')).'">'.$item->getTitle().'</a>';
        }
        
        if (!empty($subItems)) {
            $html .= '<ul class="list-unstyled">';

            foreach ($subItems as $subItem) {
                $html .= $this->_recGetItems($subItem, $locale);
            }

            $html .= '</ul>';
        }

        return $html .= '</li></ul>';
    }
}
