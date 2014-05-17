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
     * Gets the Impressum.
     *
     * @param array $where
     * @return ImpressumModel[]|array
     */
    public function getImpressum($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('impressum')
            ->where($where)
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return array();
        }

        $impressum = array();

        foreach ($entryArray as $entries) {
            $entryModel = new ImpressumModel();
            $entryModel->setId($entries['id']);
            $entryModel->setParagraph($entries['paragraph']);
            $entryModel->setCompany($entries['company']);
            $entryModel->setName($entries['name']);
            $entryModel->setAddress($entries['address']);
            $entryModel->setCity($entries['city']);
            $entryModel->setPhone($entries['phone']);
            $entryModel->setDisclaimer($entries['disclaimer']);
            $impressum[] = $entryModel;

        }

        return $impressum;
    }

    /**
     * Gets impressum.
     *
     * @param integer $id
     * @return ImpressumModel|null
     */
    public function getImpressumById($id)
    {
        $impressum = $this->getImpressum(array('id' => $id));
        return reset($impressum);
    }

    /**
     * Updates impressum model.
     *
     * @param ImpressumModel $impressum
     */
    public function save(ImpressumModel $impressum)
    {
        $this->db()->update('impressum')
            ->fields
            (
                array
                (
                    'paragraph' => $impressum->getParagraph(),
                    'company' => $impressum->getCompany(),
                    'name' => $impressum->getName(),
                    'address' => $impressum->getAddress(),
                    'city' => $impressum->getCity(),
                    'phone' => $impressum->getPhone(),
                    'disclaimer' => $impressum->getDisclaimer()
                )
            )
            ->where(array('id' => $impressum->getId()))
            ->execute();
    }
}
