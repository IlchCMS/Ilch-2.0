<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Impressum\Mappers;

use Impressum\Models\Impressum as ImpressumModel;

defined('ACCESS') or die('no direct access');

class Impressum extends \Ilch\Mapper
{
    /**
     * Gets the Impressum entries.
     *
     * @param array $where
     * @return ImpressumModel[]|array
     */
    public function getImpressum($where = array())
    {
        $entryArray = $this->db()->selectArray
        (
            '*',
            'Impressum',
            $where,
            array('id' => 'DESC')
        );

        if (empty($entryArray)) {
            return array();
        }

        $entry = array();

        foreach ($entryArray as $entries) {
            $entryModel = new ImpressumModel();
            $entryModel->setId($entries['id']);
            $entryModel->setParagraph($entries['paragraph']);
            $entryModel->setCompany($entries['company']);
            $entryModel->setName($entries['name']);
            $entryModel->setAdress($entries['address']);
            $entryModel->setCity($entries['city']);
            $entryModel->setPhone($entries['phone']);
            $entryModel->setDisclaimer($entries['disclaimer']);
            $entry[] = $entryModel;

        }

        return $entry;
    }

    /**
     * Updates impressum model.
     *
     * @param ImpressumModel $impressum
     */
    public function save(ImpressumModel $impressum)
    {
        $this->db()->update
        (
            array
            (
                'name' => $impressum->getName(),
            ),
            'impressum',
            array
            (
                'id' => $impressum->getId(),
            )
        );
    }
}
