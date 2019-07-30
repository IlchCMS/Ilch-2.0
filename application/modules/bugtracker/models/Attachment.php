<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Models;

class Attachment extends \Ilch\Model
{
    private $id;
    private $bugID;
    private $name;
    private $uploadDate;

    function __construct($id, $bugID, $name, $uploadDate)
    {
        $this->id = (int) $id;
        $this->bugID = $bugID;
        $this->name  = $name;
        $this->uploadDate = $uploadDate;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBugID()
    {
        return $this->bugID;
    }

    public function setBugID($bugID)
    {
        $this->bugID = $bugID;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUploadDate()
    {
        return $this->uploadDate;
    }

    public function setUploadDate($uploadDate)
    {
        $this->uploadDate = $uploadDate;
    }
}
