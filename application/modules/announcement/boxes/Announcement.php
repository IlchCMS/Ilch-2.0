<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Announcement\Boxes;

use Modules\Announcement\Mappers\Announcement as AnnouncementMapper;
use Modules\Announcement\Models\Announcement as AnnouncementModel;

class Announcement extends \Ilch\Box
{
    public function render()
    {
        $announcmentMapper = new AnnouncementMapper();
        $announcement = $announcmentMapper->getActiveAnnouncement();

        $this->getView()->set('announcement', $announcement);
    }
}
