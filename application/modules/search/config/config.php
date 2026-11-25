<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Search\Config;

class Config extends \Ilch\Config\Install
{
    public array $config = [
        'key' => 'search',
        'icon_small' => 'fa-solid fa-magnifying-glass',
        'system_module' => true,
        'hide_menu' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Suche',
                'description' => 'Globale Suche über Foren, Artikel und weitere Module.',
            ],
            'en_EN' => [
                'name' => 'Search',
                'description' => 'Global search across forums, articles and other modules.',
            ],
        ],
        'boxes' => [
            'search' => [
                'de_DE' => [
                    'name' => 'Suche',
                ],
                'en_EN' => [
                    'name' => 'Search',
                ],
            ],
        ]
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        // Empty table ⇒ instant; guarded no-op on MySQL 5.5.
        (new \Ilch\Search\Store($this->db()))->ensureFulltextIndex();

        $this->createScheduledTasks();
    }

    /**
     * Register the interval tasks if they do not exist yet
     * (create-if-missing only; see statistic's createScheduledTasks()).
     */
    public function createScheduledTasks(): void
    {
        $scheduler = new \Ilch\Scheduler\Scheduler($this->db());

        // Sweeps what the inline flush missed (failed ops, busy requests).
        // Also the only place where OP_ENSURE_SCHEMA (FULLTEXT DDL) may run,
        // so the ALTER is never executed on a visitor-facing request.
        $scheduler->ensureTask([
            'name' => 'search.drain',
            'handler' => 'search.drain',
            'type' => \Ilch\Scheduler\Scheduler::TYPE_INTERVAL,
            'interval_seconds' => 120,
            'payload' => ['batch' => 50, 'time_limit_ms' => 3000],
        ]);

        // Frontier-based initial/bulk indexing (statistic-style).
        // No-op when the frontiers are caught up.
        $scheduler->ensureTask([
            'name' => 'search.backfill',
            'handler' => 'search.backfill',
            'type' => \Ilch\Scheduler\Scheduler::TYPE_INTERVAL,
            'interval_seconds' => 300,
            'payload' => ['batch' => 100],
        ]);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS [prefix]_search_queue (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `module` VARCHAR(32) NOT NULL,
                  `op` TINYINT(1) NOT NULL,
                  `doc_type` VARCHAR(32) NOT NULL DEFAULT \'\',
                  `doc_id` INT(11) NULL DEFAULT NULL,
                  `locale` VARCHAR(16) NOT NULL DEFAULT \'\',
                  `scope_key` VARCHAR(32) NOT NULL DEFAULT \'\',
                  `scope_id` INT(11) NULL DEFAULT NULL,
                  `created` DATETIME NOT NULL,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_op` (`module`, `op`, `doc_type`, `doc_id`, `locale`, `scope_key`, `scope_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_search_documents (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `module` VARCHAR(32) NOT NULL,
                  `doc_type` VARCHAR(32) NOT NULL,
                  `doc_id` INT(11) NOT NULL,
                  `locale` VARCHAR(16) NOT NULL DEFAULT \'\',
                  `parent_id` INT(11) NULL DEFAULT NULL,
                  `scope_key` VARCHAR(32) NOT NULL DEFAULT \'\',
                  `scope_id` INT(11) NULL DEFAULT NULL,
                  `title` TEXT NOT NULL,
                  `body` MEDIUMTEXT NOT NULL,
                  `access_groups` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `date_activity` DATETIME NULL DEFAULT NULL,
                  `meta` TEXT NULL DEFAULT NULL,
                  `content_hash` CHAR(32) NOT NULL DEFAULT \'\',
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_doc` (`module`, `doc_type`, `doc_id`, `locale`),
                  KEY `idx_scope` (`module`, `scope_key`, `scope_id`),
                  KEY `idx_module_type` (`module`, `doc_type`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_search_progress (
                  `module` VARCHAR(32) NOT NULL,
                  `frontier` INT(11) NOT NULL DEFAULT 0,
                  `updated` DATETIME NOT NULL,
                  PRIMARY KEY (`module`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "2.2.22":
                // Install the search module.
                $this->install();

                $moduleMapper = new \Modules\Admin\Mappers\Module();
                $moduleModel = new \Modules\Admin\Models\Module();
                $moduleModel->setByArray($this->config);
                $moduleMapper->save($moduleModel);

                $boxMapper = new \Modules\Admin\Mappers\Box();
                if (!$boxMapper->modulesBoxExists($this->config['key'], $this->config['key'])) {
                    $boxModel = new \Modules\Admin\Models\Box();
                    $boxModel->setModule($this->config['key']);
                    foreach ($this->config['boxes'] as $boxKey => $value) {
                        $boxModel->addContent($boxKey, $value);
                    }
                    $boxMapper->install($boxModel);
                }
                break;
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
