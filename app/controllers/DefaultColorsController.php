<?php

namespace app\controllers;

use app\models\DefaultColor;

class DefaultColorsController {
    public function edit($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['default_colors', 'id', $req->user_id]]],
        ]);

        $title = 'Edit Default Color';

        $item = DefaultColor::find($req->params['id']);

        $res->fields['color'] = $item->data['color'];

        return compact('item', 'title');
    }

    public function update($req, $res) {
        $params = $req->val('params', [
            'id' => ['required', ['dbOwner' => ['default_colors', 'id', $req->user_id]]],
        ]);

        $data = $req->val('data', [
            'color' => ['required'],
        ]);

        $item = DefaultColor::find($params['id']);
        $item->data['color'] = $data['color'];
        $item->save();

        $res->success('Item successfully updated.', '/settings');
    }
}
