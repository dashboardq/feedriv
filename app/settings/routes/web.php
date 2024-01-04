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



Route::post('ajax/category-sort', ['AjaxController', 'categorySort'], 'private');
Route::post('ajax/feed-sort/{category_id}', ['AjaxController', 'feedSort'], 'private');
Route::post('ajax/sort', ['AjaxController', 'sort'], 'private');
Route::post('ajax/toggle/{category_id}', ['AjaxController', 'toggle'], 'private');

Route::post('ajax/archive', ['AjaxController', 'archiveAll'], 'private');
Route::post('ajax/archive/{item_id}', ['AjaxController', 'archive'], 'private');
Route::post('ajax/rate/{item_id}', ['AjaxController', 'rate'], 'private');
Route::post('ajax/refresh', ['AjaxController', 'refresh'], 'private');
Route::post('ajax/refresh/{feed_id}', ['AjaxController', 'refreshFeed'], 'private');
Route::post('ajax/tag', ['AjaxController', 'tag'], 'private');


Route::get('auto-rating/add/{category_id}', ['AutoRatingsController', 'add'], 'private');
Route::post('auto-rating/add/{category_id}', ['AutoRatingsController', 'create'], 'private');

Route::get('auto-rating/edit/{id}', ['AutoRatingsController', 'edit'], 'private');
Route::post('auto-rating/edit/{id}', ['AutoRatingsController', 'update'], 'private');

Route::post('auto-rating/delete/{id}', ['AutoRatingsController', 'delete'], 'private');


Route::get('categories', ['CategoriesController', 'categories'], 'private');

Route::get('category/add', ['CategoriesController', 'add'], 'private');
Route::post('category/add', ['CategoriesController', 'create'], 'private');

Route::get('category/edit/{id}', ['CategoriesController', 'edit'], 'private');
Route::post('category/edit/{id}', ['CategoriesController', 'update'], 'private');

Route::post('category/delete/{id}', ['CategoriesController', 'delete'], 'private');


Route::get('color/edit/{id}', ['ColorsController', 'edit'], 'private');
Route::post('color/edit/{id}', ['ColorsController', 'update'], 'private');


Route::get('default-color/edit/{id}', ['DefaultColorsController', 'edit'], 'private');
Route::post('default-color/edit/{id}', ['DefaultColorsController', 'update'], 'private');


/*
Route::get('default-tag/add', ['DefaultTagsController', 'add'], 'private');
Route::post('default-tag/add', ['DefaultTagsController', 'create'], 'private');

Route::get('default-tag/edit/{id}', ['DefaultTagsController', 'edit'], 'private');
Route::post('default-tag/edit/{id}', ['DefaultTagsController', 'update'], 'private');

Route::post('default-tag/delete/{id}', ['DefaultTagsController', 'delete'], 'private');
 */


Route::get('feeds', ['FeedsController', 'feeds'], 'private');

Route::get('feed/add', ['FeedsController', 'add'], 'private');
Route::get('feed/add/{category_id}', ['FeedsController', 'add'], 'private');
Route::post('feed/add', ['FeedsController', 'create'], 'private');
Route::post('feed/add/{category_id}', ['FeedsController', 'create'], 'private');

Route::get('feed/edit/{id}', ['FeedsController', 'edit'], 'private');
Route::post('feed/edit/{id}', ['FeedsController', 'update'], 'private');

Route::post('feed/delete/{id}', ['FeedsController', 'delete'], 'private');

Route::get('feeds/all', ['AppController', 'all'], 'private');
Route::get('feeds/category/{id}', ['AppController', 'category'], 'private');
Route::get('feeds/tag/{id}', ['AppController', 'tag'], 'private');
Route::get('feeds/feed/{id}', ['AppController', 'feed'], 'private');
Route::get('feeds/rated/{rating}', ['AppController', 'rated'], 'private');
Route::get('feeds/auto/{range}', ['AppController', 'auto'], 'private');
Route::get('feeds/archive', ['AppController', 'archive'], 'private');
Route::get('feeds/clear', ['AppController', 'clear'], 'private');


Route::get('settings', ['SettingsController', 'settings'], 'private');
Route::post('settings', ['SettingsController', 'update'], 'private');



Route::get('tag/add', ['TagsController', 'add'], 'private');
Route::post('tag/add', ['TagsController', 'create'], 'private');

Route::get('tag/edit/{id}', ['TagsController', 'edit'], 'private');
Route::post('tag/edit/{id}', ['TagsController', 'update'], 'private');

Route::post('tag/delete/{id}', ['TagsController', 'delete'], 'private');

Route::get('tag/modify/{category_id}', ['TagsController', 'modify'], 'private');
Route::post('tag/modify/{category_id}', ['TagsController', 'modifyPost'], 'private');

Route::get('tag/modify-defaults', ['TagsController', 'modifyDefaults'], 'private');
Route::post('tag/modify-defaults', ['TagsController', 'modifyDefaultsPost'], 'private');



// Public
Route::get('forgot-password', ['AuthController', 'forgotPassword'], 'public');
Route::post('forgot-password', ['AuthController', 'forgotPasswordPost'], 'public');
Route::get('login', ['AuthController', 'login'], 'public');
Route::post('login', ['AuthController', 'loginPost'], 'public');
Route::post('register', ['AuthController', 'registerPost'], 'public');
Route::get('reset-password', ['AuthController', 'resetPassword'], 'public');
Route::post('reset-password', ['AuthController', 'resetPasswordPost'], 'public');


