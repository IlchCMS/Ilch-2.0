<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Partner\Mappers;
use Partner\Models\Partner as PartnerModel;

defined('ACCESS') or die('no direct access');

/**
 * The partner mapper class.
 *
 * @package ilch
 */
class Partner extends \Ilch\Mapper
{
    /**
     * Gets partners.
     *
     * @return PartnerModel[]|null
     */
    public function getPartners()
    {
        $sql = 'SELECT *
                FROM [prefix]_partners
                WHERE setfree = 0
                ORDER by id ASC';
        $partnerArray = $this->db()->queryArray($sql);

        if (empty($partnerArray)) {
            return null;
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
        $sql = 'SELECT *
                FROM [prefix]_partners
                WHERE id = '.$this->db()->escape($id);
        $partnerRow = $this->db()->queryRow($sql);

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
        if ($partner->getId()) {
            $this->db()->update
            (
                array
                (
                    'name' => $partner->getName(),
                    'link' => $partner->getLink(),
                    'banner' => $partner->getBanner(),
                ),
                'partners',
                array
                (
                    'id' => $partner->getId(),
                )
            );
        } else {
            $this->db()->insert
            (
                array
                (
                    'name' => $partner->getName(),
                    'link' => $partner->getLink(),
                    'banner' => $partner->getBanner(),
                ),
                'partners'
            );
        }
    }

    /**
     * Saves the partner entry.
     *
     * @param array $datas
     * @return integer
     */
    public function saveEntry(array $datas)
    {
        return $this->db()->insert($datas, 'partners');
    }

    /**
     * Deletes partner with given id.
     *
     * @param integer $id
     */
    public function delete($id)
    {
        $this->db()->delete
        (
            'partners',
            array('id' => $id)
        );
    }
    
    /**
     * Gets all new entries.
     *
     * @return Partner\Models\Partner[]|null
     */
    public function getNewEntries()
    {
        $sql = 'SELECT *
                FROM [prefix]_partners
                WHERE setfree = 1
                ORDER by id DESC';
        $entryArray = $this->db()->queryArray($sql);

        if (empty($entryArray)) {
            return null;
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

    public function saveSetfree(array $datas, array $id)
    {
       return $this->db()->update($datas, 'partners', $id);
    }
}
