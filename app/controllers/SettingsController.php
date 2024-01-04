<?php

namespace app\controllers;

use app\models\DefaultColor;
use app\models\DefaultTag;
use app\models\Tag;
use app\models\Setting;

use DateTimeZone;

class SettingsController {
    public function settings($req, $res) {
        $title = 'Settings';
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        $settings = Setting::get($req->user_id);
        $res->fields = $settings;

        $default_tags = Tag::where(['user_id' => $req->user_id, 'default' => 1]);
        $tags = Tag::where('user_id', $req->user_id);
        $colors = DefaultColor::where('user_id', $req->user_id);

        return compact('colors', 'default_tags', 'tags', 'timezones', 'title');
    }

    public function update($req, $res) {
        $data = $req->val('data', [
            'timezone' => ['required'],
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_auto_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]);

        $data = $req->clean($data, [
            'show_tags' => ['boolean'],
            'show_ratings' => ['boolean'],
            'show_auto_ratings' => ['boolean'],
            'show_colors' => ['boolean'],
            'save_ratings' => ['boolean'],
        ]);

        Setting::set($req->user_id, $data);

        $res->success('Items updated successfully.', '/settings');
    }
}
