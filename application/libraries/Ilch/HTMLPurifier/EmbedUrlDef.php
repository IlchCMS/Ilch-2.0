<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\HTMLPurifier;

use HTMLPurifier_AttrDef_URI;

class EmbedUrlDef extends HTMLPurifier_AttrDef_URI
{
    public function validate($uri, $config, $context)
    {
        $regexp = $config->get('URI.SafeIframeRegexp');
        if ($regexp !== null) {
            if (!preg_match($regexp, $uri)) {
                return false;
            }
        }

        return parent::validate($uri, $config, $context);
    }
}
