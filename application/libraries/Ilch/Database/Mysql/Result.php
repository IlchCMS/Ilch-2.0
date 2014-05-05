<?php
/**
 * Created by PhpStorm.
 * User: sebastian
 * Date: 02.05.14
 * Time: 08:31
 */

namespace Ilch\Database\Mysql;


class Result
{
    /** @var \mysqli_result */
    protected $dbResult;

    /** @var \Ilch\Database\Mysql */
    protected $db;

    /**
     * @param \mysqli_result $dbResult
     * @param \Ilch\Database\Mysql $db
     */
    public function __construct(\mysqli_result $dbResult, \Ilch\Database\Mysql $db)
    {
        $this->dbResult = $dbResult;
        $this->db = $db;
    }


}
