<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shop\Mappers;

use Ilch\Mapper;
use Modules\Shop\Models\Settings as SettingsModel;

class Settings extends Mapper
{
    /**
     * Gets the settings.
     *
     * @return SettingsModel|null
     */
    public function getSettings(): ?SettingsModel
    {
        $serverRow = $this->db()->select('*')
            ->from('shop_settings')
            ->where(['id' => '1'])
            ->execute()
            ->fetchAssoc();

        if (empty($serverRow)) {
            return null;
        }

        $model = new SettingsModel();
        $model->setId($serverRow['id']);
        $model->setShopName($serverRow['shopName']);
        $model->setShopLogo($serverRow['shopLogo']);
        $model->setShopStreet($serverRow['shopStreet']);
        $model->setShopPlz($serverRow['shopPlz']);
        $model->setShopCity($serverRow['shopCity']);
        $model->setShopTel($serverRow['shopTel']);
        $model->setShopFax($serverRow['shopFax']);
        $model->setShopMail($serverRow['shopMail']);
        $model->setShopWeb($serverRow['shopWeb']);
        $model->setShopStNr($serverRow['shopStNr']);
        $model->setBankName($serverRow['bankName']);
        $model->setBankOwner($serverRow['bankOwner']);
        $model->setBankIBAN($serverRow['bankIBAN']);
        $model->setBankBIC($serverRow['bankBIC']);
        $model->setDeliveryTextTop($serverRow['deliveryTextTop']);
        $model->setInvoiceTextTop($serverRow['invoiceTextTop']);
        $model->setInvoiceTextBottom($serverRow['invoiceTextBottom']);
        $model->setAGB($serverRow['agb']);
        $model->setFixTax($serverRow['fixTax']);
        $model->setFixShippingCosts($serverRow['fixShippingCosts']);
        $model->setFixShippingTime($serverRow['fixShippingTime']);
        $model->setAllowWillCollect($serverRow['allowWillCollect']);
        $model->setClientID($serverRow['paymentClientID']);
        $model->setPayPalMe($serverRow['paypalMe']);
        $model->setPaypalMePresetAmount($serverRow['paypalMePresetAmount']);
        $model->setIfSampleData($serverRow['ifSampleData']);

        return $model;
    }

    /**
     * Update settingShop.
     *
     * @param SettingsModel $settingShop
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function updateSettingShop(SettingsModel $settingShop): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                      'shopName' => $settingShop->getShopName(),
                      'shopLogo' => $settingShop->getShopLogo(),
                      'shopStreet' => $settingShop->getShopStreet(),
                      'shopPlz' => $settingShop->getShopPlz(),
                      'shopCity' => $settingShop->getShopCity(),
                      'shopTel' => $settingShop->getShopTel(),
                      'shopFax' => $settingShop->getShopFax(),
                      'shopMail' => $settingShop->getShopMail(),
                      'shopWeb' => $settingShop->getShopWeb(),
                      'shopStNr' => $settingShop->getShopStNr()
                    ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }

    /**
     * Update settingBank.
     *
     * @param SettingsModel $settingBank
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function updateSettingBank(SettingsModel $settingBank): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                    'bankName' => $settingBank->getBankName(),
                    'bankOwner' => $settingBank->getBankOwner(),
                    'bankIBAN' => $settingBank->getBankIBAN(),
                    'bankBIC' => $settingBank->getBankBIC()
                ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }

    /**
     * Update settingDefault.
     *
     * @param SettingsModel $settingDefault
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function updateSettingDefault(SettingsModel $settingDefault): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                'fixTax' => $settingDefault->getFixTax(),
                'fixShippingCosts' => $settingDefault->getFixShippingCosts(),
                'fixShippingTime' => $settingDefault->getFixShippingTime(),
                'allowWillCollect' => $settingDefault->getAllowWillCollect(),
                'invoiceTextTop' => $settingDefault->getInvoiceTextTop(),
                'invoiceTextBottom' => $settingDefault->getInvoiceTextBottom()
            ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }

    /**
     * Update settingAGB.
     *
     * @param SettingsModel $settingAGB
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function updateSettingAGB(SettingsModel $settingAGB): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                      'agb' => $settingAGB->getAGB()
                    ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }

    /**
     * Update settings payment.
     *
     * @param SettingsModel $settingPayment
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function updateSettingPayment(SettingsModel $settingPayment): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                'paymentClientID' => $settingPayment->getClientID(),
                'paypalMe' => $settingPayment->getPayPalMe(),
                'payPalMePresetAmount' => $settingPayment->isPaypalMePresetAmount()
            ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }

    /**
     * Delete example data of the shop.
     *
     * @return int Number of sample rows deleted.
     */
    public function deleteSampleData(): int
    {
        $affectedRows = 0;

        $this->db()->update('shop_settings')
            ->values([
                'ifSampleData' => '0'
            ])
            ->where(['id' => '1'])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_cats')
            ->where(['id <=' => 3])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_access')
            ->where(['cat_id <=' => 3])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_items')
            ->where(['id <=' => 7])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_customers')
            ->where(['id <=' => 4])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_addresses')
            ->where(['id <=' => 4])
            ->execute();

        $affectedRows += (int)$this->db()->delete('shop_orders')
            ->where(['id <=' => 4])
            ->execute();

        return $affectedRows;
    }

    /**
     * Keep example data of the shop.
     *
     * @return int|null ID of the settings row if the update affected a row, otherwise null.
     */
    public function keepSampleData(): ?int
    {
        $affectedRows = (int)$this->db()->update('shop_settings')
            ->values([
                'ifSampleData' => '0'
            ])
            ->where(['id' => '1'])
            ->execute();

        return $affectedRows > 0 ? 1 : null;
    }
}
