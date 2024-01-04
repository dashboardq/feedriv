<?php

namespace app\models;

use mavoc\core\Model;

class Tag extends Model {
    public static $table = 'tags';
    public static $order = ['name' => 'asc'];

    public static function active($category_id, $return_type = 'all') {
        $output = [];

        $category = Category::find($category_id);

        if($category) {
            $data = ao()->db->query('
                SELECT t.*, ct.id AS active
                FROM tags t
                LEFT JOIN categories_tags ct
                ON ct.tag_id = t.id
                AND ct.category_id = ?
                WHERE t.user_id = ?
            ', $category->id, $category->data['user_id']);

            foreach($data as $item) {
                if($return_type == 'data') {
                    $tag = new Tag($item);
                    $output[] = $tag->data;
                } else {
                    $tag = new Tag($item);
                    $output[] = $tag;
                }
            }
        }

        return $output;
    }
}
