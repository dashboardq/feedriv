<?php

namespace app\controllers;

use app\models\Content;

class MainController {
    public function home($req, $res) {
        //$res->view('main/home');
        $home = Content::get('home');
        return compact('home');
    }

    public function pricing($req, $res) {
        // Various ways to return the data
        // Returning data and then automapping to views based on controllers would make testing easier.
        // return [];
        // return compact('list');
        // return get_defined_vars();
        $title = 'Pricing';
        return compact('title');
    }

    public function privacy($req, $res) {
        //$res->view('main/privacy', ['title' => 'Privacy Policy']);
        return ['title' => 'Privacy Policy'];
    }

    public function terms($req, $res) {
        //$res->view('main/terms', ['title' => 'Terms of Use']);
        return ['title' => 'Terms of Use'];
    }
}
