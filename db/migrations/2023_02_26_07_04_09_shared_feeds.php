<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `shared_feeds` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `real_url` varchar(255) NOT NULL DEFAULT '',
    `last_updated_at` timestamp NULL DEFAULT NULL,
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
SQL;

    $db->query($sql);
};

// Down
$down = function($db) {
    $sql = <<<'SQL'
DROP TABLE `shared_feeds`;
SQL;

    $db->query($sql);
};
