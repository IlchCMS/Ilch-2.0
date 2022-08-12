<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch;

class Sorter
{

    /**
     * @var \Ilch\Request
     */
    private $request = null;

    /**
     * @var string
     */
    private $columnName = '';

    /**
     * @var string
     */
    private $orderName = '';

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var string
     */
    private $column = '';

    /**
     * @var string
     */
    private $order = '';

    /**
     * Initial Search Mapper.
     *
     * @param \Ilch\Request $request
     * @param null|array $columns
     */
    public function __construct(\Ilch\Request $request, $columns = null, $columnName = 'column', $orderName = 'order')
    {
        $this->request = $request;
        $this->setColumnName($columnName);
        $this->setOrderName($orderName);

        if (is_array($columns)) {
            $this->setColumns($columns);
        }

        $this->setColumn($this->request->getParam($columnName) && in_array($this->request->getParam($columnName), $columns) ? $this->request->getParam($columnName) : ($columns[0] ?? 'id'));
        $this->setOrder($this->request->getParam($orderName) && strtolower($this->request->getParam($orderName)) == 'asc' ? 'ASC' : 'DESC');
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return $this
     */
    public function setColumns($columns): Sorter
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @param string $columns
     * @return $this
     */
    public function addColumn(string $column): Sorter
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumnName(): string
    {
        return $this->columnName;
    }

    /**
     * @param string $columnName
     * @return $this
     */
    public function setColumnName(string $columnName): Sorter
    {
        $this->columnName = $columnName;

        return $this;
    }

    /**
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function setColumn(string $column): Sorter
    {
        $this->column = $column;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrderName(): string
    {
        return $this->orderName;
    }

    /**
     * @param string $orderName
     * @return $this
     */
    public function setOrderName(string $orderName): Sorter
    {
        $this->orderName = $orderName;

        return $this;
    }

    /**
     * @param bool $reverse
     * @return string
     */
    public function getOrder(bool $reverse = false): string
    {
        if ($reverse) {
            return $this->order == 'ASC' ? 'DESC' : 'ASC';
        } else {
            return $this->order;
        }
    }

    /**
     * @param string $order
     * @return $this
     */
    public function setOrder(string $order): Sorter
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @param string $column
     * @return string
     */
    public function getArrowHtml(string $column = ''): string
    {
        if (!$column) {
            $column = $this->getColumn();
        }
        return '<i class="fa fa-sort'.($this->getColumn() == $column ? '-' . str_replace(array('ASC','DESC'), array('up','down'), $this->getOrder()) : '').'"></i>';
    }

    /**
     * @param string $column
     * @return array
     */
    public function getUrlArray(string $column = '')
    {
        if (!$column) {
            $column = $this->getColumn();
        }
        return [$this->getColumnName() => $column, $this->getOrderName() => strtolower($this->getOrder(true))];
    }

    /**
     * @return array
     */
    public function getOrderByArray()
    {
        return [$this->getColumn() => $this->getOrder()];
    }
}
