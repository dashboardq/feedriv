<?php

namespace app\controllers;

use app\models\Feed;
use app\models\Setting;

class AppController {
    public function all($req, $res) {
        // Change the active link
        Setting::set($req->user_id, 'feed_link', $req->path);
        Setting::set($req->user_id, 'feed_type', 'all');

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function archive($req, $res) {
        $title = 'Feeds';

        // Change the category
        $change = true;

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feed', $args);
    }

    public function auto($req, $res) {
        $title = 'Feeds';

        // Change the category
        $change = true;

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feed', $args);
    }

    public function category($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]); 

        // Change the active link
        Setting::set($req->user_id, 'feed_link', $req->path);
        Setting::set($req->user_id, 'feed_type', 'category');
        Setting::set($req->user_id, 'feed_id', $req->params['id']);

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function feed($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ]); 

        // Change the active link
        Setting::set($req->user_id, 'feed_link', $req->path);
        Setting::set($req->user_id, 'feed_type', 'feed');
        Setting::set($req->user_id, 'feed_id', $req->params['id']);

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function rated($req, $res) {
        $title = 'Feeds';

        // Change the category
        $change = true;

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feed', $args);
    }

    public function tag($req, $res) {
        $title = 'Feeds';

        // Change the category
        $change = true;

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feed', $args);
    }
}
