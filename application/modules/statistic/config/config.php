<?php

/**
 * @copyright Ilch 2
 * @package ilch
 */

namespace Modules\Statistic\Config;

class Config extends \Ilch\Config\Install
{
    public array $config = [
        'key' => 'statistic',
        'icon_small' => 'fa-solid fa-chart-pie',
        'system_module' => true,
        'languages' => [
            'de_DE' => [
                'name' => 'Statistik',
                'description' => 'Hier könnt ihr die Seiten Statistik einsehen.',
            ],
            'en_EN' => [
                'name' => 'Statistic',
                'description' => 'Here you can show the Site statistic.',
            ],
        ],
        'boxes' => [
            'online' => [
                'de_DE' => [
                    'name' => 'Online'
                ],
                'en_EN' => [
                    'name' => 'Online'
                ]
            ],
            'stats' => [
                'de_DE' => [
                    'name' => 'Statistik'
                ],
                'en_EN' => [
                    'name' => 'Statistic'
                ]
            ],
        ],
        'ilchCore' => '2.2.0',
        'phpVersion' => '7.4'
    ];

    public function install()
    {
        $this->db()->queryMulti($this->getInstallSql());

        $databaseConfig = new \Ilch\Config\Database($this->db());
        $databaseConfig->set('statistic_visibleStats', '1,1,1,1,1,1');

        $this->createScheduledTasks();
    }

    public function createScheduledTasks(): void
    {
        // [prefix]_scheduler_tasks is guaranteed to exist here:
        // - fresh installs: the install module installs system modules in a fixed
        //   order (admin first, statistic last);
        // - updates: the table was created with the 2.2.21 core release.
        //
        // ensureTask() is create-if-missing only: the task is created exactly once
        // (2.2.22) and never updated afterwards. The payload keys are overrides
        // only; the defaults live in \Modules\Statistic\Mappers\StatisticAggregate
        // (PHP), so changing defaults requires no re-registration.
        //
        // Exception: raw_retention_days is 0 on purpose (transitional). The
        // retention/purge phase is deferred: 2.2.22 ships the aggregator without
        // raw deletion so the raw-based totals stay stable until the
        // aggregate-backed reads exist. The follow-up release enables it in one
        // shot — PHP default raised to 365 and this frozen payload value flipped
        // 0 -> 365 via a one-time updateTask() call in that release's update case.
        (new \Ilch\Scheduler\Scheduler($this->db()))->ensureTask([
            'name'       => 'statistic.aggregateVisits',
            'handler'    => 'statistic.aggregateVisits',
            'type'       => \Ilch\Scheduler\Scheduler::TYPE_DAILY,
            'daily_time' => '02:00:00',
            'payload'    => [
                'daily_limit'        => 14,
                'monthly_limit'      => 6,
                'yearly_limit'       => 12,
                'recent_days'        => 7,
                'raw_retention_days' => 0,
                'purge_limit'        => 30,
            ],
        ]);
    }

