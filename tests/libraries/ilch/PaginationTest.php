<?php

/**
 * @copyright Ilch 2
 * @package ilch_phpunit
 */

namespace Ilch;

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestView.php';

class PaginationTest extends TestCase
{
    private const URL = 'http://localhost/index.php';
    private const ELLIPSIS = '<li class="page-item disabled"><span>...</span></li>';

    /**
     * Tests that a fresh Pagination starts on page 1 with 20 rows per page and 0 rows.
     */
    public function testDefaults()
    {
        $pagination = new Pagination();

        self::assertSame(1, $pagination->getPage());
        self::assertSame(20, $pagination->getRowsPerPage());
        self::assertSame(0, $pagination->getRows());
    }

    /**
     * Tests that setPage() stores numeric input, truncating decimals.
     *
     * @param mixed $input
     * @param int $expected
     * @dataProvider setPageProvider
     */
    public function testSetPage($input, int $expected)
    {
        $pagination = new Pagination();
        $pagination->setPage($input);

        self::assertSame($expected, $pagination->getPage());
    }

    public static function setPageProvider(): array
    {
        return [
            'int' => [5, 5],
            'string number' => ['5', 5],
            'float is truncated' => [2.7, 2],
            'decimal string is truncated' => ['4.2', 4],
            'large page' => [10, 10],
        ];
    }

    /**
     * Tests that setPage() falls back to page 1 for invalid input.
     *
     * @param mixed $input
     * @dataProvider invalidSetPageProvider
     */
    public function testSetPageInvalidFallsBackToOne($input)
    {
        $pagination = new Pagination();
        $pagination->setPage($input);

        self::assertSame(1, $pagination->getPage());
    }

    public static function invalidSetPageProvider(): array
    {
        return [
            'null' => [null],
            'empty string' => [''],
            'non-numeric string' => ['abc'],
            'malformed number' => ['1.2.3'],
            'zero' => [0],
            'zero string' => ['0'],
            'negative' => [-5],
            'array' => [[]],
        ];
    }

    /**
     * Tests that setRows() stores the number of rows.
     */
    public function testSetRows()
    {
        $pagination = new Pagination();
        $pagination->setRows(42);

        self::assertSame(42, $pagination->getRows());
    }

    /**
     * Tests that setRows() clamps negative values to zero.
     */
    public function testSetRowsClampsNegativeToZero()
    {
        $pagination = new Pagination();
        $pagination->setRows(-10);

        self::assertSame(0, $pagination->getRows());
    }

    /**
     * Tests that setRowsPerPage() stores the given value.
     */
    public function testSetRowsPerPage()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(10);
        self::assertSame(10, $pagination->getRowsPerPage());

