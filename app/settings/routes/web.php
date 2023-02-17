<?php

use mavoc\core\Route;

Route::get('/', ['MainController', 'home']);
Route::get('pricing', ['MainController', 'pricing']);
Route::get('terms', ['MainController', 'terms']);
Route::get('privacy', ['MainController', 'privacy']);

Route::get('contact', ['ContactController', 'contact']);
Route::post('contact', ['ContactController', 'contactPost']);

Route::get('blog', ['BlogController', 'index']);
Route::get('rss', ['BlogController', 'rss']);
Route::get('blog/{slug}', ['BlogController', 'post']);


// Content pages used for development and listed in the blog.
Route::get('content/categories', ['ContentController', 'categories']);
Route::get('content/category-edit', ['ContentController', 'categoryEdit']);
Route::get('content/category-add', ['ContentController', 'categoryAdd']);
Route::get('content/color-edit', ['ContentController', 'colorEdit']);
Route::get('content/default-tag-add', ['ContentController', 'defaultTagAdd']);
Route::get('content/default-tag-edit', ['ContentController', 'defaultTagEdit']);
Route::get('content/default-color-edit', ['ContentController', 'defaultColorEdit']);
Route::get('content/feed-add', ['ContentController', 'feedAdd']);
Route::get('content/feed-edit', ['ContentController', 'feedEdit']);
Route::get('content/feed', ['ContentController', 'feed']);
Route::get('content/settings', ['ContentController', 'settings']);
Route::get('content/tag-add', ['ContentController', 'tagAdd']);
Route::get('content/tag-edit', ['ContentController', 'tagEdit']);
Route::get('content/word-add', ['ContentController', 'wordAdd']);
Route::get('content/word-edit', ['ContentController', 'wordEdit']);

// Design pages used for development and listed in the blog.
Route::get('design/categories', ['DesignController', 'categories']);
Route::get('design/category-edit', ['DesignController', 'categoryEdit']);
Route::get('design/category-add', ['DesignController', 'categoryAdd']);
Route::get('design/color-edit', ['DesignController', 'colorEdit']);
Route::get('design/default-tag-add', ['DesignController', 'defaultTagAdd']);
Route::get('design/default-tag-edit', ['DesignController', 'defaultTagEdit']);
Route::get('design/default-color-edit', ['DesignController', 'defaultColorEdit']);
Route::get('design/feed-add', ['DesignController', 'feedAdd']);
Route::get('design/feed-edit', ['DesignController', 'feedEdit']);
Route::get('design/feed', ['DesignController', 'feed']);
Route::get('design/settings', ['DesignController', 'settings']);
Route::get('design/tag-add', ['DesignController', 'tagAdd']);
Route::get('design/tag-edit', ['DesignController', 'tagEdit']);
Route::get('design/word-add', ['DesignController', 'wordAdd']);
Route::get('design/word-edit', ['DesignController', 'wordEdit']);

// Private
Route::get('account', ['AuthController', 'account'], 'private');
Route::post('account', ['AuthController', 'accountPost'], 'private');
Route::get('change-password', ['AuthController', 'changePassword'], 'private');
Route::post('change-password', ['AuthController', 'changePasswordPost'], 'private');
Route::post('logout', ['AuthController', 'logout'], 'private');


// Public
Route::get('forgot-password', ['AuthController', 'forgotPassword'], 'public');
Route::post('forgot-password', ['AuthController', 'forgotPasswordPost'], 'public');
Route::get('login', ['AuthController', 'login'], 'public');
Route::post('login', ['AuthController', 'loginPost'], 'public');
Route::post('register', ['AuthController', 'registerPost'], 'public');
Route::get('reset-password', ['AuthController', 'resetPassword'], 'public');
Route::post('reset-password', ['AuthController', 'resetPasswordPost'], 'public');


