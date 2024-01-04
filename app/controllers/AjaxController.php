<?php

namespace app\controllers;

use app\models\AutoRating;
use app\models\Category;
use app\models\Feed;
use app\models\Item;
use app\models\ItemTag;
use app\models\Setting;

class AjaxController {
    public function archive($req, $res) {
        $params = $req->val('params', [
            'item_id' => ['required', ['dbOwner' => ['items', 'id', $req->user_id]]],
        ]);

        $item = Item::find($params['item_id']);
        $item->data['archived'] = 1;
        $item->save();

        return ['status' => 'success'];
    }

    public function archiveAll($req, $res) {
        $feed_type = Setting::get($req->user_id, 'feed_type');
        $feed_id = Setting::get($req->user_id, 'feed_id');

        if($feed_type == 'category') {
            // Validate category_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            ]); 

            $feeds = Feed::where('category_id', $feed_id);
            foreach($feeds as $feed) {
                Item::updateWhere(['archived' => 1], ['feed_id' => $feed->id, 'user_id' => $req->user_id, 'status' => 'viewed', 'archived' => 0]);
            }
        } elseif($feed_type == 'feed') {
            // Validate feed_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
            ]); 
            
            Item::updateWhere(['archived' => 1], ['feed_id' => $feed_id, 'status' => 'viewed', 'archived' => 0]);
        } else {
            Item::updateWhere(['archived' => 1], ['user_id' => $req->user_id, 'status' => 'viewed', 'archived' => 0]);
        }

        return ['status' => 'success'];
    }

    public function categorySort($req, $res) {
        $data = $req->val('data', [
            'ids' => ['required'],
        ]);

        $ids = explode(',', $data['ids']);

        foreach($ids as $i => $id) {
            $category = Category::find($id);

            // Make sure each of the ids passed in are valid.
            if($category->data['user_id'] == $req->user_id) {
                $category->data['sort_order'] = $i;
                $category->save();
            }
        }

        return ['status' => 'success'];
    }

    public function feedSort($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'ids' => ['required'],
        ]);

        $ids = explode(',', $data['ids']);

        foreach($ids as $i => $id) {
            $feed = Feed::find($id);
            // Make sure each of the tracking ids passed in are part of the category.
            if($feed->data['category_id'] == $params['category_id']) {
                $feed->data['sort_order'] = $i;
                $feed->save();
            }
        }

        return ['status' => 'success'];
    }

    public function rate($req, $res) {
        $params = $req->val('params', [
            'item_id' => ['required', ['dbOwner' => ['items', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'rating' => ['required', ['in' => ['1', '2', '3', '4', '5']]],
        ]);

        $item = Item::find($params['item_id']);
        $item->update($data);

        // Save Rating
        AutoRating::rate($item, $data['rating']);

        return ['status' => 'success'];
    }

    public function refresh($req, $res) {
        // Get Feed list
        $feed_type = Setting::get($req->user_id, 'feed_type');
        $feed_id = Setting::get($req->user_id, 'feed_id');

        if($feed_type == 'category') {
            // Validate category_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            ]); 

            $list = Feed::where('category_id', $feed_id);
        } elseif($feed_type == 'feed') {
            // Validate feed_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
            ]); 

            $list = Feed::where('id', $feed_id);
        } else {
            $list = Feed::where('user_id', $req->user_id);
        }

        // Convert $list to $feed_ids
        $feed_ids = [];
        foreach($list as $item) {
            $feed_ids[] = $item->id;
        }

        return [
            'status' => 'success',
            'feed_ids' => $feed_ids,
            'index' => 0,
        ];
    }

    public function refreshFeed($req, $res) {
        $params = $req->val('params', [
            'feed_id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'index' => ['required', 'integer'],
            'feed_ids' => ['required'],
        ]);

        $feed_ids = json_decode($data['feed_ids']);

        // Make sure the feed ids are a valid array, the index is within the range, 
        // and the passed in feed_id matches the current index.
        if(
            is_array($feed_ids) 
            && $data['index'] < count($feed_ids) 
            && $params['feed_id'] == $feed_ids[$data['index']]
        ) {
            $feed = Feed::find($params['feed_id']);

            // Refresh the feed
            $feed->refresh();

            $index = $data['index'] + 1;

            return [
                'status' => 'success',
                'feed_ids' => $feed_ids,
                'index' => $index,
            ];
        } else {
            return [
                'status' => 'success',
                'feed_ids' => $feed_ids,
                'index' => -1,
            ];
        }
    }

    public function sort($req, $res) {
        $data = $req->val('data', [
            'sort' => ['required', ['in' => ['date-desc', 'date-asc', 'auto-desc', 'auto-asc']]],
        ]);

        Setting::set($req->user_id, 'feed_sort', $data['sort']);

        return ['status' => 'success'];
    }

    public function toggle($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'opened' => ['required', 'boolean'],
        ]);
        $data = $req->clean($data, [
            'opened' => ['boolean'],
        ]);

        $category = Category::find($params['category_id']);
        $category->update($data);

        return ['status' => 'success'];
    }

    public function tag($req, $res) {
        $data = $req->val('data', [
            'item_id' => ['required', ['dbOwner' => ['items', 'id', $req->user_id]]],
            'tag_id' => ['required', ['dbOwner' => ['tags', 'id', $req->user_id]]],
            'action' => ['required', ['in' => ['add', 'remove']]],
        ]);

        if($data['action'] == 'add') {
            ItemTag::add($data['item_id'], $data['tag_id']);
        } elseif($data['action'] == 'remove') {
            ItemTag::remove($data['item_id'], $data['tag_id']);
        }

        return ['status' => 'success'];
    }
}
