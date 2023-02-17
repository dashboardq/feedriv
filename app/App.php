<?php

namespace app;

use app\models\User;

class App {
    public $default_title = false;
    public $preset_title = false;

    public function init() {
        // Run migrations if the user is not running a command line command and the db needs to be migrated.
        if(!defined('AO_CONSOLE_START') && ao()->env('DB_USE') && ao()->env('DB_INSTALL')) {
            ao()->once('ao_db_loaded', [$this, 'install']);
        } 

        ao()->filter('ao_response_partial_args', [$this, 'cacheDate']);

        ao()->filter('ao_response_default_title', [$this, 'defaultTitle']);
        ao()->filter('ao_response_preset_title', [$this, 'presetTitle']);
        ao()->filter('app_html_head_title', [$this, 'htmlTitle']);

        ao()->filter('helper_wordify_output', [$this, 'wordify']);

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
            $vars['cache_date'] = '2022-07-15';
        }

        return $vars;
    }

    public function defaultTitle($title) {
        $this->preset_title = false;
        $this->default_title = true;
        return $title;
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

    // Uppercase the title
    public function wordify($input) {
        $output = str_replace('Feedriv', 'FeedRiv', $input);
        return $output;
    }
}
