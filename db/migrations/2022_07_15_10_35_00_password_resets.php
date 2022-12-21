<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `password_resets` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `user_id` bigint unsigned NOT NULL,
    `token` varchar(255) NOT NULL,
    `created_ip` varchar(255) NOT NULL DEFAULT '',
    `used_ip` varchar(255) NOT NULL DEFAULT '',
    `used` tinyint(1) NOT NULL DEFAULT '0',
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
DROP TABLE `password_resets`;
SQL;
    $db->query($sql);

};
