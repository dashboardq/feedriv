<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `items_tags` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `item_id` bigint unsigned NOT NULL DEFAULT '0',
    `tag_id` bigint unsigned NOT NULL DEFAULT '0',
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
DROP TABLE `items_tags`;
SQL;

    $db->query($sql);
};
