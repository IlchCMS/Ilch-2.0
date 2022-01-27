<?php
/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Ilch\Database;

use DebugBar\DataCollector\ExceptionsCollector;
use DebugBar\DataCollector\PDO\TracedStatement;

class MysqlDebug extends Mysql
{
    /** @var TracedStatement[] */
    private $executedStatements;
    /**
     * @var ExceptionsCollector
     */
    private $exceptionsCollector;

    /**
     * MysqlDebug constructor.
     * @param ExceptionsCollector $exceptionsCollector
     */
    public function __construct(ExceptionsCollector $exceptionsCollector)
    {
        $this->executedStatements = [];
        self::$errorHandling = self::THROW_EXCEPTIONS;
        $this->exceptionsCollector = $exceptionsCollector;
    }

    /**
     * Execute sql query.
     *
     * @param  string $sql
     * @return \mysqli_result
     */
    public function query(string $sql)
    {
        $sql = $this->getSqlWithPrefix($sql);

        $tracedStatement = new TracedStatement($sql);
        $tracedStatement->start();
        $mysqliResult = mysqli_query($this->conn, $sql);
        $ex = null;

        try {
            if (!$mysqliResult) {
                $this->handleError($sql);
            }
            $rowCount = $mysqliResult instanceof \mysqli_result
                ? $mysqliResult->num_rows
                : mysqli_affected_rows($this->conn);
        } catch (Exception $e) {
            $ex = $e;
            $rowCount = 0;
            $mysqliResult = new \mysqli_result();
            $this->exceptionsCollector->addThrowable($e);
        }

        $tracedStatement->end($ex, $rowCount);
        $this->addExecutedStatement($tracedStatement);

        return $mysqliResult;
    }

    /**
     * Adds an executed TracedStatement
     *
     * @param TracedStatement $stmt
     */
    public function addExecutedStatement(TracedStatement $stmt)
    {
        $this->executedStatements[] = $stmt;
    }

    /**
     * Returns the accumulated execution time of statements
     *
     * @return int
     */
    public function getAccumulatedStatementsDuration()
    {
        return array_reduce($this->executedStatements, static function ($v, $s) {
            return $v + $s->getDuration();
        });
    }

    /**
     * Returns the peak memory usage while performing statements
     *
     * @return int
     */
    public function getMemoryUsage()
    {
        return array_reduce($this->executedStatements, static function ($v, $s) {
            return $v + $s->getMemoryUsage();
        });
    }

    /**
     * Returns the peak memory usage while performing statements
     *
     * @return int
     */
    public function getPeakMemoryUsage()
    {
        return array_reduce($this->executedStatements, static function ($v, $s) {
            $m = $s->getEndMemory();
            return $m > $v ? $m : $v;
        });
    }

    /**
     * Returns the list of executed statements as TracedStatement objects
     *
     * @return array
     */
    public function getExecutedStatements()
    {
        return $this->executedStatements;
    }

    /**
     * Returns the list of failed statements
     *
     * @return array
     */
    public function getFailedExecutedStatements()
    {
        return array_filter($this->executedStatements, static function ($s) {
            return !$s->isSuccess();
        });
    }
}