    public function getInstallSql(): string
    {
        return 'CREATE TABLE IF NOT EXISTS `[prefix]_visits_online` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL DEFAULT 0,
                  `session_id` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `site` VARCHAR(255) NOT NULL,
                  `os` VARCHAR(255) NOT NULL,
                  `os_version` VARCHAR(255) NOT NULL,
                  `browser` VARCHAR(255) NOT NULL,
                  `browser_version` VARCHAR(255) NOT NULL,
                  `ip_address` VARCHAR(255) NOT NULL,
                  `lang` VARCHAR(11) NOT NULL,
                  `date_last_activity` DATETIME NOT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS `[prefix]_visits_stats` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `user_id` INT(11) NOT NULL DEFAULT 0,
                  `session_id` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os` VARCHAR(255) NOT NULL,
                  `os_version` VARCHAR(255) NOT NULL,
                  `browser` VARCHAR(255) NOT NULL,
                  `browser_version` VARCHAR(255) NOT NULL,
                  `ip_address` VARCHAR(255) NOT NULL,
                  `referer` VARCHAR(255) NOT NULL,
                  `lang` VARCHAR(11) NOT NULL,
                  `date` DATETIME NOT NULL,
                  PRIMARY KEY (`id`),
                  KEY `idx_date` (`date`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_daily (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `date` DATE NOT NULL,
                  `browser` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `browser_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `lang` VARCHAR(11) NOT NULL DEFAULT \'\',
                  `dim_hash` CHAR(32) NOT NULL DEFAULT \'\',
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_period_dim` (`date`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_monthly (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `month` DATE NOT NULL,
                  `browser` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `browser_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `lang` VARCHAR(11) NOT NULL DEFAULT \'\',
                  `dim_hash` CHAR(32) NOT NULL DEFAULT \'\',
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_period_dim` (`month`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_yearly (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `year` INT(11) NOT NULL,
                  `browser` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `browser_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `os_version` VARCHAR(255) NOT NULL DEFAULT \'\',
                  `lang` VARCHAR(11) NOT NULL DEFAULT \'\',
                  `dim_hash` CHAR(32) NOT NULL DEFAULT \'\',
                  `visits` INT(11) NOT NULL DEFAULT 0,
                  PRIMARY KEY (`id`),
                  UNIQUE KEY `uniq_period_dim` (`year`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_progress (
                  `level` VARCHAR(16) NOT NULL,
                  `frontier` DATE NOT NULL,
                  `updated` DATETIME NOT NULL,
                  PRIMARY KEY (`level`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    }

    public function getUpdate(string $installedVersion): string
    {
        switch ($installedVersion) {
            case "2.1.19":
                // Add user_id and session_id to all visits stats.
                $this->db()->query('ALTER TABLE `[prefix]_visits_stats` ADD COLUMN `user_id` INT(11) NOT NULL DEFAULT 0 AFTER `id`;');
                $this->db()->query('ALTER TABLE `[prefix]_visits_stats` ADD COLUMN `session_id` VARCHAR(255) NOT NULL DEFAULT \'\' AFTER `user_id`;');
                break;
            case "2.1.51":
                $this->db()->query("UPDATE `[prefix]_modules` SET `icon_small` = '" . $this->config['icon_small'] . "' WHERE `key` = '" . $this->config['key'] . "';");
                break;
            case "2.2.21":
                // Visit statistics aggregation: aggregate tables + date index
                // (all idempotent), then register the daily aggregation task.
                $this->db()->queryMulti("CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_daily (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `date` DATE NOT NULL,
                    `browser` VARCHAR(255) NOT NULL DEFAULT '',
                    `browser_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `os` VARCHAR(255) NOT NULL DEFAULT '',
                    `os_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `lang` VARCHAR(11) NOT NULL DEFAULT '',
                    `dim_hash` CHAR(32) NOT NULL DEFAULT '',
                    `visits` INT(11) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uniq_period_dim` (`date`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_monthly (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `month` DATE NOT NULL,
                    `browser` VARCHAR(255) NOT NULL DEFAULT '',
                    `browser_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `os` VARCHAR(255) NOT NULL DEFAULT '',
                    `os_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `lang` VARCHAR(11) NOT NULL DEFAULT '',
                    `dim_hash` CHAR(32) NOT NULL DEFAULT '',
                    `visits` INT(11) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uniq_period_dim` (`month`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_yearly (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `year` INT(11) NOT NULL,
                    `browser` VARCHAR(255) NOT NULL DEFAULT '',
                    `browser_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `os` VARCHAR(255) NOT NULL DEFAULT '',
                    `os_version` VARCHAR(255) NOT NULL DEFAULT '',
                    `lang` VARCHAR(11) NOT NULL DEFAULT '',
                    `dim_hash` CHAR(32) NOT NULL DEFAULT '',
                    `visits` INT(11) NOT NULL DEFAULT 0,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uniq_period_dim` (`year`, `dim_hash`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

                CREATE TABLE IF NOT EXISTS [prefix]_visits_stats_progress (
                    `level` VARCHAR(16) NOT NULL,
                    `frontier` DATE NOT NULL,
                    `updated` DATETIME NOT NULL,
                    PRIMARY KEY (`level`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Guarded index creation (same pattern as the admin 2.2.20 update):
                // the [prefix]_ token is rewritten by Mysql::getSqlWithPrefix().
                $fileConfig = new \Ilch\Config\File();
                $fileConfig->loadConfigFromFile(CONFIG_PATH . '/config.php');
                $dbName = (string)$fileConfig->get('dbName');

                $hasIndex = $this->db()->queryCell(
                    "SELECT EXISTS (SELECT 1 FROM information_schema.statistics
                        WHERE table_schema = '" . $dbName . "'
                          AND table_name = '[prefix]_visits_stats'
                          AND index_name = 'idx_date')"
                );
                if (!$hasIndex) {
                    $this->db()->query('CREATE INDEX `idx_date` ON [prefix]_visits_stats (`date`);');
                }

                $this->createScheduledTasks();
                break;
        }

        return '"' . $this->config['key'] . '" Update-function executed.';
    }
}
