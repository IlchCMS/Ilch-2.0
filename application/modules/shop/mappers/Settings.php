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
     * @return SettingsModel
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
     */
    public function updateSettingShop(SettingsModel $settingShop)
    {
        $this->db()->update('shop_settings')
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
    }

    /**
     * Update settingBank.
     *
     * @param SettingsModel $settingBank
     */
    public function updateSettingBank(SettingsModel $settingBank)
    {
        $this->db()->update('shop_settings')
            ->values([
                    'bankName' => $settingBank->getBankName(),
                    'bankOwner' => $settingBank->getBankOwner(),
                    'bankIBAN' => $settingBank->getBankIBAN(),
                    'bankBIC' => $settingBank->getBankBIC()
                ])
            ->where(['id' => '1'])
            ->execute();
    }

    /**
     * Update settingDefault.
     *
     * @param SettingsModel $settingDefault
     */
    public function updateSettingDefault(SettingsModel $settingDefault)
    {
        $this->db()->update('shop_settings')
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
    }

    /**
     * Update settingAGB.
     *
     * @param SettingsModel $settingAGB
     */
    public function updateSettingAGB(SettingsModel $settingAGB)
    {
        $this->db()->update('shop_settings')
            ->values([
                      'agb' => $settingAGB->getAGB()
                    ])
            ->where(['id' => '1'])
            ->execute();
    }

    /**
     * Update settings payment.
     *
     * @param SettingsModel $settingPayment
     * @return void
     */
    public function updateSettingPayment(SettingsModel $settingPayment)
    {
        $this->db()->update('shop_settings')
            ->values([
                'paymentClientID' => $settingPayment->getClientID(),
                'paypalMe' => $settingPayment->getPayPalMe(),
                'payPalMePresetAmount' => $settingPayment->isPaypalMePresetAmount()
            ])
            ->where(['id' => '1'])
            ->execute();
    }

    /**
     * Delete example data of the shop.
     */
    public function deleteSampleData()
    {
        $this->db()->update('shop_settings')
            ->values([
                'ifSampleData' => '0'
            ])
            ->where(['id' => '1'])
            ->execute();

        $this->db()->delete('shop_cats')
            ->where(['id <=' => 3])
            ->execute();

        $this->db()->delete('shop_access')
            ->where(['cat_id <=' => 3])
            ->execute();

        $this->db()->delete('shop_items')
            ->where(['id <=' => 7])
            ->execute();

        $this->db()->delete('shop_customers')
            ->where(['id <=' => 4])
            ->execute();

        $this->db()->delete('shop_addresses')
            ->where(['id <=' => 4])
            ->execute();

        $this->db()->delete('shop_orders')
            ->where(['id <=' => 4])
            ->execute();
    }

    /**
     * Keep example data of the shop.
     */
    public function keepSampleData()
    {
        $this->db()->update('shop_settings')
            ->values([
                'ifSampleData' => '0'
            ])
            ->where(['id' => '1'])
            ->execute();
    }
}
