<?php

namespace app\models;

use mavoc\core\Model;

class Restriction extends Model {
    public static $table = 'restrictions';

    public static function fullAccess($user_id, $return_type = 'all') {
        $args = [];
        $args['user_id'] = $user_id;
        $args['premium_level'] = 100;
        $restriction = new Restriction($args);

        if($return_type == 'data') {
            return $restriction->data;
        } else {
            return $restriction;
        }
    }
}
