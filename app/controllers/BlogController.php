<?php

namespace app\controllers;

use app\models\Blog;

class BlogController {
    public function index($req, $res) {
        $items = Blog::list();

        $title = 'FeedRiv Blog';

        return compact('items', 'title');
    }

    public function post($req, $res) {
        $slug = $req->params['slug'];
        $draft_key = $req->query['draft'] ?? '';

        $item = Blog::get($slug, $draft_key);

        if(!$item) {
            $res->status(404);
            exit;
        }

        $title = $item['title'];

        return compact('item', 'title');
    }

    public function rss($req, $res) {
        header('Content-Type: text/xml; charset=utf-8');

        $items = Blog::all();
        return compact('items');
    }
}
