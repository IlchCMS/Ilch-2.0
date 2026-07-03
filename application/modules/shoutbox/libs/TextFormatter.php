<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Shoutbox\Libs;

class TextFormatter
{
    /**
     * Emoticon to emoji map. The keys must match the escaped text,
     * so tokens containing < > " ' are written as HTML entities.
     *
     * @var array<string, string>
     */
    private const SMILIES = [
        ':-)' => '🙂',
        ':)' => '🙂',
        ':-(' => '🙁',
        ':(' => '🙁',
        ';-)' => '😉',
        ';)' => '😉',
        ':-D' => '😀',
        ':D' => '😀',
        ':-P' => '😛',
        ':-p' => '😛',
        ':P' => '😛',
        ':p' => '😛',
        ':-O' => '😮',
        ':-o' => '😮',
        ':O' => '😮',
        ':o' => '😮',
        ':-|' => '😐',
        ':|' => '😐',
        ':&#039;(' => '😢',
        '&lt;3' => '❤️',
        '8-)' => '😎',
    ];

    /**
     * Formats a plain text message for HTML output:
     * escapes it, converts URLs to links, emoticons to emojis and newlines to <br>.
     *
     * @param string $text
     * @return string
     * @since 1.8.0
     */
    public static function format(string $text): string
    {
        // Same escaping as \Ilch\Design\Base::escape().
        $escaped = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);

        return nl2br(self::replaceSmilies(self::linkify($escaped)));
    }

    /**
     * Converts URLs (http://, https://, www.) in escaped text to links.
     *
     * @param string $escapedText
     * @return string
     */
    private static function linkify(string $escapedText): string
    {
        return preg_replace_callback(
            '~\b(?:https?://|www\.)\S+~iu',
            static function (array $matches): string {
                $url = $matches[0];

                // Quotes and angle brackets (escaped as entities) never belong to a URL - cut there.
                $rest = '';
                $cutPos = null;
                foreach (['&quot;', '&lt;', '&gt;', '&#039;'] as $entity) {
                    $pos = strpos($url, $entity);
                    if ($pos !== false && ($cutPos === null || $pos < $cutPos)) {
                        $cutPos = $pos;
                    }
                }
                if ($cutPos !== null) {
                    $rest = substr($url, $cutPos);
                    $url = substr($url, 0, $cutPos);
                }

                // Do not swallow punctuation following the URL.
                $trailing = '';
                while (preg_match('~(?:&amp;|[.,!?;:)\]])$~', $url, $match)) {
                    if ($match[0] === ')' && strpos($url, '(') !== false) {
                        // Keep closing parentheses of URLs like .../PHP_(disambiguation).
                        break;
                    }
                    $trailing = $match[0] . $trailing;
                    $url = substr($url, 0, -strlen($match[0]));
                }

                if ($url === '' || strcasecmp($url, 'www.') === 0) {
                    return $matches[0];
                }

                $href = (stripos($url, 'www.') === 0) ? 'https://' . $url : $url;

                return '<a href="' . $href . '" target="_blank" rel="noopener nofollow">' . $url . '</a>' . $trailing . $rest;
            },
            $escapedText
        );
    }

    /**
     * Replaces emoticons with emojis outside of the generated links.
     *
     * @param string $html
     * @return string
     */
    private static function replaceSmilies(string $html): string
    {
        $parts = preg_split('~(<a\b.*?</a>)~is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach ($parts as $index => $part) {
            if (strncasecmp($part, '<a', 2) === 0) {
                continue;
            }
            $parts[$index] = strtr($part, self::SMILIES);
        }

        return implode('', $parts);
    }
}
