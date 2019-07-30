<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Bugtracker\Mappers;

use Modules\Bugtracker\Models\Attachment as AttachmentModel;
use Ilch\Date;

class Attachment extends \Ilch\Mapper
{
    public function getAllAttachmentsByBugID($bugID)
    {
        $link = $this->db()->getLink();
        $bugID = mysqli_real_escape_string($link, $bugID);


        $query = "SELECT * FROM bugtracker_attachments WHERE bug_id = ?";
        $stmt = $link->prepare($query);
        $stmt->bind_param('i', $bugID);
        $stmt->execute();

        $res = $stmt->get_result();

        $i = 0;
        $attachments = array();

        while($row = mysqli_fetch_assoc($res))
        {
            $id = $row['id'];
            $bugID = $row['bug_id'];
            $name = $row['filename'];
            $uploadDate = new Date($row['upload_date']);

            $attachments[$i] = new AttachmentModel($id, $bugID, $name, $uploadDate);
            $i++;
        }

        return $attachments;
    }
}
