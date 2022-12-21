<?php

use mavoc\console\Route;

Route::command('example', ['ConsoleController', 'example']);
Route::command('view', ['ConsoleController', 'view']);

