<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `items` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `feed_id` bigint unsigned NOT NULL DEFAULT '0',
    `shared_item_id` bigint unsigned NOT NULL DEFAULT '0',
    `auto_rating_int2` int NOT NULL DEFAULT '0',
    `rating` int NOT NULL DEFAULT '0',
    `archived` tinyint(1) NOT NULL DEFAULT '0',
    `status` varchar(255) NOT NULL DEFAULT 'initialized',
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
DROP TABLE `items`;
SQL;

    $db->query($sql);
};
