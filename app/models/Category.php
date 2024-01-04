<?php

namespace app\models;

use mavoc\core\Model;

class Category extends Model {
    public static $table = 'categories';
    public static $order = ['sort_order' => 'desc'];

    public static function create($args) {
        $category = parent::create($args);
        $user_id = $category->data['user_id'];

        $tags = Tag::where(['user_id' => $user_id, 'default' => 1]);
        foreach($tags as $tag) {
            $args = [];
            $args['category_id'] = $category->id;
            $args['tag_id'] = $tag->id;
            CategoryTag::create($args);
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

    public static function delete($id) {
        $feeds = Feed::where('category_id', $id);
        if(count($feeds)) {
            ao()->response->error('The Category cannot be deleted until the feeds associated with the category have been deleted.');
        }

        parent::delete($id);
    }
}
