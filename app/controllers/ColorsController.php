<?php

namespace app\controllers;

use app\models\Color;

class ColorsController {
    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['colors', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Color';

        $item = Color::find($req->params['id']);

        $res->fields['color'] = $item->data['color'];

        return compact('item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['colors', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'color' => ['required'],
        ]);

        $item = Color::find($params['id']);
        $item->data['color'] = $data['color'];
        $item->save();

        $res->success('Item successfully updated.', '/category/edit/' . $item->data['category_id']);
    }
}
