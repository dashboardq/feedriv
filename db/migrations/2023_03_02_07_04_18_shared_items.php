<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `shared_items` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `shared_feed_id` bigint unsigned NOT NULL DEFAULT '0',
    `title` varchar(255) NOT NULL DEFAULT '',
    `link` varchar(255) NOT NULL DEFAULT '',
    `guid` varchar(255) NOT NULL DEFAULT '',
    `pub_date` varchar(255) NOT NULL DEFAULT '',
    `description` longtext,
    `type` varchar(255) NOT NULL DEFAULT 'basic',
    `published_at` timestamp NULL DEFAULT NULL,
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
DROP TABLE `shared_items`;
SQL;

    $db->query($sql);
};
