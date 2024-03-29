<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Plugins;

class LanguageCodes
{
    /**
     * @param string $language
     * @param string $locale
     * @return string
     */
    public function statisticLanguage(string $language, string $locale = ''): string
    {
        $languageDEArray = [
            'aa' => 'Fern',
            'ab' => 'Abchasen',
            'ae' => 'Awestisch',
            'af' => 'Afrikaans',
            'ak' => 'Akan-Sprache',
            'am' => 'Amharisch',
            'an' => 'Aragonesisch',
            'ar' => 'Arabisch',
            'as' => 'Assamese',
            'av' => 'Awarisch',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Baschkirisch',
            'be' => 'Weißrussisch',
            'bg' => 'Bulgarisch',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara-Sprache',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Bretonisch',
            'bs' => 'Bosnisch',
            'ca' => 'Katalanisch',
            'ce' => 'Tschetschenisch',
            'ch' => 'Chamorro-Sprache',
            'co' => 'Korsischen',
            'cr' => 'Cree-Sprache',
            'cs' => 'Tschechisch',
            'cu' => 'Kirchenslawisch',
            'cv' => 'Tschuwaschisch',
            'cy' => 'Welch',
            'da' => 'Dänisch',
            'de' => 'Deutsch',
            'dv' => 'Maledivisch',
            'dz' => 'Bhutani',
            'ee' => 'Ewe-Sprache',
            'el' => 'Griechisch',
            'en' => 'Englisch',
            'eo' => 'Esperanto',
            'es' => 'Spanisch',
            'et' => 'Estnisch',
            'eu' => 'Baske',
            'fa' => 'Persisch',
            'ff' => 'Ful',
            'fi' => 'Finnisch',
            'fj' => 'Fiji',
            'fo' => 'Färöer',
            'fr' => 'Französisch',
            'fy' => 'Friesisch',
            'ga' => 'Irish',
            'gd' => 'Scots Gaelic',
            'gl' => 'Galizisch',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'hi' => 'Hindi',
            'he' => 'Hebräisch',
            'ho' => 'Hiri-Motu',
            'hr' => 'Kroatisch',
            'ht' => 'Haitien; Frankokreolisch',
            'hu' => 'Ungarisch',
            'hy' => 'Armenisch',
            'hz' => 'Herero-Sprache',
            'ia' => 'Interlingua',
            'id' => 'Indonesier',
            'ie' => 'Interlingue',
            'ig' => 'Ibo-Sprache',
            'ii' => 'Yi',
            'ik' => 'Inupiak',
            'in' => 'eh. Indonesische',
            'io' => 'Sinohoan',
            'is' => 'Isländisch',
            'it' => 'Italienisch',
            'iu' => 'Inuktitut',
            'iw' => 'eh. Hebrew',
            'ja' => 'Japanisch',
            'ji' => 'eh. Yiddish',
            'jv' => 'Javanisch',
            'jw' => 'Javaner',
            'ka' => 'Georgian',
            'kg' => 'Kongo-Sprache',
            'ki' => 'Kikuyu-Sprache',
            'kk' => 'Kasachisch',
            'kl' => 'Grönländisch',
            'km' => 'Cambodian',
            'kn' => 'Kannada',
            'ko' => 'Koreanisch',
            'kr' => 'Kanuri-Sprache',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdisch',
            'kv' => 'Komi-Sprache',
            'kw' => 'Kornisch',
            'ky' => 'Kirgisen',
            'la' => 'Latein',
            'lb' => 'Luxemburgisch',
            'lg' => 'Ganda',
            'li' => 'Limburgisch',
            'ln' => 'Lingála',
            'lo' => 'Laotisch',
            'lt' => 'Litauisch',
            'lu' => 'Luba-Katanga-Sprache',
            'lv' => 'Lettisch',
            'mg' => 'Malagasy',
            'mh' => 'Marschallesisch',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolisch',
            'mo' => 'Moldavian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltesisch',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Bokmål',
            'nd' => 'Ndebele-Sprache (Simbabwe)',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Holländisch',
            'nn' => 'Nynorsk',
            'no' => 'Norwegisch',
            'nr' => 'Ndbele-Sprache (Transvaal)',
            'nv' => 'Navajo-Sprache',
            'ny' => 'Nyanja-Sprache',
            'oc' => 'Okzitanisch',
            'oj' => 'Ojibwa-Sprache',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetisch',
            'pa' => 'Panjabi',
            'pi' => 'Pali',
            'pl' => 'Polnisch',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portugiesisch',
            'qu' => 'Quechua',
            'rm' => 'Rätoromanisch',
            'rn' => 'Kirundi',
            'ro' => 'Rumänisch',
            'ru' => 'Russisch',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit',
            'sc' => 'Sardisch',
            'sd' => 'Sindhi',
            'se' => 'Samisch',
            'sg' => 'Sangro',
            'sh' => 'Serbo-Kroatisch',
            'si' => 'Singhalesisch',
            'sk' => 'Slowakisch',
            'sl' => 'Slowenisch',
            'sm' => 'Samoaner',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanisch',
            'sr' => 'Serbisch',
            'ss' => 'Siswati',
            'st' => 'Sotho',
            'su' => 'Sudanese',
            'sv' => 'Schwedisch',
            'sw' => 'Swahili',
            'ta' => 'Tamilisch',
            'te' => 'Tegulu',
            'tg' => 'Tajik',
            'th' => 'Thailändisch',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmenen',
            'tl' => 'Tagalog',
            'tn' => 'Setswana',
            'to' => 'Tonga',
            'tr' => 'Türkisch',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitisch',
            'ug' => 'Uiguren',
            'uk' => 'Ukrainisch',
            'ur' => 'Urdu',
            'uz' => 'Usbekisch',
            've' => 'Venda-Sprache',
            'vi' => 'Vietnamesisch',
            'vo' => 'Volapuk',
            'wa' => 'Wallonisch',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Jiddisch',
            'yo' => 'Yoruba',
            'za' => 'Zhuang',
            'zh' => 'Chinesisch',
            'zu' => 'Zulu',
            '' => '',
        ];

        $languageENArray = [
            'aa' => 'Afar',
            'ab' => 'Abkhaz',
            'ae' => 'Avestan',
            'af' => 'Afrikaans',
            'ak' => 'Akan',
            'am' => 'Amharic',
            'an' => 'Aragonese',
            'ar' => 'Arabic',
            'as' => 'Assamese',
            'av' => 'Avaric',
            'ay' => 'Aymara',
            'az' => 'Azerbaijani',
            'ba' => 'Bashkir',
            'be' => 'Belarusian',
            'bg' => 'Bulgarian',
            'bh' => 'Bihari',
            'bi' => 'Bislama',
            'bm' => 'Bambara',
            'bn' => 'Bengali',
            'bo' => 'Tibetan',
            'br' => 'Breton',
            'bs' => 'Bosnian',
            'ca' => 'Corsican',
            'ce' => 'Chechen',
            'ch' => 'Chamorro',
            'co' => 'Corsican',
            'cr' => 'Cree',
            'cs' => 'Czech',
            'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic',
            'cv' => 'Chuvash',
            'cy' => 'Welsh',
            'da' => 'Danish',
            'de' => 'German',
            'dv' => 'Divehi; Dhivehi; Maldivian',
            'dz' => 'Dzongkha',
            'ee' => 'Ewe',
            'el' => 'Greek',
            'en' => 'English',
            'eo' => 'Esperanto',
            'es' => 'Spanish',
            'et' => 'Estonian',
            'eu' => 'Basque',
            'fa' => 'Persian',
            'ff' => 'Fulah',
            'fi' => 'Finnish',
            'fj' => 'Fiji',
            'fo' => 'Faroese',
            'fr' => 'French',
            'fy' => 'Frisian',
            'ga' => 'Irish',
            'gd' => 'Scots Gaelic',
            'gl' => 'Galician',
            'gn' => 'Guarani',
            'gu' => 'Gujarati',
            'gv' => 'Manx',
            'ha' => 'Hausa',
            'hi' => 'Hindi',
            'he' => 'Hebrew',
            'ho' => 'Hiri Motu',
            'hr' => 'Croatian',
            'ht' => 'Haitian; Haitian Creole',
            'hu' => 'Hungarian',
            'hy' => 'Armenian',
            'hz' => 'Herero',
            'ia' => 'Interlingua',
            'id' => 'Indonesian',
            'ie' => 'Interlingue',
            'ig' => 'Igbo',
            'ii' => 'Sichuan Yi; Nuosu',
            'ik' => 'Inupiaq',
            'in' => 'former Indonesian',
            'io' => 'Ido',
            'is' => 'Icelandic',
            'it' => 'Italian',
            'iu' => 'Inuktitut',
            'iw' => 'former Hebrew',
            'ja' => 'Japanese',
            'ji' => 'former Yiddish',
            'jv' => 'Javanese',
            'jw' => 'Javanese',
            'ka' => 'Georgian',
            'kg' => 'Kongo',
            'ki' => 'Kikuyu; Gikuyu',
            'kk' => 'Kazakh',
            'kl' => 'Greenlandic',
            'km' => 'Cambodian',
            'kn' => 'Kannada',
            'ko' => 'Korean',
            'kr' => 'Kanuri',
            'ks' => 'Kashmiri',
            'ku' => 'Kurdish',
            'kv' => 'Komi',
            'kw' => 'Cornish',
            'ky' => 'Kirghiz',
            'la' => 'Latin',
            'lb' => 'Luxembourgish; Letzeburgesch',
            'lg' => 'Ganda',
            'li' => 'Limburgan; Limburger; Limburgish',
            'ln' => 'Lingala',
            'lo' => 'Laothian',
            'lt' => 'Lithuanian',
            'lu' => 'Luba-Katanga',
            'lv' => 'Latvian, Lettish',
            'mg' => 'Malagasy',
            'mh' => 'Marshallese',
            'mi' => 'Maori',
            'mk' => 'Macedonian',
            'ml' => 'Malayalam',
            'mn' => 'Mongolian',
            'mo' => 'Moldavian',
            'mr' => 'Marathi',
            'ms' => 'Malay',
            'mt' => 'Maltese',
            'my' => 'Burmese',
            'na' => 'Nauru',
            'nb' => 'Bokmål, Norwegian; Norwegian Bokmål',
            'nd' => 'Ndebele, North; North Ndebele',
            'ne' => 'Nepali',
            'ng' => 'Ndonga',
            'nl' => 'Dutch',
            'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian',
            'no' => 'Norwegian',
            'nr' => 'Ndebele, South; South Ndebele',
            'nv' => 'Navajo; Navaho',
            'ny' => 'Chichewa; Chewa; Nyanja',
            'oc' => 'Occitan',
            'oj' => 'Ojibwa',
            'om' => 'Oromo',
            'or' => 'Oriya',
            'os' => 'Ossetian; Ossetic',
            'pa' => 'Punjabi',
            'pi' => 'Pali',
            'pl' => 'Polish',
            'ps' => 'Pashto, Pushto',
            'pt' => 'Portuguese',
            'qu' => 'Quechua',
            'rm' => 'Rhaeto-Romance',
            'rn' => 'Kirundi',
            'ro' => 'Romanian',
            'ru' => 'Russian',
            'rw' => 'Kinyarwanda',
            'sa' => 'Sanskrit',
            'sc' => 'Sardinian',
            'sd' => 'Sindhi',
            'se' => 'Northern Sami',
            'sg' => 'Sango',
            'sh' => 'Serbo-Croatian',
            'si' => 'Singhalese',
            'sk' => 'Slovak',
            'sl' => 'Slovenian',
            'sm' => 'Samoan',
            'sn' => 'Shona',
            'so' => 'Somali',
            'sq' => 'Albanian',
            'sr' => 'Serbian',
            'ss' => 'Swati',
            'st' => 'Sesotho',
            'su' => 'Sudanese',
            'sv' => 'Swedish',
            'sw' => 'Swahili',
            'ta' => 'Tamil',
            'te' => 'Tegulu',
            'tg' => 'Tajik',
            'th' => 'Thai',
            'ti' => 'Tigrinya',
            'tk' => 'Turkmen',
            'tl' => 'Tagalog',
            'tn' => 'Setswana',
            'to' => 'Tonga',
            'tr' => 'Turkish',
            'ts' => 'Tsonga',
            'tt' => 'Tatar',
            'tw' => 'Twi',
            'ty' => 'Tahitian',
            'ug' => 'Uighur',
            'uk' => 'Ukrainian',
            'ur' => 'Urdu',
            'uz' => 'Uzbek',
            've' => 'Venda',
            'vi' => 'Vietnamese',
            'vo' => 'Volapuk',
            'wa' => 'Walloon',
            'wo' => 'Wolof',
            'xh' => 'Xhosa',
            'yi' => 'Yiddish',
            'yo' => 'Yoruba',
            'za' => 'Zhuang',
            'zh' => 'Chinese',
            'zu' => 'Zulu',
            '' => '',
        ];

        if (isset($languageDEArray[$language])) {
            if ($locale == 'de_DE') {
                $language = $languageDEArray[$language];
            } else {
                $language = $languageENArray[$language];
            }
        } else {
            return '';
        }

        return $language;
    }
}
