<?php

namespace app\controllers;

use app\models\AutoRating;

class AutoRatingsController {
    public function add($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $title = 'Add Word';

        return compact('title');
    }

    public function create($req, $res) {
        $params = $req->val('params', [
            'category_id' => ['required', ['dbOwner' => ['categories', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'word' => ['required'],
            'locked' => ['boolean'],
            'locked_score' => ['numeric'],
             '_titles' => ['locked_score' => 'Manual Score'],
        ]); 
        $data = $req->clean($data, [
            'word' => ['lowercase'],
            'locked' => ['boolean'],
            'locked_score' => ['int2'],
        ]); 
        $data['locked_score_int2'] = $data['locked_score'];
        unset($data['locked_score']);

        $rating = AutoRating::by(['word' => $data['word'], 'category_id' => $params['category_id']]);
        if($rating) {
            $res->error('The word submitted already exists.');
        }

        $data['user_id'] = $req->user_id;
        $data['category_id'] = $params['category_id'];
        $item = AutoRating::create($data);

        $res->success('Item successfully added.', '/category/edit/' . $params['category_id']);
    }

    public function delete($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['auto_ratings', 'id', $req->user_id]]],
        ]);

        AutoRating::delete($params['id']);

        $res->success('Item successfully deleted.');

    }

    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['auto_ratings', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Word';

        $item = AutoRating::find($req->params['id']);

        $res->fields['word'] = $item->data['word'];
        $res->fields['locked'] = $item->data['locked'];
        $res->fields['locked_score'] = $item->data['locked_score'];

        return compact('item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['auto_ratings', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'locked' => ['boolean'],
            'locked_score' => ['numeric'],
             '_titles' => ['locked_score' => 'Manual Score'],
        ]); 
        $data = $req->clean($data, [
            'locked' => ['boolean'],
            'locked_score' => ['int2'],
        ]); 
        $data['locked_score_int2'] = $data['locked_score'];
        unset($data['locked_score']);

        $item = AutoRating::find($params['id']);
        $item->update($data);

        $res->success('Item successfully updated.', '/category/edit/' . $item->data['category_id']);
    }
}
