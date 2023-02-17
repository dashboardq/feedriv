<?php

namespace app\controllers;

class DesignController {
    public function categories($req, $res) {
        $title = 'Edit Categories';
        return compact('title');
    }

    public function categoryAdd($req, $res) {
        $title = 'Add Category';
        return compact('title');
    }

    public function categoryEdit($req, $res) {
        $title = 'Edit Category';
        return compact('title');
    }

    public function colorEdit($req, $res) {
        $title = 'Edit Color';
        $res->fields['color'] = '#ff8888';
        return compact('title');
    }

    public function defaultColorEdit($req, $res) {
        $title = 'Edit Default Color';
        $res->fields['color'] = '#ff8888';
        return compact('title');
    }

    public function defaultTagAdd($req, $res) {
        $title = 'Add Default Tag';
        return compact('title');
    }

    public function defaultTagEdit($req, $res) {
        $title = 'Edit Default Tag';
        $res->fields['name'] = 'Read';
        return compact('title');
    }

    public function feed($req, $res) {
        $title = 'Main Feed';
        return compact('title');
    }

    public function feedAdd($req, $res) {
        $title = 'Add Feed';
        return compact('title');
    }

    public function feedEdit($req, $res) {
        $title = 'Edit Feed';

        $res->fields['category'] = 1;
        $res->fields['feed'] = 'https://example.com/';

        return compact('title');
    }

    public function settings($req, $res) {
        $title = 'Settings';
        return compact('title');
    }

    public function tagAdd($req, $res) {
        $title = 'Add Tag';
        return compact('title');
    }

    public function tagEdit($req, $res) {
        $title = 'Edit Tag';

        $res->fields['name'] = 'Read';

        return compact('title');
    }

    public function wordAdd($req, $res) {
        $title = 'Add Word';
        return compact('title');
    }

    public function wordEdit($req, $res) {
        $title = 'Edit Word';

        $res->fields['word'] = 'javascript';
        $res->fields['score'] = 5;

        return compact('title');
    }
}
