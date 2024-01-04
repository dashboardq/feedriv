<?php

namespace app;

use app\models\Category;
use app\models\Feed;
use app\models\Item;
use app\models\Setting;
use app\models\Tag;
use app\models\User;

use DateTime;
use DateTimeZone;

class App {
    public $default_title = false;
    public $preset_title = false;
    public $debug = false;

    public $per_page = 20;

    public function init() {
        // Run migrations if the user is not running a command line command and the db needs to be migrated.
        if(!defined('AO_CONSOLE_START') && ao()->env('DB_USE') && ao()->env('DB_INSTALL')) {
            ao()->once('ao_db_loaded', [$this, 'install']);
        } 

        ao()->filter('ao_model_timezone', [$this, 'timezone']);

        ao()->filter('ao_response_partial_args', [$this, 'cacheDate']);
        ao()->filter('ao_response_partial_args', [$this, 'sidebars']);

        ao()->filter('ao_response_default_title', [$this, 'defaultTitle']);
        ao()->filter('ao_response_preset_title', [$this, 'presetTitle']);
        ao()->filter('app_html_head_title', [$this, 'htmlTitle']);

        ao()->filter('helper_wordify_output', [$this, 'wordify']);

        ao()->filter('ao_model_process_dates_timezone', [$this, 'processTimezone']);

        if($this->debug) {
            ao()->filter('ao_final_exception_redirect', [$this, 'finalException']);
        }
    }

    public function cacheDate($vars, $view) {
        if(
            $view == 'head' 
            || $view == 'foot'
            || $view == 'content/head'
            || $view == 'content/foot'
            || $view == 'design/head'
            || $view == 'design/foot'
        ) {
            $vars['cache_date'] = '2023-03-15';
        }

        return $vars;
    }

    public function defaultTitle($title) {
        $this->preset_title = false;
        $this->default_title = true;
        return $title;
    }

    public function finalException($redirect, $e) {
        echo 'died before redirect';
        dd($e);
    }

    public function install() {
        try {
            $count = User::count();
        } catch(\Exception $e) {
            //ao()->command('work');
            ao()->command('mig init');
            ao()->command('mig up');

            // Redirect to home page now that the database is installed.
            header('Location: /');
            exit;
        }
    } 

    public function htmlTitle($title) {
        $add_suffix_to_title = true;
        if($this->preset_title) {
            $title .= ' | ' . ao()->env('APP_NAME') . ' Feed Reader';
        } elseif($this->default_title) {
            $title .= ' Feed Reader';
        }

        return $title;
    }

    public function presetTitle($title) {
        $this->preset_title = true;
        $this->default_title = false;
        return $title;
    }

    public function processTimezone($timezone, $table) {
        // We don't want to end up in an infinite loop call Setting::get() over and over 
        if($table == 'settings') {
            return $timezone;
        }

        $timezone = Setting::get(ao()->request->user_id, 'timezone');
        return $timezone;
    }

