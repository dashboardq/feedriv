<?php

namespace app\controllers;

use app\models\CategoryTag;
use app\models\Tag;

class TagsController {
    public function add($req, $res) {
        $title = 'Add Tag';

        return compact('title');
    }

    public function create($req, $res) {
        $data = $req->val('data', [
            'name' => ['required'],
        ]); 

        $args = [];
        $args['user_id'] = $req->user_id;
        $args['name'] = $data['name'];
        $item = Tag::create($args);

        $res->success('Item successfully updated.', '/settings');
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

    public function modify($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $title = 'Modify Tags';

        //$tags = Tag::where('user_id', $req->user_id);
        ////$tags = Tag::where('user_id', $req->user_id);

        ////$cat_tags = CategoryTag::tags('user_id', $req->user_id);

        $tags = Tag::active($params['category_id']);

        return compact('tags', 'title');
    }

    public function modifyPost($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'ids' => ['array'],
        ]);
        $data = $req->clean($data, [
            'ids' => ['array'],
        ]);

        $tags = Tag::where('user_id', $req->user_id);

        foreach($tags as $tag) {
            if(in_array($tag->id, $data['ids'])) {
                CategoryTag::add($params['category_id'], $tag->id);
            } else {
                CategoryTag::remove($params['category_id'], $tag->id);
            }
        }

        $res->success('Item successfully updated.', '/category/edit/' . $params['category_id']);
    }

    public function modifyDefaults($req, $res) {
        $title = 'Modify Default Tags';

        $tags = Tag::where('user_id', $req->user_id);

        return compact('tags', 'title');
    }

    public function modifyDefaultsPost($req, $res) {
        $data = $req->val('data', [
            'ids' => ['array'],
        ]);
        $data = $req->clean($data, [
            'ids' => ['array'],
        ]);

        $tags = Tag::where('user_id', $req->user_id);

        foreach($tags as $tag) {
            if(in_array($tag->id, $data['ids'])) {
                $tag->data['default'] = 1;
            } else {
                $tag->data['default'] = 0;
            }
            $tag->save();
        }

        $res->success('Item successfully updated.', '/settings');
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

        $res->success('Item successfully updated.', '/settings');
    }
}
