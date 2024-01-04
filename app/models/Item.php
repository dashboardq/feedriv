<?php

namespace app\models;

use mavoc\core\Model;

use DateTime;

class Item extends Model {
    public static $table = 'items';
    public static $order = ['published_at' => 'desc'];

    /*
	public static $columns = [
		'id',
		'user_id',
		'feed_id',
		'shared_item_id',
		'auto_rating_int2',
		'rating',
		'archived',
		'status',
		'published_at',
		'created_at',
		'updated_at',
	];
*/

	public static $hooked = false;

    /*
	public function __construct($args) {
		if(!self::$hooked) {
			ao()->filter('ao_model_process_' . self::$table . '_data', [$this, 'process']);
			self::$hooked = true;
		}

		// May want to look at using hooks instead of __construct().
		parent::__construct($args);
	}
     */

    public static function create($args) {
        $item = parent::create($args);

        // Consider moving the auto_rating to here.

        return $item;
    }

    public static function whereCategory($category_id, $filter = [], $return_type = 'default') {
        $class = get_called_class();
        $table = self::setTable($class);
        $order = self::setOrder($table);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = [];

        if(isset($filter['tag_id'])) {
            $sql = 'SELECT i.*
                FROM items i, feeds f , items_tags it
                WHERE f.category_id = ?
                AND i.feed_id = f.id
                AND i.archived = 0
                AND i.id = it.item_id
                AND it.tag_id = ?
                ';
            $values = [];
            $values[] = $category_id;
            $values[] = $filter['tag_id'];
        } else {
            $sql = 'SELECT i.*
                FROM items i, feeds f
                WHERE f.category_id = ?
                AND i.feed_id = f.id
                AND i.archived = 0
                ';
            $values = [];
            $values[] = $category_id;
        }

        /*
        if(isset($filter['rating']) && preg_match('/^[0-5]$/', $filter['rating'])) {
            $sql .= ' AND i.rating = ?';
            $values[] = $filter['rating'];
        }
         */
        foreach($filter as $key => $value) {
            // Tag id is handled above.
            if($key == 'tag_id') {
                continue;
            }
            $prefix = 'i.';
            if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $value[0] . ' ?';
                $values[] = $value[1];
            } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                foreach($value as $i => $val) {
                    if(isset($val[0]) && in_array($val[0], self::$compare)) {
                        $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $val[0] . ' ?';
                        $values[] = $val[1];
                    }
                }
            } else {
                $sql .= ' AND ' . $prefix . '`' . $key . '` = ?';
                $values[] = $value;
            }
        }

        if(count($order)) {
            $sql .= ' ORDER BY';
            $count = 0;
            foreach($order as $field => $direction) {
                $prefix = 'i.';
                if($count == 0) {
                    $sql .= ' ' . $prefix . '`' . $field . '` ' . $direction;
                } else {
                    $sql .= ', ' . $prefix . '`' . $field . '` ' . $direction;
                }
                $count++;
            }
        }

        if(is_numeric($limit) && $limit > 0 && is_numeric($offset) && $offset > 0) {
            $sql .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        } elseif(is_numeric($limit) && $limit > 0) {
            $sql .= ' LIMIT ' . $limit;
        }

        $data = ao()->db->query($sql, $values);

        foreach($data as $item) {
            if($return_type == 'data') {
                $item = new Item($item);
                $output[] = $item->data;
            } else {
                $output[] = new Item($item);
            }   
        } 

        return $output;
    }

    public static function whereCategoryCount($category_id, $filter = [], $return_type = 'default') {
        $class = get_called_class();
		$table = self::setTable($class);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $url_default = self::setURL($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = [];

        if(isset($filter['tag_id'])) {
            $sql = 'SELECT COUNT(i.id) as total
                FROM items i, feeds f , items_tags it
                WHERE f.category_id = ?
                AND i.feed_id = f.id
                AND i.archived = 0
                AND i.id = it.item_id
                AND it.tag_id = ?
';
            $values = [];
            $values[] = $category_id;
            $values[] = $filter['tag_id'];
        } else {
            $sql = 'SELECT COUNT(i.id) as total
                FROM items i, feeds f 
                WHERE f.category_id = ?
                AND i.feed_id = f.id
                AND i.archived = 0
';
            $values = [];
            $values[] = $category_id;
        }

        /*
        if(isset($filter['rating']) && preg_match('/^[0-5]$/', $filter['rating'])) {
            $sql .= ' AND i.rating = ?';
            $values[] = $filter['rating'];
        }
         */
        foreach($filter as $key => $value) {
            // Tag id is handled above.
            if($key == 'tag_id') {
                continue;
            }
            $prefix = 'i.';
            if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $value[0] . ' ?';
                $values[] = $value[1];
            } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                foreach($value as $i => $val) {
                    if(isset($val[0]) && in_array($val[0], self::$compare)) {
                        $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $val[0] . ' ?';
                        $values[] = $val[1];
                    }
                }
            } else {
                $sql .= ' AND ' . $prefix . '`' . $key . '` = ?';
                $values[] = $value;
            }
        }

        $data = ao()->db->query($sql, $values);

        if(isset($data[0]['total']) && $data[0]['total'] > 0) {
            if($return_type == 'pagination') {
                $total_results = $data[0]['total'];
                $output['total_results'] = $total_results;
                $output['total_pages'] = ceil($total_results / $limit);
                if($page > 1) {
                    $output['page_previous'] = $page - 1;
                } else {
                    $output['page_previous'] = 1;
                }
                if($page < $output['total_pages']) {
                    $output['page_next'] = $page + 1;
                } else {
                    $output['page_next'] = $output['total_pages'];
                }
                $output['page_current'] = $page;
                $output['current_page'] = $page;
                $output['current_result'] = (($page - 1) * $limit) + 1;
                $output['current_result_first'] = (($page - 1) * $limit) + 1;
                if($page < $output['total_pages']) {
                    $output['current_result_last'] = $page * $limit;
                } else {
                    $output['current_result_last'] = $total_results;
                }
                $url_stripped = preg_replace('/page=\d+&?/', '', $url_default);
                if(strpos($url_stripped, '?') === false) {
                    $output['url_next'] = $url_stripped . '?page=' . urlencode($output['page_next']);
                    $output['url_previous'] = $url_stripped . '?page=' . urlencode($output['page_previous']);
                } else {
                    $output['url_next'] = $url_stripped . '&page=' . urlencode($output['page_next']);
                    $output['url_previous'] = $url_stripped . '&page=' . urlencode($output['page_previous']);
                }
            } else {
                $output = $data[0]['total'];
            }
        } else {
            if($return_type == 'default') {
                $output = 0;
            }
        }

        return $output;
    }

    public static function whereTag($tag_id, $filter = [], $return_type = 'default') {
        $class = get_called_class();
        $table = self::setTable($class);
        $order = self::setOrder($table);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = [];

        $sql = 'SELECT i.*
            FROM items i, items_tags it
            WHERE it.tag_id = ?
            AND it.item_id = i.id
            ';

        $values = [];
        $values[] = $tag_id;
        /*
        if(isset($filter['rating']) && preg_match('/^[0-5]$/', $filter['rating'])) {
            $sql .= ' AND i.rating = ?';
            $values[] = $filter['rating'];
        }
         */
        foreach($filter as $key => $value) {
            $prefix = 'i.';
            if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $value[0] . ' ?';
                $values[] = $value[1];
            } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                foreach($value as $i => $val) {
                    if(isset($val[0]) && in_array($val[0], self::$compare)) {
                        $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $val[0] . ' ?';
                        $values[] = $val[1];
                    }
                }
            } else {
                $sql .= ' AND ' . $prefix . '`' . $key . '` = ?';
                $values[] = $value;
            }
        }

        if(count($order)) {
            $sql .= ' ORDER BY';
            $count = 0;
            foreach($order as $field => $direction) {
                $prefix = 'i.';
                if($count == 0) {
                    $sql .= ' ' . $prefix . '`' . $field . '` ' . $direction;
                } else {
                    $sql .= ', ' . $prefix . '`' . $field . '` ' . $direction;
                }
                $count++;
            }
        }

        if(is_numeric($limit) && $limit > 0 && is_numeric($offset) && $offset > 0) {
            $sql .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
        } elseif(is_numeric($limit) && $limit > 0) {
            $sql .= ' LIMIT ' . $limit;
        }

        $data = ao()->db->query($sql, $values);

        foreach($data as $item) {
            if($return_type == 'data') {
                $item = new Item($item);
                $output[] = $item->data;
            } else {
                $output[] = new Item($item);
            }   
        } 

        return $output;
    }

    public static function whereTagCount($tag_id, $filter = [], $return_type = 'default') {
        $class = get_called_class();
		$table = self::setTable($class);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $url_default = self::setURL($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = [];

        $sql = 'SELECT COUNT(i.id) as total
            FROM items i, items_tags it
            WHERE it.tag_id = ?
            AND it.item_id = i.id
            ';

        $values = [];
        $values[] = $tag_id;
        /*
        if(isset($filter['rating']) && preg_match('/^[0-5]$/', $filter['rating'])) {
            $sql .= ' AND i.rating = ?';
            $values[] = $filter['rating'];
        }
         */
        foreach($filter as $key => $value) {
            $prefix = 'i.';
            if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $value[0] . ' ?';
                $values[] = $value[1];
            } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                foreach($value as $i => $val) {
                    if(isset($val[0]) && in_array($val[0], self::$compare)) {
                        $sql .= ' AND ' . $prefix . '`' . $key . '` ' . $val[0] . ' ?';
                        $values[] = $val[1];
                    }
                }
            } else {
                $sql .= ' AND ' . $prefix . '`' . $key . '` = ?';
                $values[] = $value;
            }
        }

        $data = ao()->db->query($sql, $values);

        if(isset($data[0]['total']) && $data[0]['total'] > 0) {
            if($return_type == 'pagination') {
                $total_results = $data[0]['total'];
                $output['total_results'] = $total_results;
                $output['total_pages'] = ceil($total_results / $limit);
                if($page > 1) {
                    $output['page_previous'] = $page - 1;
                } else {
                    $output['page_previous'] = 1;
                }
                if($page < $output['total_pages']) {
                    $output['page_next'] = $page + 1;
                } else {
                    $output['page_next'] = $output['total_pages'];
                }
                $output['page_current'] = $page;
                $output['current_page'] = $page;
                $output['current_result'] = (($page - 1) * $limit) + 1;
                $output['current_result_first'] = (($page - 1) * $limit) + 1;
                if($page < $output['total_pages']) {
                    $output['current_result_last'] = $page * $limit;
                } else {
                    $output['current_result_last'] = $total_results;
                }
                $url_stripped = preg_replace('/page=\d+&?/', '', $url_default);
                if(strpos($url_stripped, '?') === false) {
                    $output['url_next'] = $url_stripped . '?page=' . urlencode($output['page_next']);
                    $output['url_previous'] = $url_stripped . '?page=' . urlencode($output['page_previous']);
                } else {
                    $output['url_next'] = $url_stripped . '&page=' . urlencode($output['page_next']);
                    $output['url_previous'] = $url_stripped . '&page=' . urlencode($output['page_previous']);
                }
            } else {
                $output = $data[0]['total'];
            }
        } else {
            if($return_type == 'default') {
                $output = 0;
            }
        }

        return $output;
    }

    public function process($data) {
        $shared_item = SharedItem::find($data['shared_item_id']);
        if($shared_item) {
            $data['title'] = $shared_item->data['title'];
            $data['link'] = $shared_item->data['link'];
            $data['guid'] = $shared_item->data['guid'];
            $data['pub_date'] = $shared_item->data['pub_date'];
            $data['description'] = $shared_item->data['description'];
            $data['published_at'] = $shared_item->data['published_at'];
            $data['published_tz'] = $shared_item->data['published_tz'];
        } else {
            $data['title'] = 'No Title Available';
            $data['link'] = '';
            $data['guid'] = '';
            $data['pub_date'] = '';
            $data['description'] = '';
            $data['published_at'] = new DateTime();
            $data['published_tz'] = new DateTime();
        }

        $feed = Feed::find($data['feed_id']);
        $data['available_tag_ids'] = CategoryTag::ids($feed->data['category_id']);
        if(isset($data['id'])) {
            $data['checked_tag_ids'] = ItemTag::ids($data['id']);
        } else {
            $data['checked_tag_ids'] = [];
        }

        if($feed) {
            $data['feed'] = $feed->data;
        } else {
            $data['feed'] = [];
        }

        $category = Category::find($feed->data['category_id']);
        if($category) {
            $data['category'] = $category->data;
            if($category->data['show_colors'] && $data['auto_rating_int2']) {
                if(100 <= $data['auto_rating_int2'] && $data['auto_rating_int2'] < 200) {
                    $color = Color::by(['category_id' => $category->id, 'range' => '1-2'], 'data');
                    $data['color'] = $color['color'];
                } elseif(200 <= $data['auto_rating_int2'] && $data['auto_rating_int2'] < 300) {
                    $color = Color::by(['category_id' => $category->id, 'range' => '2-3'], 'data');
                    $data['color'] = $color['color'];
                } elseif(300 <= $data['auto_rating_int2'] && $data['auto_rating_int2'] < 400) {
                    $color = Color::by(['category_id' => $category->id, 'range' => '3-4'], 'data');
                    $data['color'] = $color['color'];
                } elseif(400 <= $data['auto_rating_int2'] && $data['auto_rating_int2'] <= 500) {
                    $color = Color::by(['category_id' => $category->id, 'range' => '4-5'], 'data');
                    $data['color'] = $color['color'];
                } else {
                    $data['color'] = '';
                }
            } else {
                $data['color'] = '';
            }
        } else {
            $data['category'] = [];
            $data['color'] = '';
        }

        return $data;
    }
}
