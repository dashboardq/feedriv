<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `feeds` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `category_id` bigint unsigned NOT NULL DEFAULT '0',
    `shared_feed_id` bigint unsigned NOT NULL DEFAULT '0',
    `original_url` varchar(255) NOT NULL DEFAULT '',
    `real_url` varchar(255) NOT NULL DEFAULT '',
    `title` varchar(255) NOT NULL DEFAULT '',
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
DROP TABLE `feeds`;
SQL;

    $db->query($sql);
};
