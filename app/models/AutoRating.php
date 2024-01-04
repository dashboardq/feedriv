<?php

namespace app\models;

use mavoc\core\Model;

class AutoRating extends Model {
	public static $table = 'auto_ratings';
    public static $order = ['word' => 'asc'];

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

	public static function rate($item, $rating) {
        $feed = Feed::find($item->data['feed_id']);
        $category = Category::find($feed->data['category_id']);
        $user_id = ao()->request->user_id;

        if($category->data['save_ratings']) {
            $words = AutoRating::getWords($item->data['title'] . ' ' . $item->data['description']);
            foreach($words as $word) {
                if($word) {
                    $auto_rating = AutoRating::by([
                        'category_id' => $category->id,
                        'word' => $word,
                    ]);

                    if($auto_rating) {
                        $args = [];
                        $args['use_count'] = $auto_rating->data['use_count'] + 1;
                        $args['sum_score'] = $auto_rating->data['sum_score'] + ($rating * 100);
                        $args['avg_score_int2'] = round(($args['sum_score'] / $args['use_count']) * 100);
                        $auto_rating->update($args);
                    } else {
                        $args = [];
                        $args['user_id'] = $user_id;
                        $args['category_id'] = $category->id;
                        $args['word'] = $word;
                        $args['use_count'] = 1;
                        $args['sum_score'] = $rating * 100;
                        $args['avg_score_int2'] = $rating * 10000;
                        $auto_rating = AutoRating::create($args);
                    }
                }
            }
        }
    }

	public static function getRating($category_id, $input) {
        $output = 0;

        $total_score = 0;
        $total_words = 0;

        $words = AutoRating::getWords($input);
        foreach($words as $word) {
            if($word) {
                $auto_rating = AutoRating::by([
                    'category_id' => $category_id,
                    'word' => $word,
                ]);

                if($auto_rating) {
                    $total_words++;
                    if($auto_rating->data['locked']) {
                        $total_score += $auto_rating->data['locked_score_int2'];
                    } else {
                        $total_score += $auto_rating->data['avg_score_int2'];
                    }
                }
            }
        }

        if($total_score && $total_words) {
            $output = round($total_score / $total_words) / 100;
        }

        return $output;
    }

    public static function getWords($input) {
        $input = strip_tags($input);
        $input = html_entity_decode($input);
        $input = strtolower($input);
        $input = preg_replace('/[^[:alnum:][:space:]]/u', '', $input);

        $output = preg_split('/[\s]+/', $input); 

        return $output;
    }
}
