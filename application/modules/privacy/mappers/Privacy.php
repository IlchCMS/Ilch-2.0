<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Privacy\Mappers;

use Modules\Privacy\Models\Privacy as PrivacyModel;

defined('ACCESS') or die('no direct access');

class Privacy extends \Ilch\Mapper
{
    /**
     * Gets the Privacy.
     *
     * @param array $where
     * @return PrivacyModel[]|array
     */
    public function getPrivacy($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('privacy')
            ->where($where)
            ->order(array('id' => 'ASC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return null;
        }

        $privacy = array();

        foreach ($entryArray as $entries) {
            $entryModel = new PrivacyModel();
            $entryModel->setId($entries['id']);
            $entryModel->setTitle($entries['title']);
            $entryModel->setUrlTitle($entries['urltitle']);
            $entryModel->setUrl($entries['url']);
            $entryModel->setText($entries['text']);
            $privacy[] = $entryModel;

        }

        return $privacy;
    }

    /**
     * Gets privacy.
     *
     * @param integer $id
     * @return PrivacyModel|null
     */
    public function getPrivacyById($id)
    {
        $privacy = $this->getPrivacy(array('id' => $id));
        return reset($privacy);
    }

    /**
     * Inserts or updates privacy model.
     *
     * @param PrivacyModel $privacy
     */
    public function save(PrivacyModel $privacy)
    {
        $fields = array
        (
            'title' => $privacy->getTitle(),
            'urltitle' => $privacy->getUrlTitle(),
            'url' => $privacy->getUrl(),
            'text' => $privacy->getText(),
        );

        if ($privacy->getId()) {
            $this->db()->update('privacy')
                ->values($fields)
                ->where(array('id' => $privacy->getId()))
                ->execute();
        } else {
            $this->db()->insert('privacy')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes privacy with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('privacy')
            ->where(array('id' => $id))
            ->execute();
    }
}
