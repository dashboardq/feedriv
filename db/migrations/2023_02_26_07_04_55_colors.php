<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `colors` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `category_id` bigint unsigned NOT NULL DEFAULT '0',
    `range` varchar(255) NOT NULL DEFAULT '',
    `color` varchar(255) NOT NULL DEFAULT '',
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
DROP TABLE `colors`;
SQL;

    $db->query($sql);
};
