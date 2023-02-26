<?php

// Up
$up = function($db) {
    $sql = <<<'SQL'
CREATE TABLE `auto_ratings` (
    `id` bigint unsigned NOT NULL AUTO_INCREMENT,
    `category_id` bigint unsigned NOT NULL DEFAULT '0',
    `word` varchar(255) NOT NULL DEFAULT '',
    `use_count` int NOT NULL DEFAULT '0',
    `sum_score` int NOT NULL DEFAULT '0',
    `avg_score_int2` int NOT NULL DEFAULT '0',
    `locked_score_int2` int NOT NULL DEFAULT '0',
    `locked` tinyint(1) NOT NULL DEFAULT '0',
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
DROP TABLE `auto_ratings`;
SQL;

    $db->query($sql);
};
