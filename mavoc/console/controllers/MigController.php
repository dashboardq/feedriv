<?php

namespace mavoc\console\controllers;

use DateTime;

// TODO: Need to move this to core
class MigController {
    public $db;

    public function __construct() {
        $this->db = ao()->db;
    }   

    public function alter($in, $out) {
        $dir = ao()->env('AO_DB_DIR') . DIRECTORY_SEPARATOR . 'migrations';
        if(!is_dir($dir)) {
            out('Error: ' . 'The db/migrations directory does not appear to exist. Please create it.', 'red');
            exit(1);
        }

        // Create the new migration file
        // Date time underscored passed in info

        $today = new DateTime();
        $file = '';
        $file .= $today->format('Y_m_d_H_i_s_');
        $file .= implode('_', $in->params);
        $file .= '.php';

        // Check if the file already exists
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_file($path)) {
            out('Error: ' . 'The migration already appears to exist. Please try again.', 'red');
            exit(1);
        }

        if(count($in->params) == 1) {
            $table = $in->params[0];
        } else {
            $table = 'example';
        }

$content = <<<PHP
<?php

// Up
\$up = function(\$db) {
    \$sql = <<<'SQL'
ALTER TABLE `$table` RENAME COLUMN old_col_name TO new_col_name;
ALTER TABLE `$table`

  ADD COLUMN `user_id` bigint unsigned NOT NULL DEFAULT '0',
  ADD COLUMN `name` varchar(255) NOT NULL DEFAULT '',
  ADD COLUMN `content` longtext,
  ADD COLUMN `total_cents` int NOT NULL DEFAULT '0',
  ADD COLUMN `quantity_int2` int NOT NULL DEFAULT '0',
  ADD COLUMN `primary` tinyint(1) NOT NULL DEFAULT '0',
  ADD COLUMN `expires_at` timestamp NULL DEFAULT NULL;
SQL;

    \$db->query(\$sql);
};

// Down
\$down = function(\$db) {
    \$sql = <<<'SQL'
ALTER TABLE `$table` RENAME COLUMN new_col_name TO old_col_name;
ALTER TABLE `$table`

  DROP COLUMN `user_id`,
  DROP COLUMN `name`;
SQL;

    \$db->query(\$sql);
};

PHP;

        file_put_contents($path, $content);


