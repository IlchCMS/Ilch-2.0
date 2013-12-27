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
     * @return string
     */
    public function getItems()
    {
        $html = '';
        $menuMapper = new \Admin\Mappers\Menu();
        $items = $menuMapper->getMenuItemsByParent($this->getId(), 0);
        
        if (!empty($items)) {
            foreach ($items as $item) {
                $html .= $this->_recGetItems($item);
            }
        }

        return $html;
    }

    /**
     * Gets the menu items as html-string.
     *
     * @param \Admin\Models\MenuItem $item
     * @return string
     */
    protected function _recGetItems($item)
    {
        $menuMapper = new \Admin\Mappers\Menu();
        $pageMapper = new \Page\Mappers\Page();
        $boxMapper = new \Box\Mappers\Box();
        $subItems = $menuMapper->getMenuItemsByParent(1, $item->getId());

        $html = '<ul class="list-unstyled"><li>';
        $config = \Ilch\Registry::get('config');

        $locale = '';

        if ((bool)$config->get('multilingual_acp')) {
            if ($this->_layout->getTranslator()->getLocale() != $config->get('content_language')) {
                $locale = $this->_layout->getTranslator()->getLocale();
            }
        }

        if ($item->getType() == 0) {
            $html .= '<a href="'.$item->getHref().'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 1) {
            $page = $pageMapper->getPageByIdLocale($item->getSiteId(), $locale);
            $html .= '<a href="'.$this->_layout->url($page->getPerma()).'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 2) {
            $html .= '<a href="'.$this->_layout->url(array('module' => $item->getModuleKey(), 'action' => 'index', 'controller' => 'index')).'">'.$item->getTitle().'</a>';
        } elseif ($item->getType() == 3) {
            $box = $boxMapper->getBoxByIdLocale($item->getBoxId(), $locale);
            $html .= $box->getContent();
        }
        
        if (!empty($subItems)) {
            $html .= '<ul class="list-unstyled">';

            foreach ($subItems as $subItem) {
                $html .= $this->_recGetItems($subItem);
            }

            $html .= '</ul>';
        }

        return $html .= '</li></ul>';
    }
}
