<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

class LayoutAdvSettings extends \Ilch\Mapper
{
    /**
     * Get specific setting of a layout.
     *
     * @param $layoutKey
     * @param $key
     * @return LayoutAdvSettingsModel|null
     */
    public function getSetting($layoutKey, $key)
    {
        $result = $this->db()->select('*')
            ->from('admin_layoutAdvSettings')
            ->where(['layoutKey' => $layoutKey, 'key' => $key])
            ->execute()
            ->fetchAssoc();

        if (empty($result)) {
            return null;
        }

        $layoutAdvSettingsModel = new LayoutAdvSettingsModel();
        $layoutAdvSettingsModel->setId($result['id']);
        $layoutAdvSettingsModel->setLayoutKey($result['layoutKey']);
        $layoutAdvSettingsModel->setKey($result['key']);
        $layoutAdvSettingsModel->setValue($result['value']);

        return $layoutAdvSettingsModel;
    }

    /**
     * Get all settings of a layout.
     *
     * @param $layoutKey
     * @return array
     */
    public function getSettings($layoutKey)
    {
        $array = $this->db()->select('*')
            ->from('admin_layoutAdvSettings')
            ->where(['layoutKey' => $layoutKey])
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return [];
        }

        $layoutAdvSettings = [];
        foreach ($array as $result) {
            $layoutAdvSettingsModel = new LayoutAdvSettingsModel();
            $layoutAdvSettingsModel->setId($result['id']);
            $layoutAdvSettingsModel->setLayoutKey($result['layoutKey']);
            $layoutAdvSettingsModel->setKey($result['key']);
            $layoutAdvSettingsModel->setValue($result['value']);
            $layoutAdvSettings[$result['key']] = $layoutAdvSettingsModel;
        }

        return $layoutAdvSettings;
    }

    /**
     * Save settings.
     *
     * @param LayoutAdvSettingsModel[] $model
     */
    public function save($models)
    {
        if (!empty($models) && $this->hasSettings($models[0]->getLayoutKey())) {
            foreach($models as $model) {
                $this->db()->update('admin_layoutAdvSettings')
                    ->values([
                        'layoutKey' => $model->getLayoutKey(),
                        'key' => $model->getKey(),
                        'value' => $model->getValue(),
                        ])
                    ->where(['layoutKey' => $model->getLayoutKey(), 'key' => $model->getKey()])
                    ->execute();
            }
        } else {
            foreach($models as $model) {
                if ($model->getKey() === 'ilch_token' || $model->getKey() === 'save') {
                    continue;
                }
                $this->db()->insert('admin_layoutAdvSettings')
                    ->values([
                        'layoutKey' => $model->getLayoutKey(),
                        'key' => $model->getKey(),
                        'value' => $model->getValue(),
                    ])
                    ->execute();
            }
        }
    }

    /**
     * Check if there are settings for a specific layout.
     *
     * @param string $layoutKey
     * @return bool
     */
    public function hasSettings($layoutKey)
    {
        return (bool)$this->db()->select('COUNT(*)')
            ->from('admin_layoutAdvSettings')
            ->where(['layoutKey' => $layoutKey])
            ->execute()
            ->fetchCell();
    }

    /**
     * Delete specific setting of a layout.
     *
     * @param $layoutKey
     * @param $key
     */
    public function deleteSetting($layoutKey, $key)
    {
        $this->deleteBy(['layoutKey' => $layoutKey, 'key' => $key]);
    }

    /**
     * Delete setting by it's id.
     *
     * @param int $id
     */
    public function deleteSettingById($id)
    {
        $this->deleteBy(['id' => $id]);
    }

    /**
     * Delete all settings of a layout.
     * Call this when the layout gets deleted.
     *
     * @param $layoutKey
     */
    public function deleteSettings($layoutKey)
    {
        $this->deleteBy(['layoutKey' => $layoutKey]);
    }

    /**
     * Delete setting/settings by specified where clause.
     *
     * @param array $where
     */
    private function deleteBy($where)
    {
        $this->db()->delete('admin_layoutAdvSettings')
            ->where($where)
            ->execute();
    }
}
