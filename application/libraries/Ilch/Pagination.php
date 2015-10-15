<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch;

defined('ACCESS') or die('no direct access');

class Pagination
{
    /**
     * @var integer
     */
    protected $page = 1;

    /**
     * @var integer
     */
    protected $rowsPerPage = 20;

    /**
     * @var integer
     */
    protected $rows;

    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        if ($page == null) {
            $page = 1;
        }

        $this->page = $page;
    }

    /**
     * @param integer $rows
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
    }

    /**
     * @param integer $rows
     */
    public function setRowsPerPage($rowsPerPage)
    {
        $this->rowsPerPage = $rowsPerPage;
    }

    /**
     * @return array
     */
    public function getLimit()
    {
        return array(($this->page - 1) * $this->rowsPerPage, $this->rowsPerPage);
    }

    /**
     * @return string
     */
    public function getHtml($view, $urlArray)
    {
        if (empty($this->rows)) {
            return;
        }

        $html = '<ul class="pagination">';
        $page = $this->page;

        if ($page > 1) {
            $urlArray['page'] = $page - 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&laquo;</a></li>';
        }

        $html .= '<li><a href="#">'.(($page - 1) * $this->rowsPerPage).' - '.((($page - 1) * $this->rowsPerPage) + $this->rowsPerPage).' von '.$this->rows.'</a></li>';

        if ($page * $this->rowsPerPage < $this->rows) {
            $urlArray['page'] = $page + 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&raquo;</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
