<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Clanlayout\Mappers;

use Modules\Clanlayout\Models\Layout as LayoutModel;

class Layout extends \Ilch\Mapper
{
    /**
     * Gets value.
     *
     * @param array $where
     * @return LayoutModel[]|null
     */
    public function getValues($where = [])
    {
        $array = $this->db()->select('*')
            ->from('clanlayout_settings')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $values = [];
        foreach ($array as $row) {
            $model = new LayoutModel();
            $model->setKey($row['key'])
                ->setValue($row['value']);

            $values[] = $model;
        }

        return $values;
    }

    /**
     * Gets value by key.
     *
     * @param string $key
     * @return LayoutModel|null
     */
    public function getValueByKey($key)
    {
        $value = $this->getValues(['key' => $key]);

        return reset($value);
    }


    /**
     * Updates Layout model.
     *
     * @param LayoutModel $entry
     */
    public function update(LayoutModel $entry)
    {
        $fields = [
            'key' => $entry->getKey(),
            'value' => $entry->getValue()
        ];

        $this->db()->update('clanlayout_settings')
            ->values($fields)
            ->where(['key' => $entry->getKey()])
            ->execute();
    }
}
