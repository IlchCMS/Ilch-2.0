<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Search;

/**
 * Builds a short plain-text snippet (excerpt) around the first query-token
 * occurrence in a document body.
 */
class Excerpt
{
    /**
     * Plain-text window around the first case-insensitive token hit in $text.
     *
     * The text is whitespace-collapsed and scanned for the leftmost occurrence
     * of any token (case-insensitive, word-boundary aware). The window is
     * trimmed to about $radius characters around the match and ellipses are
     * added where the text continues. Returns '' when no token hits the text
     * (title-only matches).
     *
     * @param string $text   document body (already stripped plain text)
     * @param array $tokens  query tokens (see Engine::search())
     * @param int $radius    approximate excerpt radius in characters
     * @return string
     */
    public static function make(string $text, array $tokens, int $radius = 90): string
    {
        $text = trim((string)$text);
        if ($text === '' || $tokens === []) {
            return '';
        }

        // Collapse whitespace for a stable, single-line snippet.
        $text = preg_replace('/\s+/u', ' ', $text);

        // Find the leftmost hit of any token, case-insensitively.
        $hit = null;
        foreach ($tokens as $token) {
            $token = (string)$token;
            if ($token === '') {
                continue;
            }
            $candidate = self::findPosition($text, $token);
            if ($candidate !== null && ($hit === null || $candidate[0] < $hit[0])) {
                $hit = $candidate;
            }
        }

        if ($hit === null) {
            return '';
        }

        $charOffset = $hit[0];
        $hitLength  = $hit[1];
        $start = max(0, $charOffset - $radius);
        $end = min(mb_strlen($text), $charOffset + $hitLength + $radius);

        $excerpt = mb_substr($text, $start, $end - $start, 'UTF-8');
        $prefix  = $start > 0 ? '…' : '';
        $suffix  = $end < mb_strlen($text) ? '…' : '';

        return $prefix . trim($excerpt) . $suffix;
    }

    /**
     * Case-insensitive, word-boundary search returning [charOffset, hitLength]
     * for the first occurrence of $needle in $haystack (UTF-8), or null.
     *
     * preg_match offsets are byte-based; they are converted to character
     * offsets so mb_substr can be used on the result.
     *
     * @param string $haystack
     * @param string $needle
     * @return array|null [int $charOffset, int $hitLength]
     */
    private static function findPosition(string $haystack, string $needle): ?array
    {
        $pattern = '/(?<!\p{L}\p{N})' . preg_quote($needle, '/') . '(?!\p{L}\p{N})/iu';
        if (preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE) !== 1) {
            return null;
        }

        $byteOffset = (int)$matches[0][1];
        $charOffset = mb_strlen(mb_substr($haystack, 0, $byteOffset, 'UTF-8'), 'UTF-8');

        return [$charOffset, mb_strlen($matches[0][0], 'UTF-8')];
    }
}
