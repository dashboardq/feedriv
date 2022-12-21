<?php

define('AO_START', microtime(true));

require __DIR__.'/../mavoc/Boot.php';

$ao = null;

$boot = new mavoc\Boot();
$boot->init();
