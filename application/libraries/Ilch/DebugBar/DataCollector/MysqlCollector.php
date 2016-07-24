<?php
/**
 * @copyright Ilch 2.0
 * @package ilch
 */

namespace Ilch\DebugBar\DataCollector;

use DebugBar\DataCollector\AssetProvider;
use DebugBar\DataCollector\DataCollector;
use DebugBar\DataCollector\PDO\TracedStatement;
use DebugBar\DataCollector\Renderable;
use DebugBar\DataCollector\TimeDataCollector;
use Ilch\Database\MysqlDebug;

class MysqlCollector extends DataCollector implements Renderable, AssetProvider
{
    /**
     * @var MysqlDebug
     */
    private $mysqlDebug;
    /**
     * @var TimeDataCollector
     */
    private $timeCollector;

    public function __construct(MysqlDebug $mysqlDebug, TimeDataCollector $timeCollector = null)
    {
        $this->mysqlDebug = $mysqlDebug;
        $this->timeCollector = $timeCollector;
    }

    public function collect()
    {
        $stmts = array();
        /** @var TracedStatement $stmt */
        foreach ($this->mysqlDebug->getExecutedStatements() as $stmt) {
            $stmts[] = array(
                'sql' => $stmt->getSql(),
                'row_count' => $stmt->getRowCount(),
                'stmt_id' => $stmt->getPreparedId(),
                'prepared_stmt' => $stmt->getSql(),
                'params' => (object) $stmt->getParameters(),
                'duration' => $stmt->getDuration(),
                'duration_str' => $this->getDataFormatter()->formatDuration($stmt->getDuration()),
                'memory' => $stmt->getMemoryUsage(),
                'memory_str' => $this->getDataFormatter()->formatBytes($stmt->getMemoryUsage()),
                'end_memory' => $stmt->getEndMemory(),
                'end_memory_str' => $this->getDataFormatter()->formatBytes($stmt->getEndMemory()),
                'is_success' => $stmt->isSuccess(),
                'error_code' => $stmt->getErrorCode(),
                'error_message' => $stmt->getErrorMessage()
            );
            if ($this->timeCollector !== null) {
                $this->timeCollector->addMeasure($stmt->getSql(), $stmt->getStartTime(), $stmt->getEndTime());
            }
        }

        return array(
            'nb_statements' => count($stmts),
            'nb_failed_statements' => count($this->mysqlDebug->getFailedExecutedStatements()),
            'accumulated_duration' => $this->mysqlDebug->getAccumulatedStatementsDuration(),
            'accumulated_duration_str' => $this->getDataFormatter()->formatDuration(
                $this->mysqlDebug->getAccumulatedStatementsDuration()),
            'memory_usage' => $this->mysqlDebug->getMemoryUsage(),
            'memory_usage_str' => $this->getDataFormatter()->formatBytes($this->mysqlDebug->getPeakMemoryUsage()),
            'peak_memory_usage' => $this->mysqlDebug->getPeakMemoryUsage(),
            'peak_memory_usage_str' => $this->getDataFormatter()->formatBytes($this->mysqlDebug->getPeakMemoryUsage()),
            'statements' => $stmts
        );
    }

    public function getName()
    {
        return 'database';
    }

    public function getWidgets()
    {
        return [
            "database" => [
                "icon" => "inbox",
                "widget" => "PhpDebugBar.Widgets.SQLQueriesWidget",
                "map" => "database",
                "default" => "[]"
            ],
            "database:badge" => [
                "map" => "database.nb_statements",
                "default" => 0
            ]
        ];
    }

    public function getAssets()
    {
        return [
            'css' => 'widgets/sqlqueries/widget.css',
            'js' => 'widgets/sqlqueries/widget.js'
        ];
    }
}
