<?php

// Up
$up = function($db) {
    // It looks like multiple calls cause a problem so calling individually.
    //$sql = <<<'SQL'
//ALTER TABLE `shared_feeds` MODIFY url varchar(255); 
//ALTER TABLE `shared_feeds` MODIFY link varchar(255); 
//ALTER TABLE `feeds` MODIFY original_url text;
//ALTER TABLE `shared_items` MODIFY link text;
//ALTER TABLE `shared_items` MODIFY guid text;
//SQL;
    
    //$db->query($sql);

    $db->query('ALTER TABLE `shared_feeds` MODIFY url text;');
    $db->query('ALTER TABLE `shared_feeds` MODIFY link text;');
    $db->query('ALTER TABLE `feeds` MODIFY original_url text;');
    $db->query('ALTER TABLE `shared_items` MODIFY link text;');
    $db->query('ALTER TABLE `shared_items` MODIFY guid text;');
};

// Down
$down = function($db) {
    // It looks like multiple calls cause a problem so calling individually.
    //$sql = <<<'SQL'
//ALTER TABLE `shared_feeds` MODIFY url varchar(255); 
//ALTER TABLE `shared_feeds` MODIFY link varchar(255); 
//ALTER TABLE `feeds` MODIFY original_url varchar(255);
//ALTER TABLE `shared_items` MODIFY link varchar(255);
//ALTER TABLE `shared_items` MODIFY guid varchar(255);
//SQL;

    //$db->query($sql);

    $db->query('ALTER TABLE `shared_feeds` MODIFY url varchar(255);');
    $db->query('ALTER TABLE `shared_feeds` MODIFY link varchar(255);');
    $db->query('ALTER TABLE `feeds` MODIFY original_url varchar(255);');
    $db->query('ALTER TABLE `shared_items` MODIFY link varchar(255);');
    $db->query('ALTER TABLE `shared_items` MODIFY guid varchar(255);');
};
