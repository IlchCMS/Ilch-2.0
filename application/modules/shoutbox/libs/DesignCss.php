<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Libs;

use Ilch\Registry;

/**
 * Renders the configured design overrides for the shoutbox as a style block.
 * Used by the box and the frontend page so both look the same.
 *
 * @since 1.8.0
 */
class DesignCss
{
    /**
     * Returns a style block with the configured overrides or an empty string
     * if everything is set to the theme default.
     *
     * @return string
     */
    public static function render(): string
    {
        $config = Registry::get('config');
        if ($config === null) {
            return '';
        }

        $backgroundColor = self::sanitizeColor((string)$config->get('shoutbox_designBackgroundColor'));
        $textColor = self::sanitizeColor((string)$config->get('shoutbox_designTextColor'));
        $nameColor = self::sanitizeColor((string)$config->get('shoutbox_designNameColor'));
        $fontSize = (int)$config->get('shoutbox_designFontSize');
        $customCss = trim((string)$config->get('shoutbox_customCss'));

        $rules = [];

        // Override the Bootstrap table variables so striped/hover backgrounds change as well.
        $tableVariables = [];
        if ($backgroundColor !== '') {
            foreach (['bg', 'striped-bg', 'hover-bg'] as $variable) {
                $tableVariables[] = '--bs-table-' . $variable . ': ' . $backgroundColor . ';';
            }
        }
        if ($textColor !== '') {
            foreach (['color', 'striped-color', 'hover-color'] as $variable) {
                $tableVariables[] = '--bs-table-' . $variable . ': ' . $textColor . ';';
            }
        }
        if ($tableVariables) {
            $rules[] = '.shoutbox-messages table, table.shoutbox-messages { ' . implode(' ', $tableVariables) . ' }';
        }

        $cellRules = [];
        if ($textColor !== '') {
            $cellRules[] = 'color: ' . $textColor . ';';
        }
        if ($fontSize > 0) {
            $cellRules[] = 'font-size: ' . $fontSize . 'px;';
        }
        if ($cellRules) {
            $rules[] = '.shoutbox-messages td { ' . implode(' ', $cellRules) . ' }';
        }

        if ($nameColor !== '') {
            $rules[] = '.shoutbox-messages td a { color: ' . $nameColor . '; }';
        }

        // Box only: container background, buttons and form fields.
        $boxBackgroundColor = self::sanitizeColor((string)$config->get('shoutbox_designBoxBackgroundColor'));
        $buttonColor = self::sanitizeColor((string)$config->get('shoutbox_designButtonColor'));
        $buttonTextColor = self::sanitizeColor((string)$config->get('shoutbox_designButtonTextColor'));
        $inputBackgroundColor = self::sanitizeColor((string)$config->get('shoutbox_designInputBackgroundColor'));
        $inputTextColor = self::sanitizeColor((string)$config->get('shoutbox_designInputTextColor'));

        if ($boxBackgroundColor !== '') {
            $rules[] = '.shoutbox-box { background-color: ' . $boxBackgroundColor . '; }';
        }

        // Set the Bootstrap button variables so hover/active states stay consistent.
        $buttonVariables = [];
        if ($buttonColor !== '') {
            foreach (['bg', 'border-color', 'hover-bg', 'hover-border-color', 'active-bg', 'active-border-color'] as $variable) {
                $buttonVariables[] = '--bs-btn-' . $variable . ': ' . $buttonColor . ';';
            }
        }
        if ($buttonTextColor !== '') {
            foreach (['color', 'hover-color', 'active-color'] as $variable) {
                $buttonVariables[] = '--bs-btn-' . $variable . ': ' . $buttonTextColor . ';';
            }
        }
        if ($buttonVariables) {
            $rules[] = '.shoutbox-box .btn { ' . implode(' ', $buttonVariables) . ' }';
        }

        $inputRules = [];
        if ($inputBackgroundColor !== '') {
            $inputRules[] = 'background-color: ' . $inputBackgroundColor . ';';
        }
        if ($inputTextColor !== '') {
            $inputRules[] = 'color: ' . $inputTextColor . ';';
        }
        if ($inputRules) {
            $rules[] = '.shoutbox-box .form-control, .shoutbox-box .input-group-text { ' . implode(' ', $inputRules) . ' }';
        }

        if ($customCss !== '') {
            // Defense in depth: saved without a closing style tag, strip it on output too.
            $rules[] = str_ireplace('</style', '', $customCss);
        }

        if (empty($rules)) {
            return '';
        }

        return '<style>' . PHP_EOL . implode(PHP_EOL, $rules) . PHP_EOL . '</style>';
    }

    /**
     * Returns the color if it is a valid hex color like #aabbcc or #aabbccdd
     * (with alpha channel), otherwise an empty string.
     *
     * This is the boundary that keeps the rendered style block free of injected
     * CSS, so it stays strict on purpose: no color names, no rgba() notation.
     *
     * @param string $color
     * @return string
     */
    public static function sanitizeColor(string $color): string
    {
        return preg_match('/^#([0-9a-f]{6}|[0-9a-f]{8})$/i', $color) ? $color : '';
    }

    /**
     * Combines a color from the color picker with an opacity in percent into a
     * hex color. 100 percent keeps the six digit notation so existing values
     * stay untouched, everything below adds the alpha channel (0 = transparent).
     *
     * @param string $color color like #aabbcc, an alpha channel gets replaced
     * @param string $opacity opacity in percent (0-100)
     * @return string
     * @since 1.8.2
     */
    public static function composeColor(string $color, string $opacity): string
    {
        if (self::sanitizeColor($color) === '') {
            return '';
        }
        // The color input only ever submits #rrggbb, drop a manually added alpha channel.
        $color = substr($color, 0, 7);

        if (!preg_match('/^\d{1,3}$/', trim($opacity))) {
            return $color;
        }

        $opacity = min(100, (int)$opacity);
        if ($opacity === 100) {
            return $color;
        }

        return $color . sprintf('%02x', (int)round($opacity * 255 / 100));
    }

    /**
     * Returns the opacity of a hex color in percent. Colors without an alpha
     * channel are fully opaque.
     *
     * @param string $color
     * @return int
     * @since 1.8.2
     */
    public static function getOpacity(string $color): int
    {
        if (strlen(self::sanitizeColor($color)) !== 9) {
            return 100;
        }

        return (int)round(hexdec(substr($color, 7, 2)) * 100 / 255);
    }
}
