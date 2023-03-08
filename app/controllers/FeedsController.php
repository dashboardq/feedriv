<?php

namespace app\controllers;

use app\models\Category;
use app\models\Feed;
use app\models\Item;
use app\models\Setting;
use app\models\Tag;

class FeedsController {
    public function feeds($req, $res) {
        $title = 'Feeds';

        $feed_type = Setting::get($req->user_id, 'feed_type');

        if($feed_type == 'category') {
            $list = [];
            $tags = [];
            //$tags = Tag::where('category_id', $feed->data['category_id']);
        } elseif($feed_type == 'feed') {
            $list = [];
            $tags = [];
            //$tags = Tag::where('category_id', $feed->data['category_id']);
        } else {
            $list = Item::where('user_id', $req->user_id);
            $tags = Tag::where('user_id', $req->user_id);
        }


        return compact('list', 'tags', 'title');
    }

    public function add($req, $res) {
        if(isset($req->params['category_id'])) {
            $params = $req->val('params', [
                'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            ]); 

            $res->fields['category_id'] = $req->params['category_id'];
        }

        $title = 'Add Feed';

        $list = Category::where('user_id', $req->user_id);
        $categories = [];
        $categories[] = [
            'label' => 'Please select...',
            'value' => '',
        ];
        foreach($list as $item) {
            $categories[] = [
                'label' => $item->data['name'],
                'value' => $item->data['id'],
            ];
        }


        return compact('categories', 'title');
    }

    public function create($req, $res) {
        if(isset($req->params['category_id'])) {
            $params = $req->val('params', [
                'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            ]); 
        }

        $data = $req->val('data', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            'url' => ['required'],
        ]); 

        $args = [];
        $args['user_id'] = $req->user_id;
        $args['category_id'] = $data['category_id'];
        $args['original_url'] = $data['url'];
        $item = Feed::create($args);

        if(isset($req->params['category_id'])) {
            $res->success('Item successfully added.', '/category/edit/' . $req->params['category_id']);
        } else {
            $res->success('Item successfully added.', '/feeds');
        }
    }

    public function delete($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ]);

        Feed::delete($params['id']);

        $res->success('Item successfully deleted.');

    }

    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Feed';

        $list = Category::where('user_id', $req->user_id);
        $categories = [];
        $categories[] = [
            'label' => 'Please select...',
            'value' => '',
        ];
        foreach($list as $item) {
            $categories[] = [
                'label' => $item->data['name'],
                'value' => $item->data['id'],
            ];
        }

        $item = Feed::find($req->params['id']);

        $res->fields['category_id'] = $item->data['category_id'];
        $res->fields['url'] = $item->data['original_url'];

        return compact('categories', 'item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
            'url' => ['required'],
        ]);

        $item = Feed::find($params['id']);
        $item->data['category_id'] = $data['category_id'];
        $item->data['original_url'] = $data['url'];
        $item->save();

        $res->success('Item successfully updated.', '/category/edit/' . $item->data['category_id']);
    }
}
