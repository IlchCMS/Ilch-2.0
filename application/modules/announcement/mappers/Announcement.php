<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Announcement\Mappers;

use Modules\Announcement\Models\Announcement as AnnouncementModel;

class Announcement extends \Ilch\Mapper
{
    /**
     * Get AnnouncementBy ID
     * @param int $id
     * @return AnnouncementModel
     */
    public function getAnnouncementByID($id)
    {
        $id = mysqli_real_escape_string($this->db()->getLink(), $id);

        $query = "SELECT * FROM [prefix]_announcements WHERE id = $id";

        $res = $this->db()->query($query);

        $row = mysqli_fetch_assoc($res);

        $id = $row['id'];
        $content = $row['content'];
        $active = $row['active'];

        return new AnnouncementModel($id, $content, (bool) $active);
    }

    /**
     * Gets the active Announcement
     * @return AnnouncementModel
     */
    public function getActiveAnnouncement()
    {
        $query = "SELECT * FROM [prefix]_announcements WHERE active = 1";

        $res = $this->db()->query($query);

        $row = mysqli_fetch_assoc($res);

        $id = $row['id'];
        $content = $row['content'];
        $active = $row['active'];

        return new AnnouncementModel($id, $content, (bool) $active);
    }

    public function getAllAnnouncements()
    {
        $announcements = new \SplDoublyLinkedList();

        $query = "SELECT * FROM [prefix]_announcements";

        $res = $this->db()->query($query);

        while($row = mysqli_fetch_assoc($res))
        {
            $id = $row['id'];
            $content = $row['content'];
            $active = $row['active'];

            $announcements->push(new AnnouncementModel($id, $content, (bool) $active));
        }

        return $announcements;
    }

    public function createAnnouncement($content)
    {
        $content = mysqli_real_escape_string($this->db()->getLink(), $content);
        $query = "INSERT INTO [prefix]_announcements (content) VALUES ('$content')";

        $this->db()->query($query);
    }

    public function deleteAnnouncement($id)
    {
        $id = mysqli_real_escape_string($this->db()->getLink(), $id);
        $query = "DELETE FROM [prefix]_announcements WHERE id = $id";

        $this->db()->query($query);
    }

    public function activateAnnouncement($id)
    {
        $id = mysqli_real_escape_string($this->db()->getLink(), $id);
        $query = "UPDATE [prefix]_announcements SET active = 0";

        $this->db()->query($query);

        $query = "UPDATE [prefix]_announcements SET active = 1 WHERE id = $id";

        $this->db()->query($query);
    }

    public function editAnnouncement($id, $newContent)
    {
        $id = mysqli_real_escape_string($this->db()->getLink(), $id);
        $newContent = mysqli_real_escape_string($this->db()->getLink(), $newContent);

        $query = "UPDATE [prefix]_announcements SET content = '$newContent' WHERE id = $id;";

        $this->db()->query($query);
    }
}
