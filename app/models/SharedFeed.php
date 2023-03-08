<?php

namespace app\models;

use mavoc\core\Model;
use mavoc\core\RSS;

class SharedFeed extends Model {
    public static $table = 'shared_feeds';

    public $items = [];

    public static function create($args) {
        // Check if this is being created from the $args of a Feed.
        if(isset($args['original_url'])) {
            $rss = new RSS($args['original_url']);
        } else {
            $rss = new RSS($args['url']);
        }

        $data = [];
        $data['url'] = $rss->url;
        $data['title'] = $rss->title;
        $data['link'] = $rss->link;
        $data['language'] = $rss->language;
        $data['description'] = $rss->description;
        $data['last_updated_at'] = now();
        $shared_feed = parent::create($data);

        // Create items based off of the RSS. These are all new so no need to check for latest.
        // Load the oldest items first.
        for($i = count($rss->items) - 1; $i >= 0; $i--) {
            $item = $rss->items[$i];
            $temp = [];
            $temp['shared_feed_id'] = $shared_feed->id;
            $temp['title'] = $item['title'];
            $temp['link'] = $item['link'];
            $temp['guid'] = $item['link'];
            $temp['pub_date'] = $item['pub_date'];
            $temp['description'] = $item['description'];
            $temp['published_at'] = $item['published_at']->format('Y-m-d H:i:s');
            $shared_feed->items[] = SharedItem::create($temp);
        }

        // Put the items back in reverse chronological order
        $shared_feed->items = array_reverse($shared_feed->items);

        return $shared_feed;
    }

    public static function load($shared_feed_id) {
        $shared_feed = SharedFeed::find($shared_feed_id);
        $rss = new RSS($shared_feed->data['url']);

        $data = [];
        $data['url'] = $rss->url;
        $data['title'] = $rss->title;
        $data['link'] = $rss->link;
        $data['language'] = $rss->language;
        $data['description'] = $rss->description;
        $data['last_updated_at'] = now();
        $shared_feed = parent::create($data);

        // Create items based off of the RSS.
        // Load the oldest items first.
        for($i = count($rss->items) - 1; $i >= 0; $i--) {
            $item = $rss->items[$i];

            $temp = [];
            $temp['shared_feed_id'] = $shared_feed->id;
            $temp['title'] = $item['title'];
            $temp['link'] = $item['link'];
            $temp['guid'] = $item['link'];
            $temp['pub_date'] = $item['pub_date'];
            $temp['description'] = $item['description'];
            $temp['published_at'] = $item['published_at']->format('Y-m-d H:i:s');

            // If the shared item does not exist, create it.
            $shared_item = SharedItem::by('guid', $temp['guid']);
            if($shared_item) {
                $shared_feed->items[] = $shared_item;
            } else {
                $shared_feed->items[] = SharedItem::create($temp);
            }
        }

        // Put the items back in reverse chronological order
        $shared_feed->items = array_reverse($shared_feed->items);

        return $shared_feed;
    }
}