        out('The migration has been created: ' . $file, 'green');
    }

    // mig up is greedy (all at once) while mig down is stingy (one at a time)
    public function down($in, $out) {
        $dir = ao()->env('AO_DB_DIR') . DIRECTORY_SEPARATOR . 'migrations';
        if(!is_dir($dir)) {
            out('Error: ' . 'The db/migrations directory does not appear to exist. Please create it.', 'red');
            exit(1);
        }

        // Get database migrations
        $results = $this->db->query('SELECT * FROM _migrations ORDER BY id DESC LIMIT 1');

        $run_count = 0;
        if(count($results)) {
            // TODO: Need some error checking
            // Run the migration
            include $dir . DIRECTORY_SEPARATOR . $results[0]['migration'];
            $down($this->db);

            $query = $this->db->query('DELETE FROM _migrations WHERE id = ?', $results[0]['id']);

            // Output
            out('Down - Migration complete: ' . $results[0]['migration'], 'green');
            $run_count++;
        }

        if($run_count) {
            out(pluralize($run_count, 'migration') . ' ran.', 'green');
        } else {
            out('No migrations to run.', 'red');
        }
    }

    public function init($in, $out) {
        try {
            // Set up _migrations table.
            $sql = <<<'SQL'
CREATE TABLE `_migrations` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;
            //$this->pdo->exec($sql);
            $this->db->query($sql);

            // Set up _deletions table.
            // Inspired by: https://brandur.org/soft-deletion
            // With discussion: https://news.ycombinator.com/item?id=32156009
            $sql = <<<'SQL'
CREATE TABLE `_deletions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `original_id` bigint unsigned NOT NULL,
    `original_table` varchar(255) NOT NULL,
    `data` longtext NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;
            //$this->pdo->exec($sql);
            $this->db->query($sql);

            // Set up _logs table.
            $sql = <<<'SQL'
CREATE TABLE `_logs` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `original_id` bigint unsigned NOT NULL,
    `type` varchar(255) NOT NULL,
    `action` varchar(255) NOT NULL,
    `description` longtext NOT NULL,
    `data` longtext NOT NULL,
    `extra` longtext NOT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;
            //$this->pdo->exec($sql);
            $this->db->query($sql);
        } catch(\PDOException $e) {
            //var_dump($e);
            out('Error: ' . $e->getMessage(), 'red');
            exit(1);
        }

        out('The base tables have been set up in the database: _migrations, _deletions, _logs', 'green');
    }

    public function new($in, $out) {
        $dir = ao()->env('AO_DB_DIR') . DIRECTORY_SEPARATOR . 'migrations';
        if(!is_dir($dir)) {
            out('Error: ' . 'The db/migrations directory does not appear to exist. Please create it.', 'red');
            exit(1);
        }

        // Create the new migration file
        // Date time underscored passed in info

        $today = new DateTime();
        $file = '';
        $file .= $today->format('Y_m_d_H_i_s_');
        $file .= implode('_', $in->params);
        $file .= '.php';

        // Check if the file already exists
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_file($path)) {
            out('Error: ' . 'The migration already appears to exist. Please try again.', 'red');
            exit(1);
        }

        if(count($in->params) == 1) {
            $table = $in->params[0];
        } else {
            $table = 'example';
        }

$content = <<<PHP
<?php

// Up
\$up = function(\$db) {
    \$sql = <<<'SQL'
CREATE TABLE `$table` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,

    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL DEFAULT '',
    `content` longtext,
    `total_cents` int NOT NULL DEFAULT '0',
    `quantity_int2` int NOT NULL DEFAULT '0',
    `extra` longtext,
    `default` tinyint(1) NOT NULL DEFAULT '0',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;

    \$db->query(\$sql);
};

// Down
\$down = function(\$db) {
    \$sql = <<<'SQL'
DROP TABLE `$table`;
SQL;

    \$db->query(\$sql);
};

PHP;

        file_put_contents($path, $content);


        out('The migration has been created: ' . $file, 'green');
    }

    // mig up is greedy (all at once) while mig down is stingy (one at a time)
    public function up($in, $out) {
        $dir = ao()->env('AO_DB_DIR') . DIRECTORY_SEPARATOR . 'migrations';
        if(!is_dir($dir)) {
            out('Error: ' . 'The db/migrations directory does not appear to exist. Please create it.', 'red');
            exit(1);
        }

        $migrations = [];
        foreach(scandir($dir) as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $path = ao()->hook('ao_router_path', $path);
            if(is_file($path)) {
                $migrations[] = [
                    'file' => $file,
                    'path' => $path,
                ];
            }
        }

        // Probably need to sort migrations

        // Get database migrations
        $results = $this->db->query('SELECT * FROM _migrations');
        //print_r($results);die;

        // Place into an associated array based on migration name (pdo might be able to do this)
        $past = [];
        foreach($results as $row) {
            $past[$row['migration']] = true;
        }

        $run_count = 0;
        // Loop through and process the ones that are not in the database
        foreach($migrations as $migration) {
            if(!isset($past[$migration['file']])) {
                // TODO: Need to add some error checking

                // Run the migration
                include $migration['path'];
                $up($this->db);

                $query = $this->db->query('INSERT INTO _migrations SET migration = ?, created_at = ?, updated_at = ?', $migration['file'], now(), now());


                // Output
                out('Up - Migration complete: ' . $migration['file'], 'green');
                $run_count++;
            }
        }

        if($run_count) {
            out(pluralize($run_count, 'migration') . ' ran.', 'green');
        } else {
            out('No migrations to run.', 'red');
        }
    }

    public function process($migration) {
    }

}

