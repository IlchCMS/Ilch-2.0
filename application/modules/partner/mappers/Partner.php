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
     * @param array $where
     * @param array $orderBy
     * @return PartnerModel[]|null
     */
    public function getPartnersBy($where = array(), $orderBy = array('id' => 'ASC'))
    {
        $partnerArray = $this->db()->selectArray
        (
            '*',
            'partners',
            $where,
            $orderBy
        );

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
        $partnerRow = $this->db()->selectRow
        (
            '*',
            'partners',
            array('id' => $this->db()->escape($id))
        );

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
                    'setfree' => $partner->getFree(),
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
                    'setfree' => $partner->getFree(),
                    'name' => $partner->getName(),
                    'link' => $partner->getLink(),
                    'banner' => $partner->getBanner(),
                ),
                'partners'
            );
        }
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
}
