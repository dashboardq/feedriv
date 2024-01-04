<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `subscriptions` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL DEFAULT '0',
    `base_plan` varchar(255) NOT NULL DEFAULT '',
    `status` varchar(255) NOT NULL DEFAULT '',
    `expires_at` timestamp NULL DEFAULT NULL,
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
DROP TABLE `subscriptions`;
SQL;

    $db->query($sql);
};
