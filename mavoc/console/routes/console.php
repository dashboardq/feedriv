<?php

use mavoc\console\Route;

Route::command('help', ['\mavoc\console\controllers\MainController', 'help']);
Route::command('work', ['\mavoc\console\controllers\MainController', 'work']);
Route::command('works', ['\mavoc\console\controllers\MainController', 'work']);

Route::command('gen keys', ['\mavoc\console\controllers\GenController', 'keys']);

Route::command('mig alter', ['\mavoc\console\controllers\MigController', 'alter']);
Route::command('mig down', ['\mavoc\console\controllers\MigController', 'down']);
Route::command('mig init', ['\mavoc\console\controllers\MigController', 'init']);
Route::command('mig new', ['\mavoc\console\controllers\MigController', 'new']);
Route::command('mig up', ['\mavoc\console\controllers\MigController', 'up']);

