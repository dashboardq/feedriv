<?php

use mavoc\console\Route;

Route::command('refresh', ['ConsoleController', 'refresh']);

Route::command('example', ['ConsoleController', 'example']);
Route::command('view', ['ConsoleController', 'view']);