        $pagination->setRowsPerPage(1);
        self::assertSame(1, $pagination->getRowsPerPage());
    }

    /**
     * Tests that setRowsPerPage() falls back to 20 for null or values below 1.
     */
    public function testSetRowsPerPageInvalidFallsBackToDefault()
    {
        $pagination = new Pagination();

        $pagination->setRowsPerPage(null);
        self::assertSame(20, $pagination->getRowsPerPage());

        $pagination->setRowsPerPage(0);
        self::assertSame(20, $pagination->getRowsPerPage());

        $pagination->setRowsPerPage(-5);
        self::assertSame(20, $pagination->getRowsPerPage());
    }

    /**
     * Tests that getLastPage() is 0 when no rows exist.
     */
    public function testGetLastPageWithoutRows()
    {
        $pagination = new Pagination();

        self::assertSame(0, $pagination->getLastPage());
    }

    /**
     * Tests that getLastPage() is exact when rows divide evenly.
     */
    public function testGetLastPageExact()
    {
        $pagination = new Pagination();
        $pagination->setRows(100);
        $pagination->setRowsPerPage(20);

        self::assertSame(5, $pagination->getLastPage());
    }

    /**
     * Tests that getLastPage() rounds up partial pages.
     */
    public function testGetLastPageRoundsUp()
    {
        $pagination = new Pagination();
        $pagination->setRows(101);
        $pagination->setRowsPerPage(20);

        self::assertSame(6, $pagination->getLastPage());
    }

    /**
     * Tests that getLastPage() is 1 when rows fit into a single page.
     */
    public function testGetLastPageSinglePage()
    {
        $pagination = new Pagination();
        $pagination->setRows(19);
        $pagination->setRowsPerPage(20);

        self::assertSame(1, $pagination->getLastPage());
    }

    /**
     * Tests that getLimit() defaults to offset 0 and 20 rows.
     */
    public function testGetLimitDefault()
    {
        $pagination = new Pagination();

        self::assertSame([0, 20], $pagination->getLimit());
    }

    /**
     * Tests that getLimit() computes the offset for the given page.
     */
    public function testGetLimitPageThree()
    {
        $pagination = new Pagination();
        $pagination->setPage(3);

        self::assertSame([40, 20], $pagination->getLimit());
    }

    /**
     * Tests that getLimit() respects a custom rows per page.
     */
    public function testGetLimitCustomRowsPerPage()
    {
        $pagination = new Pagination();
        $pagination->setRowsPerPage(10);
        $pagination->setPage(2);

        self::assertSame([10, 10], $pagination->getLimit());
    }

    /**
     * Tests that getHtml() returns an empty string when no rows exist.
     */
    public function testGetHtmlEmptyWithoutRows()
    {
        $pagination = new Pagination();

        self::assertSame('', $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests that getHtml() returns an empty string when there is only one page.
     */
    public function testGetHtmlEmptyForSinglePage()
    {
        $pagination = new Pagination();
        $pagination->setRows(19);
        $pagination->setRowsPerPage(20);

        self::assertSame('', $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests the complete HTML for two pages while on page one.
     */
    public function testGetHtmlTwoPagesOnFirstPage()
    {
        $pagination = new Pagination();
        $pagination->setRows(25);
        $pagination->setRowsPerPage(20);

        $expected = '<ul class="pagination">'
            . $this->pageItem(1, true)
            . $this->pageItem(2)
            . $this->prevNextItem(2, '&raquo;')
            . '</ul>';

        self::assertSame($expected, $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests the complete HTML for two pages while on page two (no next link).
     */
    public function testGetHtmlTwoPagesOnSecondPage()
    {
        $pagination = new Pagination();
        $pagination->setRows(25);
        $pagination->setRowsPerPage(20);
        $pagination->setPage(2);

        $expected = '<ul class="pagination">'
            . $this->prevNextItem(1, '&laquo;')
            . $this->pageItem(1)
            . $this->pageItem(2, true)
            . '</ul>';

        self::assertSame($expected, $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests the complete HTML on the first page of many pages (trailing ellipsis only).
     */
    public function testGetHtmlFirstPageOfMany()
    {
        $pagination = new Pagination();
        $pagination->setRows(200);
        $pagination->setRowsPerPage(10);

        $window = '';
        for ($i = 1; $i <= 8; $i++) {
            $window .= $this->pageItem($i, $i === 1);
        }

        $expected = '<ul class="pagination">'
            . $window
            . self::ELLIPSIS
            . $this->edgePageItem(20)
            . $this->prevNextItem(2, '&raquo;')
            . '</ul>';

        self::assertSame($expected, $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests that getHtml() limits the page window and adds ellipsis markers
     * for a page in the middle of many pages.
     */
    public function testGetHtmlMiddlePageWithEllipsis()
    {
        $pagination = new Pagination();
        $pagination->setRows(200);
        $pagination->setRowsPerPage(10);
        $pagination->setPage(10);

        $window = '';
        for ($i = 3; $i <= 17; $i++) {
            $window .= $this->pageItem($i, $i === 10);
        }

        $expected = '<ul class="pagination">'
            . $this->prevNextItem(9, '&laquo;')
            . $this->edgePageItem(1)
            . self::ELLIPSIS
            . $window
            . self::ELLIPSIS
            . $this->edgePageItem(20)
            . $this->prevNextItem(11, '&raquo;')
            . '</ul>';

        self::assertSame($expected, $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests the complete HTML on the last page of many pages (leading ellipsis only, no next link).
     */
    public function testGetHtmlLastPageOfMany()
    {
        $pagination = new Pagination();
        $pagination->setRows(200);
        $pagination->setRowsPerPage(10);
        $pagination->setPage(20);

        $window = '';
        for ($i = 13; $i <= 20; $i++) {
            $window .= $this->pageItem($i, $i === 20);
        }

        $expected = '<ul class="pagination">'
            . $this->prevNextItem(19, '&laquo;')
            . $this->edgePageItem(1)
            . self::ELLIPSIS
            . $window
            . '</ul>';

        self::assertSame($expected, $pagination->getHtml(new TestView(), []));
    }

    /**
     * Tests that a custom query parameter name is used for the page links.
     */
    public function testGetHtmlCustomPageKey()
    {
        $pagination = new Pagination();
        $pagination->setRows(25);
        $pagination->setRowsPerPage(20);

        $html = $pagination->getHtml(new TestView(), [], 'p');

        self::assertStringContainsString('?p=1', $html);
        self::assertStringContainsString('?p=2', $html);
        self::assertStringNotContainsString('page=', $html);
    }

    /**
     * Tests that existing URL parameters are kept in the generated links.
     */
    public function testGetHtmlKeepsExistingUrlParameters()
    {
        $pagination = new Pagination();
        $pagination->setRows(25);
        $pagination->setRowsPerPage(20);

        $html = $pagination->getHtml(new TestView(), ['module' => 'news']);

        // http_build_query() orders parameters alphabetically: module, page.
        self::assertStringContainsString('module=news&page=1', $html);
        self::assertStringContainsString('module=news&page=2', $html);
    }

    /**
     * Tests that getHtml() still renders the available pages without an active
     * item when the given page exceeds the last page.
     */
    public function testGetHtmlPageBeyondLast()
    {
        $pagination = new Pagination();
        $pagination->setRows(25);
        $pagination->setRowsPerPage(20);
        $pagination->setPage(5);

        $html = $pagination->getHtml(new TestView(), []);

        self::assertStringStartsWith('<ul class="pagination">', $html);
        self::assertStringEndsWith('</ul>', $html);
        self::assertStringContainsString('?page=1', $html);
        self::assertStringContainsString('?page=2', $html);
        self::assertStringNotContainsString('page-item active', $html);
    }

    /**
     * Builds the markup of a numbered page link item exactly as the window
     * loop in Pagination::getHtml() renders it (note the trailing space
     * when the item is not active).
     */
    private function pageItem(int $number, bool $active = false): string
    {
        $class = 'page-item ' . ($active ? 'active' : '');

        return '<li class="' . $class
            . '"><a href="' . self::URL . '?page=' . $number
            . '" class="page-link">' . $number . '</a></li>';
    }

    /**
     * Builds the markup of the static first ("1") or last page link item,
     * which Pagination::getHtml() renders without the loop's trailing space.
     */
    private function edgePageItem(int $number): string
    {
        return '<li class="page-item"><a href="' . self::URL . '?page=' . $number
            . '" class="page-link">' . $number . '</a></li>';
    }

    /**
     * Builds the markup of a previous (&laquo;) or next (&raquo;) link item.
     */
    private function prevNextItem(int $number, string $label): string
    {
        return '<li class="page-item"><a href="' . self::URL . '?page=' . $number
            . '" class="page-link">' . $label . '</a></li>';
    }
}
