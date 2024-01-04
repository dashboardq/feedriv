<?php

namespace app\models;

use mavoc\core\Model;

class Feed extends Model {
    public static $table = 'feeds';
    public static $order = ['sort_order' => 'desc'];

    public $items = [];

    public static function create($args) {
        // Check if the shared feed exists by seeing if the original_url matches any other feeds original_url
        $similar = Feed::by('original_url', $args['original_url']);
        if($similar) {
            // Similar feed found. Get the $shared_feed
            // Load the latest items.
            $shared_feed = SharedFeed::load($similar->data['shared_feed_id']);
        } else {
            // No similar feed found so create the $shared_feed
            $shared_feed = SharedFeed::create($args);
        }


        $data = [];
        $data['user_id'] = $args['user_id'];
        $data['category_id'] = $args['category_id'];
        $data['shared_feed_id'] = $shared_feed->id;
        $data['original_url'] = $args['original_url'];
        $data['title'] = $shared_feed->data['title'];
        $data['description'] = $shared_feed->data['description'];
        $data['last_updated_at'] = $shared_feed->data['last_updated_at'];
        $feed = parent::create($data);

        // Because this is new, add all items.
        for($i = count($shared_feed->items) - 1; $i >= 0; $i--) {
            $shared_item = $shared_feed->items[$i];
            $temp = [];
            $temp['user_id'] = $feed->data['user_id'];
            $temp['feed_id'] = $feed->data['id'];
            $temp['shared_item_id'] = $shared_item->id;
            $temp['published_at'] = $shared_item->data['published_at'];

            $temp['auto_rating_int2'] = AutoRating::getRating($feed->data['category_id'], $shared_item->data['title'] . ' ' . $shared_item->data['description']);

            $temp['rating'] = 0;
            $temp['archived'] = 0;
            $temp['status'] = 'initialized';

            $feed->items[] = Item::create($temp);
        }

        return $feed;
    }

    public static function delete($id) {
        // Delete all items associated with the feed.
        ao()->db->query('DELETE FROM ' . Item::$table . ' WHERE feed_id = ?', $id);

        parent::delete($id);
    }

    public function refresh($shared_feed = null) {
        if(!$shared_feed) {
            $shared_feed = SharedFeed::load($this->data['shared_feed_id']);
        }

        // Update the feed details
        $data = [];
        if($this->data['title'] != $shared_feed->data['title']) {
            $data['title'] = $shared_feed->data['title'];
        }
        if($this->data['description'] != $shared_feed->data['description']) {
            $data['description'] = $shared_feed->data['description'];
        }
        if($this->data['last_updated_at'] != $shared_feed->data['last_updated_at']) {
            $data['last_updated_at'] = $shared_feed->data['last_updated_at'];
        }
        if(count($data)) {
            self::update($data);
        }

        // Load the newest items
        $last_shared_item_id = ao()->db->get('shared_item_id', '
            SELECT shared_item_id
            FROM items 
            WHERE feed_id = ?
            ORDER BY id DESC
            LIMIT 1
        ', $this->id);

        /*
        ao()->once('ao_db_query_args', function($args) {
            echo debugLastSql($args);
            echo "\n";
            return $args;
        });
         */
        $shared_items = SharedItem::query('
            SELECT *
            FROM shared_items 
            WHERE shared_feed_id = ?
            AND id > ?
            ORDER BY id ASC
        ', $this->data['shared_feed_id'], $last_shared_item_id);

        $feed = $this;
        $feed->items = [];
        foreach($shared_items as $shared_item) {
            $temp = [];
            $temp['user_id'] = $feed->data['user_id'];
            $temp['feed_id'] = $feed->data['id'];
            $temp['shared_item_id'] = $shared_item->id;
            $temp['published_at'] = $shared_item->data['published_at'];

            $temp['auto_rating_int2'] = AutoRating::getRating($feed->data['category_id'], $shared_item->data['title'] . ' ' . $shared_item->data['description']);

            $temp['rating'] = 0;
            $temp['archived'] = 0;
            $temp['status'] = 'initialized';

            $feed->items[] = Item::create($temp);
        }
    }

    public function total() {
        $total = Item::count([
            'feed_id' => $this->id,
            'archived' => 0,
        ]);

        return $total;
    }
}
