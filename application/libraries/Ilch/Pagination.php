<?php
/**
 * @package ilch
 */

namespace Ilch;
defined('ACCESS') or die('no direct access');

class Pagination
{
    /**
     * @var integer
     */
    protected $_page = 1;

    /**
     * @var integer
     */
    protected $_rowsPerPage = 20;

    /**
     * @var integer
     */
    protected $_rows;

    /**
     * @param integer $page
     */
    public function setPage($page)
    {
        if ($page == null) {
            $page = 1;
        }

        $this->_page = $page;
    }

    /**
     * @param integer $rows
     */
    public function setRows($rows)
    {
        $this->_rows = $rows;
    }

    /**
     * @param integer $rows
     */
    public function setRowsPerPage($rowsPerPage)
    {
        $this->_rowsPerPage = $rowsPerPage;
    }

    /**
     * @return array
     */
    public function getLimit()
    {
        return array(($this->_page - 1) * $this->_rowsPerPage, $this->_rowsPerPage);
    }

    /**
     * @return string
     */
    public function getHtml($view, $urlArray)
    {
        if (empty($this->_rows)) {
            return;
        }

        $html = '<ul class="pagination">';
        $page = $this->_page;

        if ($page > 1) {
            $urlArray['page'] = $page - 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&laquo;</a></li>';
        }

        $html .= '<li><a href="#">'.(($page - 1) * $this->_rowsPerPage).' - '.((($page - 1) * $this->_rowsPerPage) + $this->_rowsPerPage).' von '.$this->_rows.'</a></li>';

        if ($page * $this->_rowsPerPage < $this->_rows) {
            $urlArray['page'] = $page + 1;
            $html .= '<li><a href="'.$view->getUrl($urlArray).'">&raquo;</a></li>';
        }

        $html .= '</ul>';

        return $html;
    }
}