    public function sidebars($vars, $view, $req, $res) {
        if($view == 'sidebar_left') {
            $feed_link = Setting::get($req->user_id, 'feed_link');
            $vars['feed_link'] = $feed_link;

            // Get all count
            $count = Item::count([
                'user_id' => $req->user_id,
                'archived' => 0,
            ]);

            $vars['categories'] = [];
            $vars['categories'][] = [
                'label' => 'All',
                'count' => $count,
                'link' => '/feeds/all',
                'feeds' => [],
                'id' => 0,
                'class' => ('/feeds/all' == $feed_link) ? 'active' : '',
            ];

            $categories = Category::where('user_id', $req->user_id);
            foreach($categories as $category) {
                $count = Item::whereCategoryCount($category->id);
                $feeds = Feed::where('category_id', $category->id);
                $vars['categories'][] = [
                    'label' => $category->data['name'],
                    'count' => $count,
                    'link' => '/feeds/category/' . $category->id,
                    'feeds' => $feeds,
                    'id' => $category->id,
                    'opened' => $category->data['opened'],
                    'class' => ('/feeds/category/' . $category->id == $feed_link) ? 'active' : '',
                ];
            }
        } elseif($view == 'sidebar_right') {
            $feed_link = Setting::get($req->user_id, 'feed_link');
            $feed_type = Setting::get($req->user_id, 'feed_type');
            $feed_id = Setting::get($req->user_id, 'feed_id');
            $feed_sort = Setting::get($req->user_id, 'feed_sort');
            $feed_filter = Setting::get($req->user_id, 'feed_filter');

            $vars['feed_link'] = $feed_link;

            // Get tags
            $vars['tags'] = [];
            $counts = [];
            $tags = Tag::where('user_id', $req->user_id);
            if($feed_type == 'archive') {
                foreach($tags as $i => $tag) {
                    $count = Item::whereTagCount($tag->id, ['user_id' => $req->user_id, 'archived' => 1]);
                    $tags[$i]->data['count'] = $count;
                }
            } elseif($feed_type == 'category') {
                foreach($tags as $i => $tag) {
                    $count = Item::whereCategoryCount($feed_id, ['tag_id' => $tag->id]);
                    $tags[$i]->data['count'] = $count;
                }
            } elseif($feed_type == 'feed') {
                foreach($tags as $i => $tag) {
                    $count = Item::whereTagCount($tag->id, ['feed_id' => $feed_id, 'archived' => 0]);
                    $tags[$i]->data['count'] = $count;
                }
            } else {
                foreach($tags as $i => $tag) {
                    $count = Item::whereTagCount($tag->id, ['user_id' => $req->user_id, 'archived' => 0]);
                    $tags[$i]->data['count'] = $count;
                }
            }

            foreach($tags as $i => $tag) {
                $vars['tags'][] = [
                    'label' => $tag->data['name'],
                    'count' => $tag->data['count'],
                    'link' => '/feeds/tag/' . $tag->id,
                    'class' => ('tag_' . $tag->id == $feed_filter) ? 'active' : '',
                    'active' => ('tag_' . $tag->id == $feed_filter) ? true : false,
                ];
            }

            // Get manual ratings info
            $vars['ratings'] = [];
            if($feed_type == 'archive') {
                $count_5 = Item::count(['rating' => 5, 'user_id' => $req->user_id, 'archived' => 1]);
                $count_4 = Item::count(['rating' => 4, 'user_id' => $req->user_id, 'archived' => 1]);
                $count_3 = Item::count(['rating' => 3, 'user_id' => $req->user_id, 'archived' => 1]);
                $count_2 = Item::count(['rating' => 2, 'user_id' => $req->user_id, 'archived' => 1]);
                $count_1 = Item::count(['rating' => 1, 'user_id' => $req->user_id, 'archived' => 1]);
                $count_0 = Item::count(['rating' => 0, 'user_id' => $req->user_id, 'archived' => 1]);
            } elseif($feed_type == 'category') {
                $count_5 = Item::whereCategoryCount($feed_id, ['rating' => 5]);
                $count_4 = Item::whereCategoryCount($feed_id, ['rating' => 4]);
                $count_3 = Item::whereCategoryCount($feed_id, ['rating' => 3]);
                $count_2 = Item::whereCategoryCount($feed_id, ['rating' => 2]);
                $count_1 = Item::whereCategoryCount($feed_id, ['rating' => 1]);
                $count_0 = Item::whereCategoryCount($feed_id, ['rating' => 0]);
            } elseif($feed_type == 'feed') {
                $count_5 = Item::count(['rating' => 5, 'feed_id' => $feed_id, 'archived' => 0]);
                $count_4 = Item::count(['rating' => 4, 'feed_id' => $feed_id, 'archived' => 0]);
                $count_3 = Item::count(['rating' => 3, 'feed_id' => $feed_id, 'archived' => 0]);
                $count_2 = Item::count(['rating' => 2, 'feed_id' => $feed_id, 'archived' => 0]);
                $count_1 = Item::count(['rating' => 1, 'feed_id' => $feed_id, 'archived' => 0]);
                $count_0 = Item::count(['rating' => 0, 'feed_id' => $feed_id, 'archived' => 0]);
            } else {
                $count_5 = Item::count(['rating' => 5, 'user_id' => $req->user_id, 'archived' => 0]);
                $count_4 = Item::count(['rating' => 4, 'user_id' => $req->user_id, 'archived' => 0]);
                $count_3 = Item::count(['rating' => 3, 'user_id' => $req->user_id, 'archived' => 0]);
                $count_2 = Item::count(['rating' => 2, 'user_id' => $req->user_id, 'archived' => 0]);
                $count_1 = Item::count(['rating' => 1, 'user_id' => $req->user_id, 'archived' => 0]);
                $count_0 = Item::count(['rating' => 0, 'user_id' => $req->user_id, 'archived' => 0]);
            }
            $vars['ratings'][5] = [
                'label' => 'Rated 5',
                'count' => $count_5,
                'link' => '/feeds/rated/5',
                'class' => ('rated_5' == $feed_filter) ? 'active' : '',
                'active' => ('rated_5' == $feed_filter) ? true : false,
            ];

            $vars['ratings'][4] = [
                'label' => 'Rated 4',
                'count' => $count_4,
                'link' => '/feeds/rated/4',
                'class' => ('rated_4' == $feed_filter) ? 'active' : '',
                'active' => ('rated_4' == $feed_filter) ? true : false,
            ];

            $vars['ratings'][3] = [
                'label' => 'Rated 3',
                'count' => $count_3,
                'link' => '/feeds/rated/3',
                'class' => ('rated_3' == $feed_filter) ? 'active' : '',
                'active' => ('rated_3' == $feed_filter) ? true : false,
            ];

            $vars['ratings'][2] = [
                'label' => 'Rated 2',
                'count' => $count_2,
                'link' => '/feeds/rated/2',
                'class' => ('rated_2' == $feed_filter) ? 'active' : '',
                'active' => ('rated_2' == $feed_filter) ? true : false,
            ];

            $vars['ratings'][1] = [
                'label' => 'Rated 1',
                'count' => $count_1,
                'link' => '/feeds/rated/1',
                'class' => ('rated_1' == $feed_filter) ? 'active' : '',
                'active' => ('rated_1' == $feed_filter) ? true : false,
            ];

            $vars['ratings'][0] = [
                'label' => 'Unrated',
                'count' => $count_0,
                'link' => '/feeds/rated/0',
                'class' => ('rated_0' == $feed_filter) ? 'active' : '',
                'active' => ('rated_0' == $feed_filter) ? true : false,
            ];

            // Get auto ratings info
            $vars['auto_ratings'] = [];
            if($feed_type == 'archive') {
                $count_4 = Item::count(['auto_rating_int2' => ['>=', 400], 'user_id' => $req->user_id, 'archived' => 1]);
                $count_3 = Item::count(['auto_rating_int2' => [['>=', 300], ['<', 400]], 'user_id' => $req->user_id, 'archived' => 1]);
                $count_2 = Item::count(['auto_rating_int2' => [['>=', 200], ['<', 300]], 'user_id' => $req->user_id, 'archived' => 1]);
                $count_1 = Item::count(['auto_rating_int2' => [['>=', 100], ['<', 200]], 'user_id' => $req->user_id, 'archived' => 1]);
                $count_0 = Item::count(['auto_rating_int2' => ['<', 100], 'user_id' => $req->user_id, 'archived' => 1]);
            } elseif($feed_type == 'category') {
                $count_4 = Item::whereCategoryCount($feed_id, ['auto_rating_int2' => ['>=', 400]]);
                $count_3 = Item::whereCategoryCount($feed_id, ['auto_rating_int2' => [['>=', 300], ['<', 400]]]);
                $count_2 = Item::whereCategoryCount($feed_id, ['auto_rating_int2' => [['>=', 200], ['<', 300]]]);
                $count_1 = Item::whereCategoryCount($feed_id, ['auto_rating_int2' => [['>=', 100], ['<', 200]]]);
                $count_0 = Item::whereCategoryCount($feed_id, ['auto_rating_int2' => ['<', 100]]);
            } elseif($feed_type == 'feed') {
                $count_4 = Item::count(['auto_rating_int2' => ['>=', 400], 'feed_id' => $feed_id, 'archived' => 0]);
                $count_3 = Item::count(['auto_rating_int2' => [['>=', 300], ['<', 400]], 'feed_id' => $feed_id, 'archived' => 0]);
                $count_2 = Item::count(['auto_rating_int2' => [['>=', 200], ['<', 300]], 'feed_id' => $feed_id, 'archived' => 0]);
                $count_1 = Item::count(['auto_rating_int2' => [['>=', 100], ['<', 200]], 'feed_id' => $feed_id, 'archived' => 0]);
                $count_0 = Item::count(['auto_rating_int2' => ['<', 100], 'feed_id' => $feed_id, 'archived' => 0]);
            } else {
                $count_4 = Item::count(['auto_rating_int2' => ['>=', 400], 'user_id' => $req->user_id, 'archived' => 0]);
                $count_3 = Item::count(['auto_rating_int2' => [['>=', 300], ['<', 400]], 'user_id' => $req->user_id, 'archived' => 0]);
                $count_2 = Item::count(['auto_rating_int2' => [['>=', 200], ['<', 300]], 'user_id' => $req->user_id, 'archived' => 0]);
                $count_1 = Item::count(['auto_rating_int2' => [['>=', 100], ['<', 200]], 'user_id' => $req->user_id, 'archived' => 0]);
                $count_0 = Item::count(['auto_rating_int2' => ['<', 100], 'user_id' => $req->user_id, 'archived' => 0]);
            }

            $vars['auto_ratings'][4] = [
                'label' => 'Rated 4-5',
                'count' => $count_4,
                'link' => '/feeds/auto/4-5',
                'class' => ('auto_rated_4-5' == $feed_filter) ? 'active' : '',
                'active' => ('auto_rated_4-5' == $feed_filter) ? true : false,
            ];

            $vars['auto_ratings'][3] = [
                'label' => 'Rated 3-4',
                'count' => $count_3,
                'link' => '/feeds/auto/3-4',
                'class' => ('auto_rated_3-4' == $feed_filter) ? 'active' : '',
                'active' => ('auto_rated_3-4' == $feed_filter) ? true : false,
            ];

            $vars['auto_ratings'][2] = [
                'label' => 'Rated 2-3',
                'count' => $count_2,
                'link' => '/feeds/auto/2-3',
                'class' => ('auto_rated_2-3' == $feed_filter) ? 'active' : '',
                'active' => ('auto_rated_2-3' == $feed_filter) ? true : false,
            ];

            $vars['auto_ratings'][1] = [
                'label' => 'Rated 1-2',
                'count' => $count_1,
                'link' => '/feeds/auto/1-2',
                'class' => ('auto_rated_1-2' == $feed_filter) ? 'active' : '',
                'active' => ('auto_rated_1-2' == $feed_filter) ? true : false,
            ];

            $vars['auto_ratings'][0] = [
                'label' => 'Unrated',
                'count' => $count_0,
                'link' => '/feeds/auto/0-1',
                'class' => ('auto_rated_0-1' == $feed_filter) ? 'active' : '',
                'active' => ('auto_rated_0-1' == $feed_filter) ? true : false,
            ];


            // Get archive info
            $count = Item::count([
                'user_id' => $req->user_id,
                'archived' => 1,
            ]);

            $vars['archive'] = [];
            $vars['archive']['count'] = $count;
        } elseif($view == 'sidebar_right') {
            $feed_link = Setting::get($req->user_id, 'feed_link');
            $feed_type = Setting::get($req->user_id, 'feed_type');
            $feed_id = Setting::get($req->user_id, 'feed_id');
            $feed_sort = Setting::get($req->user_id, 'feed_sort');
            $feed_filter = Setting::get($req->user_id, 'feed_filter');

            $vars['feed_link'] = $feed_link;

            // Get manual ratings info
            $vars['ratings'] = [];
            if($feed_type == 'archive') {
                $count = Item::count(['rating' => 5, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][5] = [
                    'label' => 'Rated 5',
                    'count' => $count,
                    'link' => '/feeds/rated/5',
                    'class' => ('rated_5' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 4, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][4] = [
                    'label' => 'Rated 4',
                    'count' => $count,
                    'link' => '/feeds/rated/4',
                    'class' => ('rated_4' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 3, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][3] = [
                    'label' => 'Rated 3',
                    'count' => $count,
                    'link' => '/feeds/rated/3',
                    'class' => ('rated_3' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 2, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][2] = [
                    'label' => 'Rated 2',
                    'count' => $count,
                    'link' => '/feeds/rated/2',
                    'class' => ('rated_2' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 1, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][1] = [
                    'label' => 'Rated 1',
                    'count' => $count,
                    'link' => '/feeds/rated/1',
                    'class' => ('rated_1' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 0, 'user_id' => $req->user_id, 'archived' => 1]);
                $vars['ratings'][0] = [
                    'label' => 'Unrated',
                    'count' => $count,
                    'link' => '/feeds/rated/0',
                    'class' => ('rated_0' == $feed_filter) ? 'active' : '',
                ];
            } elseif($feed_type == 'feed') {
                $count = Item::count(['rating' => 5, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][5] = [
                    'label' => 'Rated 5',
                    'count' => $count,
                    'link' => '/feeds/rated/5',
                    'class' => ('rated_5' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 4, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][4] = [
                    'label' => 'Rated 4',
                    'count' => $count,
                    'link' => '/feeds/rated/4',
                    'class' => ('rated_4' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 3, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][3] = [
                    'label' => 'Rated 3',
                    'count' => $count,
                    'link' => '/feeds/rated/3',
                    'class' => ('rated_3' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 2, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][2] = [
                    'label' => 'Rated 2',
                    'count' => $count,
                    'link' => '/feeds/rated/2',
                    'class' => ('rated_2' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 1, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][1] = [
                    'label' => 'Rated 1',
                    'count' => $count,
                    'link' => '/feeds/rated/1',
                    'class' => ('rated_1' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 0, 'feed_id' => $feed_id, 'archived' => 0]);
                $vars['ratings'][0] = [
                    'label' => 'Unrated',
                    'count' => $count,
                    'link' => '/feeds/rated/0',
                    'class' => ('rated_0' == $feed_filter) ? 'active' : '',
                ];
            } else {
                $count = Item::count(['rating' => 5, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][5] = [
                    'label' => 'Rated 5',
                    'count' => $count,
                    'link' => '/feeds/rated/5',
                    'class' => ('rated_5' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 4, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][4] = [
                    'label' => 'Rated 4',
                    'count' => $count,
                    'link' => '/feeds/rated/4',
                    'class' => ('rated_4' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 3, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][3] = [
                    'label' => 'Rated 3',
                    'count' => $count,
                    'link' => '/feeds/rated/3',
                    'class' => ('rated_3' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 2, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][2] = [
                    'label' => 'Rated 2',
                    'count' => $count,
                    'link' => '/feeds/rated/2',
                    'class' => ('rated_2' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 1, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][1] = [
                    'label' => 'Rated 1',
                    'count' => $count,
                    'link' => '/feeds/rated/1',
                    'class' => ('rated_1' == $feed_filter) ? 'active' : '',
                ];

                $count = Item::count(['rating' => 0, 'user_id' => $req->user_id, 'archived' => 0]);
                $vars['ratings'][0] = [
                    'label' => 'Unrated',
                    'count' => $count,
                    'link' => '/feeds/rated/0',
                    'class' => ('rated_0' == $feed_filter) ? 'active' : '',
                ];
            }




            // Get archive info
            $count = Item::count([
                'user_id' => $req->user_id,
                'archived' => 1,
            ]);

            $vars['archive'] = [];
            $vars['archive']['count'] = $count;
        }

        return $vars;
    }

    public function tz($dt, $format) {
        $timezone = Setting::get(ao()->request->user_id, 'timezone');
        $tz = new DateTimeZone($timezone);

        $output = $dt->setTimezone($tz)->format($format);

        return $output;
    }

    // Uppercase the title
    public function wordify($input) {
        $output = str_replace('Feedriv', 'FeedRiv', $input);
        return $output;
    }
}
