<?php

namespace app\models;

use mavoc\core\Model;

class AutoRating extends Model {
	public static $table = 'auto_ratings';

    // Only need to set when the model has dynamic data.
    // Make sure to update when migration changes columns.
    // TODO: Maybe have migration files structured in a way so that columns can be pulled dynamically.
	public static $columns = [
		'id',
		'user_id',
		'category_id',
		'word',
		'use_count',
		'sum_score',
		'avg_score_int2',
		'locked_score_int2',
		'locked',
		'created_at',
		'updated_at',
	];

	public static $hooked = false;

	public function __construct($args) {
		if(!self::$hooked) {
			ao()->filter('ao_model_process_' . self::$table . '_data', [$this, 'process']);
			self::$hooked = true;
		}

		// May want to look at using hooks instead of __construct().
		parent::__construct($args);
	}

	public function process($data) {
        if($data['locked']) {
            $data['score'] = $data['locked_score_int2'] / 100;
        } else {
            $data['score'] = $data['avg_score_int2'] / 100;
        }
        $data['locked_score'] = $data['locked_score_int2'] / 100;
        $data['avg_score'] = $data['avg_score_int2'] / 100;

		return $data;
	} 
}
