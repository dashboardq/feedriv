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

        // Change the active link
        Setting::set($req->user_id, 'feed_link', $req->path);
        Setting::set($req->user_id, 'feed_type', 'archive');

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function auto($req, $res) {
        $params = $req->val('params', [
            'range' => ['required', ['match' => '/^\d-\d$/']],
        ], '/feeds/all'); 

        // Change the category
        Setting::set($req->user_id, 'feed_filter', 'auto_rated_' . $params['range']);

        // TODO: Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function category($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ], '/feeds/all'); 

        // Change the active link
        Setting::set($req->user_id, 'feed_link', $req->path);
        Setting::set($req->user_id, 'feed_type', 'category');
        Setting::set($req->user_id, 'feed_id', $req->params['id']);

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function clear($req, $res) {
        // Change the category
        Setting::set($req->user_id, 'feed_filter', '');

        // Call another controller?
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function feed($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['feeds', 'id', $req->user_id]]],
        ], '/feeds/all'); 

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
        $params = $req->val('params', [
            'rating' => ['required', 'int'],
        ], '/feeds/all'); 

        // Change the category
        Setting::set($req->user_id, 'feed_filter', 'rated_' . $params['rating']);

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }

    public function tag($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['tags', 'id', $req->user_id]]],
        ], '/feeds/all'); 

        // Change the category
        Setting::set($req->user_id, 'feed_filter', 'tag_' . $params['id']);

        // Call another controller
        $feeds_controller = new FeedsController();
        $args = $feeds_controller->feeds($req, $res);

        $res->view('feeds/feeds', $args);
    }
}
