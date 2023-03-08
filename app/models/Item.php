<?php

namespace app\models;

use mavoc\core\Model;

use DateTime;

class Item extends Model {
    public static $table = 'items';
    public static $order = ['published_at' => 'desc'];

    /*
	public static $columns = [
		'id',
		'user_id',
		'feed_id',
		'shared_item_id',
		'auto_rating_int2',
		'rating',
		'archived',
		'status',
		'published_at',
		'created_at',
		'updated_at',
	];
*/

	public static $hooked = false;

    /*
	public function __construct($args) {
		if(!self::$hooked) {
			ao()->filter('ao_model_process_' . self::$table . '_data', [$this, 'process']);
			self::$hooked = true;
		}

		// May want to look at using hooks instead of __construct().
		parent::__construct($args);
	}
     */

    public static function categoryCount($category_id) {
        $sql = 'SELECT COUNT(i.id) as total 
            FROM items i, feeds f 
            WHERE f.category_id = ?
            AND i.feed_id = f.id';
        $data = ao()->db->query($sql, [$category_id]);

        return $data[0]['total'];
    }

    public function process($data) {
        $shared_item = SharedItem::find($data['shared_item_id']);
        if($shared_item) {
            $data['title'] = $shared_item->data['title'];
            $data['link'] = $shared_item->data['link'];
            $data['guid'] = $shared_item->data['guid'];
            $data['pub_date'] = $shared_item->data['pub_date'];
            $data['description'] = $shared_item->data['description'];
            $data['published_at'] = $shared_item->data['published_at'];
        } else {
            $data['title'] = 'No Title Available';
            $data['link'] = '';
            $data['guid'] = '';
            $data['pub_date'] = '';
            $data['description'] = '';
            $data['published_at'] = new DateTime();
        }

        //$items_tags = ItemTag::tags($data['id']);

        return $data;
    }
}
