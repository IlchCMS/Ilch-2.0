<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\User\Mappers;

class Setting extends \Ilch\Mapper
{
    /**
     * Gets the Size.
     *
     * @param $bytes
     * @param null $format
     * @param bool $addFormat
     * @return float|string
     */
    public function getNicebytes($bytes, $format = null, $addFormat = true) {
        if ($format === null) {
            $format = $bytes < 1000000 ? 'KB' : 'MB';
        }

        if ($format === 'KB') {
            $nicebytes = round($bytes / 1024, 2);
        } elseif ($format === 'MB') {
            $nicebytes = round($bytes / (1024 * 1024), 2);
        } else {
            $nicebytes = $bytes;
            $format = 'B';
        }

        if ($addFormat) {
            $nicebytes .= ' ' . $format;
        }

        return $nicebytes;
    }

    /**
     * Delete/Unlink Avatar by id.
     *
     * @param int $id
     */
    public function delAvatarById($id) 
    {
        $avatar = $this->db()->select('avatar')
            ->from('users')
            ->where(['id' => $id])
            ->execute()
            ->fetchCell('avatar');

        if ((strpos($avatar, 'static/img/noavatar.jpg') == false) && file_exists($avatar)) {
            unlink($avatar);
        }

        $this->db()->update('users')
            ->values(['avatar' => ''])
            ->where(['id' => $id])
            ->execute();
    }
}
