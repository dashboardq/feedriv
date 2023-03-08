<?php

namespace app\controllers;

use app\models\AutoRating;
use app\models\Category;
use app\models\Color;
use app\models\Feed;
use app\models\Setting;
use app\models\Tag;

class CategoriesController {
    public function categories($req, $res) {
        $title = 'Categories';
        $list = Category::where('user_id', $req->user_id);

        return compact('list', 'title');
    }

    public function add($req, $res) {
        $title = 'Add Category';

        $settings = Setting::get($req->user_id);

        // Need to get the default settings.
        $res->fields['show_tags'] = $settings['show_tags'];
        $res->fields['show_ratings'] = $settings['show_ratings'];
        $res->fields['show_colors'] = $settings['show_colors'];
        $res->fields['save_ratings'] = $settings['save_ratings'];

        return compact('title');
    }

    public function create($req, $res) {
        $data = $req->val('data', [
            'name' => ['required'],
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]); 

        $data = $req->clean($data, [
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]);

        $data['user_id'] = $req->user_id;
        $category = Category::create($data);


        $res->success('Item successfully added.', '/categories');
    }

    public function delete($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        Category::delete($val['id']);

        $res->success('Item successfully deleted.');

    }

    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Category';

        $feeds = Feed::where('category_id', $req->params['id']);
        $tags = Tag::where('category_id', $req->params['id']);
        $colors = Color::where('category_id', $req->params['id']);
        $ratings = AutoRating::where('category_id', $req->params['id']);

        $category = Category::find($req->params['id']);

        $res->fields['name'] = $category->data['name'];
        $res->fields['show_tags'] = $category->data['show_tags'];
        $res->fields['show_ratings'] = $category->data['show_ratings'];
        $res->fields['show_colors'] = $category->data['show_colors'];
        $res->fields['save_ratings'] = $category->data['save_ratings'];

        return compact('category', 'colors', 'feeds', 'ratings', 'tags', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'name' => ['required'],
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]); 

        $data = $req->clean($data, [
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]);

        $item = Category::find($params['id']);
        $item->update($data);

        $res->success('Item successfully updated.', '/categories');
    }
}
