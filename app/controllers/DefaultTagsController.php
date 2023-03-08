<?php

namespace app\controllers;

use app\models\DefaultTag;

class DefaultTagsController {
    public function add($req, $res) {
        $title = 'Add Default Tag';

        return compact('title');
    }

    public function create($req, $res) {
        $data = $req->val('data', [
            'name' => ['required'],
        ]); 

        $args = [];
        $args['user_id'] = $req->user_id;
        $args['name'] = $data['name'];
        $item = DefaultTag::create($args);

        $res->success('Item successfully added.', '/settings');
    }

    public function delete($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['default_tags', 'id', $req->user_id]]],
        ]);

        DefaultTag::delete($params['id']);

        $res->success('Item successfully deleted.');

    }

    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['default_tags', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Default Tag';

        $item = DefaultTag::find($req->params['id']);

        $res->fields['name'] = $item->data['name'];

        return compact('item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['default_tags', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'name' => ['required'],
        ]);

        $item = DefaultTag::find($params['id']);
        $item->data['name'] = $data['name'];
        $item->save();

        $res->success('Item successfully updated.', '/settings');
    }
}
