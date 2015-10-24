<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Imprint\Mappers;

use Modules\Imprint\Models\Imprint as ImprintModel;

class Imprint extends \Ilch\Mapper
{
    /**
     * Gets the Imprint.
     *
     * @param array $where
     * @return ImprintModel[]|array
     */
    public function getImprint($where = array())
    {
        $entryArray = $this->db()->select('*')
            ->from('imprint')
            ->where($where)
            ->order(array('id' => 'DESC'))
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return array();
        }

        $imprint = array();

        foreach ($entryArray as $entries) {
            $entryModel = new ImprintModel();
            $entryModel->setId($entries['id']);
            $entryModel->setParagraph($entries['paragraph']);
            $entryModel->setCompany($entries['company']);
            $entryModel->setName($entries['name']);
            $entryModel->setAddress($entries['address']);
            $entryModel->setAddressAdd($entries['addressadd']);
            $entryModel->setCity($entries['city']);
            $entryModel->setPhone($entries['phone']);
            $entryModel->setFax($entries['fax']);
            $entryModel->setEmail($entries['email']);
            $entryModel->setRegistration($entries['registration']);
            $entryModel->setCommercialRegister($entries['commercialregister']);
            $entryModel->setVatId($entries['vatid']);
            $entryModel->setOther($entries['other']);
            $entryModel->setDisclaimer($entries['disclaimer']);
            $imprint[] = $entryModel;

        }

        return $imprint;
    }

    /**
     * Gets imprint.
     *
     * @param integer $id
     * @return ImprintModel|null
     */
    public function getImprintById($id)
    {
        $imprint = $this->getImprint(array('id' => $id));
        return reset($imprint);
    }

    /**
     * Updates imprint model.
     *
     * @param ImprintModel $imprint
     */
    public function save(ImprintModel $imprint)
    {
        $this->db()->update('imprint')
            ->values
            (
                array
                (
                    'paragraph' => $imprint->getParagraph(),
                    'company' => $imprint->getCompany(),
                    'name' => $imprint->getName(),
                    'address' => $imprint->getAddress(),
                    'addressadd' => $imprint->getAddressAdd(),
                    'city' => $imprint->getCity(),
                    'phone' => $imprint->getPhone(),
                    'fax' => $imprint->getFax(),
                    'email' => $imprint->getEmail(),
                    'registration' => $imprint->getRegistration(),
                    'commercialregister' => $imprint->getCommercialRegister(),
                    'vatid' => $imprint->getVatId(),
                    'other' => $imprint->getOther(),
                    'disclaimer' => $imprint->getDisclaimer()
                )
            )
            ->where(array('id' => $imprint->getId()))
            ->execute();
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string         $key
     * @param string|integer $value
     * @param integer        $autoload
     */
    public function set($key, $value, $id)
    {
        $this->db()->update('imprint')
            ->values
            (
                array
                (
                    $key => $value,
                )
            )
            ->where(array('id' => $id))
            ->execute();
    }
}
