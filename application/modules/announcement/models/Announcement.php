<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Announcement\Models;

class Announcement extends \Ilch\Model
{
    private $id;
    private $content;
    private $active;

    /**
     * @param int $id
     * @param string $content
     */
    public function __construct($id, $content, $active)
    {
        $this->id = $id;
        $this->content = $content;
        $this->active = $active;
    }

    public function getID()
    {
        return $this->id;
    }

    public function setID($id)
    {
        $this->id = $id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function isActive()
    {
        return $this->getActiveState();
    }

    public function getActiveState()
    {
        return $this->active;
    }

    public function setActiveState($state)
    {
        $this->active = $state;
    }
}
