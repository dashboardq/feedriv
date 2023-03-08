<?php

namespace app\models;

use mavoc\core\Model;


class Setting extends Model {
    public static $table = 'settings';
    public static $order = ['name' => 'asc'];

    public static $defaults = [
        'category' => [
            'name' => 'Category',
            'key' => 'category',
            'value' => 'all',
            'editable' => 0,
        ],
        'show_tags' => [
            'name' => 'Show Tags',
            'key' => 'show_tags',
            'value' => 1,
            'editable' => 1,
        ],
        'show_ratings' => [
            'name' => 'Show Ratings',
            'key' => 'show_ratings',
            'value' => 1,
            'editable' => 1,
        ],
        'show_colors' => [
            'name' => 'Show Colors',
            'key' => 'show_colors',
            'value' => 0,
            'editable' => 1,
        ],
        'save_ratings' => [
            'name' => 'Save Ratings',
            'key' => 'save_ratings',
            'value' => 1,
            'editable' => 1,
        ],
        'sort' => [
            'name' => 'Sort',
            'key' => 'sort',
            'value' => 'date-desc',
            'editable' => 0,
        ],
        'timezone' => [
            'name' => 'Timezone',
            'key' => 'timezone',
            'value' => 'UTC',
            'editable' => 1,
        ],  

        'feed_link' => [
            'name' => 'Feed Link',
            'key' => 'feed_link',
            'value' => '/feeds/all',
            'editable' => 0,
        ],  
        'feed_type' => [
            'name' => 'Feed Type',
            'key' => 'feed_type',
            'value' => 'all',
            'editable' => 0,
        ],  
        'feed_id' => [
            'name' => 'Feed ID',
            'key' => 'feed_id',
            'value' => '0',
            'editable' => 0,
        ],  
        'filter_link' => [
            'name' => 'Filter Link',
            'key' => 'filter_link',
            'value' => '',
            'editable' => 0,
        ],  
    ];

    public static function get($user_id = 0, $key = null) {
        $output = null;

        if(is_array($key)) {
            $results = Setting::where('user_id', $user_id, 'data');
            $settings = [];
            foreach($results as $item) {
                if(in_array($item['key'], $key)) {
                    $settings[$item['key']] = $item['value'];
                }
            }

            // Set defaults
            foreach(self::$defaults as $default) {
                if(in_array($default['key'], $key) && !isset($settings[$default['key']])) {
                    $settings[$default['key']] = $default['value'];
                }
            }

            $output = $settings;
        } elseif($key) {
            $result = Setting::by(['user_id' => $user_id, 'key' => $key], '', 'data');

            if($result) {
                $output = $result['value'];
            } elseif(isset(self::$defaults[$key])) {
                $output = self::$defaults[$key]['value'];
            }
        } else {
            $results = Setting::where('user_id', $user_id, 'data');
            $settings = [];
            foreach($results as $item) {
                $settings[$item['key']] = $item['value'];
            }

            // Set defaults
            foreach(self::$defaults as $default) {
                if(!isset($settings[$default['key']])) {
                    $settings[$default['key']] = $default['value'];
                }
            }

            $output = $settings;
        }

        return $output;
    }

    public static function set($user_id = 0, $key = null, $value = null) {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $item = Setting::by(['user_id' => $user_id, 'key' => $k]);
                if($item) {
                    $item->data['value'] = $v;
                    $item->save();
                } else {
                    $item = Setting::create([
                        'user_id' => $user_id, 
                        'name' => self::$defaults[$k]['name'], 
                        'editable' => self::$defaults[$k]['editable'], 
                        'key' => $k, 
                        'value' => $v,
                    ]);
                }
            }
        } else {
            $item = Setting::by(['user_id' => $user_id, 'key' => $key]);
            if($item) {
                $item->data['value'] = $value;
                $item->save();
            } else {
                $item = Setting::create([
                    'user_id' => $user_id, 
                    'name' => self::$defaults[$key]['name'], 
                    'editable' => self::$defaults[$key]['editable'], 
                    'key' => $key, 
                    'value' => $value,
                ]);
            }
        }
    }
}
