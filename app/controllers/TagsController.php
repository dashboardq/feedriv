<?php

namespace app\controllers;

use app\models\Tag;

class TagsController {
    public function add($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $title = 'Add Tag';

        return compact('title');
    }

    public function create($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'name' => ['required'],
        ]); 

        $args = [];
        $args['user_id'] = $req->user_id;
        $args['category_id'] = $params['category_id'];
        $args['name'] = $data['name'];
        $item = Tag::create($args);

        $res->success('Item successfully updated.', '/category/edit/' . $item->data['category_id']);
    }

    public function delete($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['tags', 'id', $req->user_id]]],
        ]);

        Tag::delete($params['id']);

        $res->success('Item successfully deleted.');

    }

    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['tags', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Tag';

        $item = Tag::find($req->params['id']);

        $res->fields['name'] = $item->data['name'];

        return compact('item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['tags', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'name' => ['required'],
        ]);

        $item = Tag::find($params['id']);
        $item->data['name'] = $data['name'];
        $item->save();

        $res->success('Item successfully updated.', '/category/edit/' . $item->data['category_id']);
    }
}
