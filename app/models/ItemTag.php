<?php

namespace app\models;

use mavoc\core\Model;

class ItemTag extends Model {
    public static $table = 'items_tags';

    public static function add($item_id, $tag_id) {
        $args = [];
        $args['item_id'] = $item_id;
        $args['tag_id'] = $tag_id;
        $item_tag = ItemTag::by($args);

        if(!$item_tag) {
            $item_tag = ItemTag::create($args);
        }

        return $item_tag;
    }


    public static function ids($item_id) {
        $output = [];

        $its = ItemTag::where('item_id', $item_id);
        foreach($its as $it) {
            $output[] = $it->data['tag_id'];
        }

        return $output;
    }

    public static function remove($item_id, $tag_id) {
        $args = [];
        $args['item_id'] = $item_id;
        $args['tag_id'] = $tag_id;
        ItemTag::delete($args);
    }

    public function tags($item_id, $return_type = 'all') {
        $output = [];
        $data = ao()->db->query('
            SELECT t.* 
            FROM tags t, items_tags it
            WHERE it.item_id = ?
            AND it.tag_id = t.id
        ', $item_id);

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
