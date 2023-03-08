<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `categories` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `parent_id` bigint unsigned NOT NULL DEFAULT '0',
    `name` varchar(255) NOT NULL DEFAULT '',
    `show_tags` tinyint(1) NOT NULL DEFAULT '1',
    `show_ratings` tinyint(1) NOT NULL DEFAULT '1',
    `show_colors` tinyint(1) NOT NULL DEFAULT '0',
    `save_ratings` tinyint(1) NOT NULL DEFAULT '1',
    `opened` tinyint(1) NOT NULL DEFAULT '1',
    `sort_order` int NOT NULL DEFAULT '0',
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
DROP TABLE `categories`;
SQL;

    $db->query($sql);
};
