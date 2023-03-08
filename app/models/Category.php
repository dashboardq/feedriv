<?php

namespace app\models;

use mavoc\core\Model;

class Category extends Model {
    public static $table = 'categories';
    public static $order = ['sort_order' => 'desc'];

    public static function create($args) {
        $category = parent::create($args);
        $user_id = $category->data['user_id'];

        $default_tags = DefaultTag::where('user_id', $user_id);
        foreach($default_tags as $default_tag) {
            $args = [];
            $args['user_id'] = $user_id;
            $args['category_id'] = $category->id;
            $args['name'] = $default_tag->data['name'];
            Tag::create($args);
        }

        $default_colors = DefaultColor::where('user_id', $user_id);
        foreach($default_colors as $default_color) {
            $args = [];
            $args['user_id'] = $user_id;
            $args['category_id'] = $category->id;
            $args['range'] = $default_color->data['range'];
            $args['color'] = $default_color->data['color'];
            Color::create($args);
        }

        return $category;
    }

}
