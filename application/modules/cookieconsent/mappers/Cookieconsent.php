<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Modules\Cookieconsent\Mappers;

use Modules\Cookieconsent\Models\Cookieconsent as CookieConsentModel;

class Cookieconsent extends \Ilch\Mapper
{
    /**
     * Gets cookie consents.
     *
     * @param array $where
     *
     * @return CookieConsentModel[]|null
     */
    public function getCookieConsent($where = [])
    {
        $array = $this->db()->select('*')
            ->from('cookies_consent')
            ->where($where)
            ->execute()
            ->fetchRows();

        if (empty($array)) {
            return null;
        }

        $cookieConsents = [];

        foreach ($array as $row) {
            $model = new CookieConsentModel();
            $model->setText($row['text'])
                ->setLocale($row['locale']);
            $cookieConsents[] = $model;
        }

        return $cookieConsents;
    }

    /**
     * Get cookie consent by locale.
     * @param string $locale
     *
     * @return CookieConsentModel
     */
    public function getCookieConsentByLocale($locale)
    {
        $cookieConsents = $this->getCookieConsent(['locale' => $locale]);

        return reset($cookieConsents);
    }

    /**
     * Inserts or updates cookies consent model.
     *
     * @param CookieConsentModel $cookiesConsent
     */
    public function save(CookieConsentModel $cookiesConsent)
    {
        $fields = [
            'text' => $cookiesConsent->getText(),
            'locale' => $cookiesConsent->getLocale()
        ];

        $row = $this->db()->select('*')
            ->from('cookies_consent')
            ->where(['locale' => $cookiesConsent->getLocale()])
            ->execute()
            ->fetchAssoc();

        if ($row) {
            $this->db()->update('cookies_consent')
                ->values($fields)
                ->where(['locale' => $cookiesConsent->getLocale()])
                ->execute();
        } else {
            $this->db()->insert('cookies_consent')
                ->values($fields)
                ->execute();
        }
    }
}
