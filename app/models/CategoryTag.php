<?php

namespace app\models;

use mavoc\core\Model;

class CategoryTag extends Model {
    public static $table = 'categories_tags';
    public static $order = ['sort_order' => 'desc'];

    public static function add($category_id, $tag_id) {
        $args = [];
        $args['category_id'] = $category_id;
        $args['tag_id'] = $tag_id;
        $category_tag = CategoryTag::by($args);

        if(!$category_tag) {
            $category_tag = CategoryTag::create($args);
        }

        return $category_tag;
    }

    public static function ids($category_id) {
        $output = [];

        $cts = CategoryTag::where('category_id', $category_id);
        foreach($cts as $ct) {
            $output[] = $ct->data['tag_id'];
        }

        return $output;
    }

    public static function remove($category_id, $tag_id) {
        $args = [];
        $args['category_id'] = $category_id;
        $args['tag_id'] = $tag_id;
        CategoryTag::delete($args);
    }

    public static function tags($category_id, $return_type = 'all') {
        $output = [];
        $data = ao()->db->query('
            SELECT t.* 
            FROM tags t, categories_tags ct
            WHERE ct.category_id = ?
            AND ct.tag_id = t.id
        ', $category_id);

        foreach($data as $item) {
            if($return_type == 'data') {
                $item = new Tag($item);
                $output[] = $item->data;
            } else {
                $output[] = new Tag($item);
            }
        }

        return $output;
    }
}
