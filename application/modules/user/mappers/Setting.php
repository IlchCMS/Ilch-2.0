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
     * @param
     * @return Size
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
        $avatarRow = $this->db()->select('*')
            ->from('users')
            ->where(['id' => $id])
            ->execute()
            ->fetchAssoc();

        if (file_exists($avatarRow['avatar'])) {
            unlink($avatarRow['avatar']);
        }

        $this->db()->update('users')
            ->values(['avatar' => ''])
            ->where(['id' => $id])
            ->execute();
    }
}
