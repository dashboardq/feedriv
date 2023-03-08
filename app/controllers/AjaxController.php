<?php

namespace app\controllers;

use app\models\Category;
use app\models\Feed;
use app\models\Item;
use app\models\ItemTag;

class AjaxController {
    public function archive($req, $res) {
        return ['status' => 'success'];
    }

    public function archiveAll($req, $res) {
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

        return ['status' => 'success'];
    }

    public function refresh($req, $res) {
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
