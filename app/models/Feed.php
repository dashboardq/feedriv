<?php

namespace app\models;

use mavoc\core\Model;

class Feed extends Model {
    public static $table = 'feeds';
    public static $order = ['sort_order' => 'desc'];

    public $items = [];

    public static function create($args) {
        // TODO: delete
        ao()->filter('ao_final_exception_redirect', function() {
            echo 'died before redirect';
            die;
        });


        // Check if the shared feed exists by seeing if the original_url matches any other feeds original_url
        $similar = Feed::by('original_url', $args['original_url']);
        if($similar) {
            // Similar feed found. Get the $shared_feed
            // TODO: Change this from ::find() to ::load() and have it load the latest items
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

            // TODO: Get auto rating working (if it is turned on)
            // TODO: Should probably move the items creation to a separate method
            $temp['auto_rating_int2'] = 0;
            $temp['rating'] = 0;
            $temp['archived'] = 0;
            $temp['status'] = 'initialized';

            $feed->items[] = Item::create($temp);
        }

        return $feed;
    }

    public function total() {
        $total = Item::count([
            'feed_id' => $this->id,
            'archived' => 0,
        ]);

        return $total;
    }
}
