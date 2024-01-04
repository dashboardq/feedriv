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
        $pagination = false;

        $feed_type = Setting::get($req->user_id, 'feed_type');
        $feed_id = Setting::get($req->user_id, 'feed_id');
        $feed_sort = Setting::get($req->user_id, 'feed_sort');
        $feed_filter = Setting::get($req->user_id, 'feed_filter');

        $page = clean($req->query['page'] ?? 1, 'int', 1);

        ao()->filter('ao_model_order_items', function($order) use ($feed_sort) {
            if($feed_sort == 'date-asc') {
                $order = ['published_at' => 'asc'];
            } elseif($feed_sort == 'auto-asc') {
                $order = ['auto_rating_int2' => 'asc'];
            } elseif($feed_sort == 'auto-desc') {
                $order = ['auto_rating_int2' => 'desc'];
            } else {
                // date-desc
                $order = ['published_at' => 'desc'];
            }
            return $order;
        });

        $tags = Tag::where('user_id', $req->user_id);
        if($feed_type == 'archive') {
            $args = [
                'user_id' => $req->user_id,
                'archived' => 1,
            ];
            if(preg_match('/^rated_([1-5])+$/', $feed_filter, $matches)) {
                $args['rating'] = $matches[1];
            }
            if(preg_match('/^auto_rated_([0-4])-([1-5])+$/', $feed_filter, $matches)) {
                $lowest = $matches[1] * 100;
                $highest = $matches[2] * 100;
                if($highest == 500) {
                    $args['auto_rating_int2'] = ['>=', $lowest];
                } elseif($highest == 100) {
                    $args['auto_rating_int2'] = ['<', $highest];
                } else {
                    $args['auto_rating_int2'] = [['>=', $lowest], ['<', $highest]];
                }
            }
            if(preg_match('/^tag_(\d)+$/', $feed_filter, $matches)) {
                $tag_id = $matches[1];
                $list = Item::whereTag($tag_id, $args, [ao()->app->per_page, $page]);
                $pagination = Item::whereTagCount($tag_id, $args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            } else {
                $list = Item::where($args, [ao()->app->per_page, $page]);
                $pagination = Item::count($args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            }

        } elseif($feed_type == 'category') {
            // Validate category_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
                '_settings' => ['check_referrer' => false],
            ], '/feeds/all'); 

            $filters = [];
            if(preg_match('/^rated_([0-5])+$/', $feed_filter, $matches)) {
                $filters['rating'] = $matches[1];
            }
            if(preg_match('/^auto_rated_([0-4])-([1-5])+$/', $feed_filter, $matches)) {
                $lowest = $matches[1] * 100;
                $highest = $matches[2] * 100;
                if($highest == 500) {
                    $filters['auto_rating_int2'] = ['>=', $lowest];
                } elseif($highest == 100) {
                    $filters['auto_rating_int2'] = ['<', $highest];
                } else {
                    $filters['auto_rating_int2'] = [['>=', $lowest], ['<', $highest]];
                }
            }
            if(preg_match('/^tag_(\d)+$/', $feed_filter, $matches)) {
                $tag_id = $matches[1];
                $filters['tag_id'] = $tag_id;
                $list = Item::whereCategory($feed_id, $filters, [ao()->app->per_page, $page, 'pagination', $req->path]);
                $pagination = Item::whereCategoryCount($feed_id, $filters, [ao()->app->per_page, $page, 'pagination', $req->path]);
            } else {
                $list = Item::whereCategory($feed_id, $filters, [ao()->app->per_page, $page, 'pagination', $req->path]);
                $pagination = Item::whereCategoryCount($feed_id, $filters, [ao()->app->per_page, $page, 'pagination', $req->path]);
            }
        } elseif($feed_type == 'feed') {
            // Validate feed_id
            $val = $req->val(compact('feed_id'), [
                'feed_id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
                '_settings' => ['check_referrer' => false],
            ], '/feeds/all'); 

            $args = [
                'feed_id' => $feed_id,
                'archived' => 0,
            ];
            if(preg_match('/^rated_([0-5])+$/', $feed_filter, $matches)) {
                $args['rating'] = $matches[1];
            }
            if(preg_match('/^auto_rated_([0-4])-([1-5])+$/', $feed_filter, $matches)) {
                $lowest = $matches[1] * 100;
                $highest = $matches[2] * 100;
                if($highest == 500) {
                    $args['auto_rating_int2'] = ['>=', $lowest];
                } elseif($highest == 100) {
                    $args['auto_rating_int2'] = ['<', $highest];
                } else {
                    $args['auto_rating_int2'] = [['>=', $lowest], ['<', $highest]];
                }
            }
            if(preg_match('/^tag_(\d)+$/', $feed_filter, $matches)) {
                $tag_id = $matches[1];
                $list = Item::whereTag($tag_id, $args, [ao()->app->per_page, $page]);
                $pagination = Item::whereTagCount($tag_id, $args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            } else {
                $list = Item::where($args, [ao()->app->per_page, $page]);
                $pagination = Item::count($args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            }
        } else {
            $args = [
                'user_id' => $req->user_id,
                'archived' => 0,
            ];
            if(preg_match('/^rated_([1-5])+$/', $feed_filter, $matches)) {
                $args['rating'] = $matches[1];
            }
            if(preg_match('/^auto_rated_([0-4])-([1-5])+$/', $feed_filter, $matches)) {
                $lowest = $matches[1] * 100;
                $highest = $matches[2] * 100;
                if($highest == 500) {
                    $args['auto_rating_int2'] = ['>=', $lowest];
                } elseif($highest == 100) {
                    $args['auto_rating_int2'] = ['<', $highest];
                } else {
                    $args['auto_rating_int2'] = [['>=', $lowest], ['<', $highest]];
                }
            }
            if(preg_match('/^tag_(\d)+$/', $feed_filter, $matches)) {
                $tag_id = $matches[1];
                $list = Item::whereTag($tag_id, $args, [ao()->app->per_page, $page]);
                $pagination = Item::whereTagCount($tag_id, $args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            } else {
                $list = Item::where($args, [ao()->app->per_page, $page]);
                $pagination = Item::count($args, [ao()->app->per_page, $page, 'pagination', $req->path]);
            }
        }

        // This is used to track what items can be archived when "Archive All" is called.
        // Only 'viewed' items should be archived.
        Item::updateWhere(['status' => 'viewed'], [
            'user_id' => $req->user_id,
            'archived' => 0,
        ]);

        return compact('feed_sort', 'list', 'pagination', 'tags', 'title');
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
