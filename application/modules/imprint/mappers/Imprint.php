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
    public function getImprint($where = [])
    {
        $entryArray = $this->db()->select('*')
            ->from('imprint')
            ->where($where)
            ->order(['id' => 'DESC'])
            ->execute()
            ->fetchRows();

        if (empty($entryArray)) {
            return [];
        }

        $imprint = [];
        foreach ($entryArray as $entries) {
            $entryModel = new ImprintModel();
            $entryModel->setId($entries['id']);
            $entryModel->setImprint($entries['imprint']);
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
        $imprint = $this->getImprint(['id' => $id]);

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
                [
                    'imprint' => $imprint->getImprint(),
                ]
            )
            ->where(['id' => $imprint->getId()])
            ->execute();
    }

    /**
     * Sets the config for given key/vale.
     *
     * @param string         $key
     * @param string|integer $value
     * @param integer        $id
     */
    public function set($key, $value, $id)
    {
        $this->db()->update('imprint')
            ->values
            (
                [
                    $key => $value,
                ]
            )
            ->where(['id' => $id])
            ->execute();
    }
}
