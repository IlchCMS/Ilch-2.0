<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch\Search;

use PHPUnit\Ilch\TestCase;

class ExcerptTest extends TestCase
{
    /**
     * Tests that make() returns an empty string for empty text or no tokens.
     */
    public function testEmptyInputReturnsEmptyExcerpt()
    {
        self::assertSame('', Excerpt::make('', ['zebra']));
        self::assertSame('', Excerpt::make('some text', []));
    }

    /**
     * Tests that make() returns '' when no token hits the text (title-only match).
     */
    public function testNoTokenHitReturnsEmptyExcerpt()
    {
        self::assertSame('', Excerpt::make('plain text about other things', ['needle']));
    }

    /**
     * Tests that make() collapses all whitespace runs into single spaces.
     */
    public function testWhitespaceIsCollapsed()
    {
        $excerpt = Excerpt::make("line one\n\n   line two zebra line three", ['zebra']);

        self::assertSame('line one line two zebra line three', $excerpt);
    }

    /**
     * Tests that the leftmost hit of any token wins (case-insensitive).
     */
    public function testLeftmostHitWinsCaseInsensitive()
    {
        self::assertSame('AA bb aa', Excerpt::make('AA bb aa', ['bb', 'aa']));
    }

    /**
     * Tests word-boundary matching: 'cat' must not hit inside 'concatenation'.
     */
    public function testWordBoundaryIsRespected()
    {
        self::assertSame('', Excerpt::make('concatenation only', ['cat']));
    }

    /**
     * Tests that a hit in a short text returns the whole text without ellipses.
     */
    public function testShortTextFitsWithoutEllipses()
    {
        self::assertSame('the cat sat', Excerpt::make('the cat sat', ['cat']));
    }

    /**
     * Tests that ellipses are added when the text continues on both sides.
     */
    public function testEllipsesAreAddedWhenTextContinues()
    {
        $text = str_repeat('x ', 100) . 'needle ' . str_repeat('y ', 100);
        $excerpt = Excerpt::make($text, ['needle'], 10);

        self::assertStringStartsWith('…', $excerpt);
        self::assertStringEndsWith('…', $excerpt);
        self::assertStringContainsString('needle', $excerpt);
    }

    /**
     * Tests that no leading ellipsis is added when the hit starts the text.
     */
    public function testNoLeadingEllipsisWhenHitStartsText()
    {
        $text = 'needle ' . str_repeat('y ', 100);
        $excerpt = Excerpt::make($text, ['needle'], 10);

        self::assertStringStartsWith('needle', $excerpt);
        self::assertStringEndsWith('…', $excerpt);
    }

    /**
     * Tests that matching is case-insensitive.
     */
    public function testMatchingIsCaseInsensitive()
    {
        $text = str_repeat('x ', 100) . 'NEEDLE ' . str_repeat('y ', 100);

        self::assertStringContainsString('NEEDLE', Excerpt::make($text, ['needle'], 10));
    }

    /**
     * Tests that multi-byte characters around the hit are preserved.
     */
    public function testUtf8TextIsHandled()
    {
        self::assertSame('über die zeile', Excerpt::make('über die zeile', ['zeile']));
    }
}
