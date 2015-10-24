<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Partner\Mappers;

use Modules\Partner\Models\Entry as PartnerModel;

class Partner extends \Ilch\Mapper
{
    /**
     * Gets the Partner entries.
     *
     * @param array $where
     * @return PartnerModel[]|array
     */
    public function getEntries($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('partners')
            ->where($where)
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new PartnerModel();
            $entryModel->setId($entries['id']);
            $entryModel->setName($entries['name']);
            $entryModel->setLink($entries['link']);
            $entryModel->setBanner($entries['banner']);
            $entryModel->setFree($entries['setfree']);
            $entry[] = $entryModel;

        }

        return $entry;
    }
    
    /**
     * Gets partners.
     *
     * @param array $where
     * @param array $orderBy
     * @return PartnerModel[]|null
     */
    public function getPartnersBy($where = array(), $orderBy = array('id' => 'ASC'))
    {
        $partnerArray = $this->db()->select('*')
            ->from('partners')
            ->where($where)
            ->order($orderBy)
            ->execute()
            ->fetchRows();

        if (empty($partnerArray)) {
            return array();
        }

        $partners = array();

        foreach ($partnerArray as $partnerRow) {
            $partnerModel = new PartnerModel();
            $partnerModel->setId($partnerRow['id']);
            $partnerModel->setName($partnerRow['name']);
            $partnerModel->setLink($partnerRow['link']);
            $partnerModel->setBanner($partnerRow['banner']);
            $partners[] = $partnerModel;
        }

        return $partners;
    }

    /**
     * Gets partner.
     *
     * @param integer $id
     * @return PartnerModel|null
     */
    public function getPartnerById($id)
    {
        $partnerRow = $this->db()->select('*')
            ->from('partners')
            ->where(array('id' => $id))
            ->execute()
            ->fetchAssoc();

        if (empty($partnerRow)) {
            return null;
        }

        $partnerModel = new PartnerModel();
        $partnerModel->setId($partnerRow['id']);
        $partnerModel->setName($partnerRow['name']);
        $partnerModel->setLink($partnerRow['link']);
        $partnerModel->setBanner($partnerRow['banner']);

        return $partnerModel;
    }

    /**
     * Inserts or updates partner model.
     *
     * @param PartnerModel $partner
     */
    public function save(PartnerModel $partner)
    {
        $fields = array
        (
            'setfree' => $partner->getFree(),
            'name' => $partner->getName(),
            'link' => $partner->getLink(),
            'banner' => $partner->getBanner(),
        );

        if ($partner->getId()) {
            $this->db()->update('partners')
                ->values($fields)
                ->where(array('id' => $partner->getId()))
                ->execute();
        } else {
            $this->db()->insert('partners')
                ->values($fields)
                ->execute();
        }
    }

    /**
     * Deletes partner with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete('partners')
            ->where(array('id' => $id))
            ->execute();
    }
}
