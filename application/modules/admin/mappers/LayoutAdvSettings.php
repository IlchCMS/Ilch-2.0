<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Admin\Mappers;

use Modules\Admin\Models\LayoutAdvSettings as LayoutAdvSettingsModel;

/**
 * Class LayoutAdvSettings
 * @package Modules\Admin\Mappers
 * @since 2.1.32
 */
class LayoutAdvSettings extends \Ilch\Mapper
{
    /**
     * Get specific setting of a layout.
     *
     * @param string $layoutKey
     * @param string $key
     * @return LayoutAdvSettingsModel|null
     */
    public function getSetting($layoutKey, $key)
    {
        $result = $this->db()->select('*')
            ->from('admin_layoutadvsettings')
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
     * @param string $layoutKey
     * @return array
     */
    public function getSettings($layoutKey): array
    {
        $array = $this->db()->select('*')
            ->from('admin_layoutadvsettings')
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
     * Get list of existing layout keys.
     *
     * @return array|string[]
     */
    public function getListOfLayoutKeys(): array
    {
        $layoutKeys = $this->db()->select('DISTINCT layoutKey')
            ->from('admin_layoutadvsettings')
            ->execute()
            ->fetchList();

        if (empty($layoutKeys)) {
            return [];
        }

        return $layoutKeys;
    }

    /**
     * Save settings.
     *
     * @param LayoutAdvSettingsModel[] $model
     */
    public function save($models)
    {
        if (!empty($models)) {
            if ($this->hasSettings($models[0]->getLayoutKey())) {
                foreach($models as $model) {
                    if ($model->getKey() === 'ilch_token' || $model->getKey() === 'save') {
                        continue;
                    }

                    if ($this->updateSetting($model) === 0) {
                        // This might be the case if a new setting was added to the layout later.
                        // Should be relatively rare.
                        $this->insertSetting($model);
                    }
                }
            } else {
                foreach($models as $model) {
                    $this->insertSetting($model);
                }
            }
        }
    }

    /**
     * Update an existing setting.
     *
     * @param LayoutAdvSettingsModel $model
     * @return int affected rows
     */
    private function updateSetting($model): int
    {
        $affectedRows = $this->db()->update('admin_layoutadvsettings')
            ->values([
                'layoutKey' => $model->getLayoutKey(),
                'key' => $model->getKey(),
                'value' => $model->getValue(),
            ])
            ->where(['layoutKey' => $model->getLayoutKey(), 'key' => $model->getKey()])
            ->execute();

        if ($affectedRows === 0) {
            // If the value was unchanged then affected rows will be 0, even though it was existing.
            // Therefore check if the specific setting exists in this case.
            return (int)$this->db()->select('COUNT(*)')
                ->from('admin_layoutadvsettings')
                ->where(['layoutKey' => $model->getLayoutKey(), 'key' => $model->getKey()])
                ->execute()
                ->fetchCell();
        }

        return $affectedRows;
    }

    /**
     * Insert a new setting.
     *
     * @param LayoutAdvSettingsModel $model
     */
    private function insertSetting($model)
    {
        if ($model->getKey() === 'ilch_token' || $model->getKey() === 'save') {
            return;
        }

        $this->db()->insert('admin_layoutadvsettings')
            ->values([
                'layoutKey' => $model->getLayoutKey(),
                'key' => $model->getKey(),
                'value' => $model->getValue(),
            ])
            ->execute();
    }

    /**
     * Check if there are settings for a specific layout.
     *
     * @param string $layoutKey
     * @return bool
     */
    public function hasSettings($layoutKey): bool
    {
        return (bool)$this->db()->select('COUNT(*)')
            ->from('admin_layoutadvsettings')
            ->where(['layoutKey' => $layoutKey])
            ->execute()
            ->fetchCell();
    }

    /**
     * Delete specific setting of a layout.
     *
     * @param string $layoutKey
     * @param string $key
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
     * @param string $layoutKey
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
        $this->db()->delete('admin_layoutadvsettings')
            ->where($where)
            ->execute();
    }
}
