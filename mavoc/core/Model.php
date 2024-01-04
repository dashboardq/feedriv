<?php

namespace mavoc\core;

use DateTime;
use DateTimeZone;

class Model {
    public $data = [];
    public $id = 0;
    ////public $db;
    // TODO: Need to make $this->tbl safe (as long as the table is a known hardcoded value it is fine
    // but this could cause issues if the table is dynamic.
    public static $table = '';
	public static $order = [];

    public $tbl = '';
    public $clmns = [];

    public static $compare = ['=', '<', '<=', '>', '>=', '!='];

    public function __construct($args = []) {
        // TODO: Restrict this to only valid args.
        $this->data = $args;
        if(isset($args['id'])) {
            $this->id = $args['id'];
        }
        // TODO: Need to think of a better way to do this.
        ////$this->db = ao()->db;

        $class = get_called_class();
        $this->tbl = $class::$table;
        if(isset($class::$columns)) {
            $this->clmns = $class::$columns;
        } else {
            // Load columns automatically
            $columns = ao()->db->query('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?', ao()->env('DB_NAME'), $this->tbl);
            $this->clmns = [];
            foreach($columns as $column) {
                $this->clmns[] = $column['COLUMN_NAME'];
            }
        }

        if(
            ao()->hook('ao_model_process', true)
        ) {
            // This is used to process data within the models by overriding the process method.
            if(
                ao()->hook('ao_model_call_process', true)
                && ao()->hook('ao_model_call_process_' . $this->tbl, true)
            ) {
                $this->data = $this->process($this->data);
            }
            if(
                ao()->hook('ao_model_process_ints', true)
                && ao()->hook('ao_model_process_ints_' . $this->tbl, true)
            ) {
                $this->data = $this->processInts($this->data);
            }
            if(
                ao()->hook('ao_model_process_cents', true)
                && ao()->hook('ao_model_process_cents_' . $this->tbl, true)
            ) {
                $this->data = $this->processCents($this->data);
            }
            if(
                ao()->hook('ao_model_process_dates', true)
                && ao()->hook('ao_model_process_dates_' . $this->tbl, true)
            ) {
                $this->data = $this->processDates($this->data);
            }
            $this->data = ao()->hook('ao_model_process_data', $this->data);
            $this->data = ao()->hook('ao_model_process_data_' . $this->tbl, $this->data);

            $this->id = ao()->hook('ao_model_process_id', $this->id);
            $this->id = ao()->hook('ao_model_process_id_' . $this->tbl, $this->id);
        }
    }   

    public function init() {
    }

    public function process($data) {
        return $data;
    }

    public function processDates($data) {
        $utc = new DateTimeZone('UTC');

        foreach($data as $key => $value) {
            if(is_string($value) && substr($key, -3) == '_at') {
                $dt_utc = new DateTime($value, $utc);
                $data[$key] = $dt_utc;

                if(ao()->session_initialized) {
                    $timezone = ao()->hook('ao_model_process_dates_timezone', 'UTC', $this->tbl);
                    $tz = new DateTimeZone($timezone);
                    $dt_tz = new DateTime($value);
                    $dt_tz->setTimezone($tz);
                    $data[substr($key, 0, -3) . '_tz'] = $dt_tz;
                } else {
                    $data[substr($key, 0, -3) . '_tz'] = $dt_utc;
                }
            }
        }

        return $data;
    }

    public function processCents($data) {
        foreach($data as $key => $value) {
            if(is_int($value) && substr($key, -6) == '_cents') {
                $data[substr($key, 0, -5)] = $value / 100;
            }
        }

        return $data;
    }

    public function processInts($data) {
        foreach($data as $key => $value) {
            $suffix = substr($key, -5);
            if(is_int($value) && $suffix == '_int2') {
                $data[substr($key, 0, -5)] = $value / 100;
            }
            if(is_int($value) && $suffix == '_int3') {
                $data[substr($key, 0, -5)] = $value / 1000;
            }
        }

        return $data;
    }

    public static function all($return_type = 'default') {
        $class = get_called_class();
        $table = self::setTable($class);
        $order = self::setOrder($table);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);
        $output = [];

        if($table) {
            $sql = 'SELECT * FROM ' . $table;
            // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
            if(is_array($order) && count($order)) {
                $sql .= ' ORDER BY';
                $count = 0;
                foreach($order as $field => $direction) {
                    if($count == 0) {
                        $sql .= ' `' . $field . '` ' . $direction;
                    } else {
                        $sql .= ', ' . $field . ' ' . $direction;
                    }   
                    $count++;
                }   
            }  

            if(is_numeric($limit) && $limit > 0 && is_numeric($offset) && $offset > 0) {
                $sql .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
            } elseif(is_numeric($limit) && $limit > 0) {
                $sql .= ' LIMIT ' . $limit;
            }

            $data = ao()->db->query($sql);

            foreach($data as $item) {
                if($return_type == 'data') {
                    $item = new $class($item);
                    $output[] = $item->data;
                } else {
                    $output[] = new $class($item);
                }
            }
        }

        return $output;
    }

    public static function by($key, $value = '', $return_type = 'default') {
        if(is_string($value) && in_array($value, ['data', 'default']) && $return_type == 'default') {
            $return_type = $value;
        } elseif(is_array($value) && isset($value[0]) && is_numeric($value[0])) {
            $return_type = $value;
        } 

        $class = get_called_class();
        $table = self::setTable($class);
        $return_type = self::setReturnType($return_type, $table);

        $output = []; 
        $values = [];

        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT * FROM ' . $table . ' WHERE ';
                foreach($key as $k => $v) {
                    if($first) {
                        $first = false;
                    } else {
                        $sql .= ' AND';
                    }
                    if(is_array($v) && isset($v[0]) && isset($v[1]) && in_array($v[0], self::$compare)) {
                        $sql .= ' `' . $k . '` ' . $v[0] . ' ?';
                        $values[] = $v[1];
                    } elseif(is_array($v) && isset($v[0]) && isset($v[0][0]) && in_array($v[0][0], self::$compare)) {
                        foreach($v as $i => $val) {
                            if(isset($val[0]) && in_array($val[0], self::$compare)) {
                                if($i > 0) {
                                    $sql .= ' AND';
                                }
                                $sql .= ' `' . $k . '` ' . $val[0] . ' ?';
                                $values[] = $val[1];
                            }
                        }
                    } else {
                        $sql .= ' `' . $k . '` = ?';
                        $values[] = $v;
                    }
                }

                $sql .= ' LIMIT 1';

                $data = ao()->db->query($sql, $values);
            } else {
                $sql = 'SELECT * FROM ' . $table . ' WHERE';
                if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                    $sql .= ' `' . $key . '` ' . $value[0] . ' ?';
                    $values[] = $value[1];
                } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                    foreach($value as $i => $val) {
                        if(isset($val[0]) && in_array($val[0], self::$compare)) {
                            if($i > 0) {
                                $sql .= ' AND';
                            }
                            $sql .= ' `' . $key . '` ' . $val[0] . ' ?';
                            $values[] = $val[1];
                        }
                    }
                } else {
                    $sql .= ' `' . $key . '` = ?';
                    $values[] = $value;
                }

                $sql .= ' LIMIT 1';

                $data = ao()->db->query($sql, $values);
            }
            if(count($data)) {
                if($return_type == 'data') {
                    $item = new $class($data[0]);
                    $output = $item->data;
                } else {
                    $output = new $class($data[0]);
                }
            }
        }

        return $output;
    }

    // TODO: Need to only allow approved values.
    public static function Xby($key, $value = '', $return_type = 'default') {
        $class = get_called_class();
        $table = self::setTable($class);

        $item = null;
        $output = null;
        if($table) {
            if(is_array($key)) {
                if(is_string($value) && in_array($value, ['data', 'default'])) {
                    $return_type = $value;
                }  

                $first = true;
                $sql = 'SELECT * FROM ' . $table . ' WHERE ';
                $values = [];
                foreach($key as $k => $v) {
                    if(!$first) {
                        $sql .= ' AND ';
                    }

                    if(is_array($v) && count($v) == 2) {
                        if(strtoupper($v[1]) == 'NOW()') {
                            $sql .= ' `' . $k . '` ' . $v[0] . ' NOW()';
                        } else {
                            $sql .= ' `' . $k . '` ' . $v[0] . ' ?';
                            $values[] = $v;
                        }
                    } else {
                        $sql .= ' `' . $k . '` = ?';
                        $values[] = $v;
                    }

                    $first = false;
                }
                $sql .= ' LIMIT 1';
                $data = ao()->db->query($sql, $values);
            } else {
                if(is_array($value) && count($value) == 2) {
                    $data = ao()->db->query('SELECT * FROM ' . $table . ' WHERE `' . $key . '` ' . $value[0] . ' ? LIMIT 1', $value[1]);
                } else {
                    $data = ao()->db->query('SELECT * FROM ' . $table . ' WHERE `' . $key . '` = ? LIMIT 1', $value);
                }
            }
            if(count($data)) {
                if($return_type == 'data') {
                    $item = new $class($data[0]);
                    $output = $item->data;
                } else {
                    $output = new $class($data[0]);
                }
            }
        }

        return $output;
    }

    // TODO: *Need to add protection for user passed in columns.
    public static function count($key = '', $value = '', $return_type = 'default') {
        if(is_string($value) && in_array($value, ['default', 'pagination']) && $return_type == 'default') {
            $return_type = $value;
        } elseif(is_array($value) && isset($value[0]) && is_numeric($value[0])) {
            $return_type = $value;
        } 

        $class = get_called_class();
		$table = self::setTable($class);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $url_default = self::setURL($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        if($return_type == 'pagination') {
            $output = []; 
            $output['total_results'] = 0;
            $output['total_pages'] = 1;
            $output['page_previous'] = 1;
            $output['page_next'] = 1;
            $output['page_current'] = 1;
            $output['current_page'] = 1;
            $output['current_result'] = 0;
            $output['current_result_first'] = 0;
            $output['current_result_last'] = 0;
            $output['url_next'] = $url_default;
            $output['url_previous'] = $url_default;
        } else {
            $output = 0; 
        }
        $values = [];

        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT COUNT(id) as total FROM ' . $table . ' WHERE ';
                foreach($key as $k => $v) {
                    if($first) {
                        $first = false;
                    } else {
                        $sql .= ' AND';
                    }
                    if(is_array($v) && isset($v[0]) && isset($v[1]) && in_array($v[0], self::$compare)) {
                        $sql .= ' `' . $k . '` ' . $v[0] . ' ?';
                        $values[] = $v[1];
                    } elseif(is_array($v) && isset($v[0]) && isset($v[0][0]) && in_array($v[0][0], self::$compare)) {
                        foreach($v as $i => $val) {
                            if(isset($val[0]) && in_array($val[0], self::$compare)) {
                                if($i > 0) {
                                    $sql .= ' AND';
                                }
                                $sql .= ' `' . $k . '` ' . $val[0] . ' ?';
                                $values[] = $val[1];
                            }
                        }
                    } else {
                        $sql .= ' `' . $k . '` = ?';
                        $values[] = $v;
                    }
                }

                $data = ao()->db->query($sql, $values);
            } elseif($key) {
                $sql = 'SELECT COUNT(id) as total FROM ' . $table . ' WHERE';
                if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                    $sql .= ' `' . $key . '` ' . $value[0] . ' ?';
                    $values[] = $value[1];
                } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                    foreach($value as $i => $val) {
                        if(isset($val[0]) && in_array($val[0], self::$compare)) {
                            if($i > 0) {
                                $sql .= ' AND';
                            }
                            $sql .= ' `' . $key . '` ' . $val[0] . ' ?';
                            $values[] = $val[1];
                        }
                    }
                } else {
                    $sql = ' `' . $key . '` = ?';
                    $values[] = $value;
                }
                $data = ao()->db->query($sql, $values);
            } else {
                $sql = 'SELECT COUNT(id) as total FROM ' . $table;
                $data = ao()->db->query($sql);
            }

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
            }
        }

        return $output;
    }

    // TODO: Need to only allow approved values.
    public static function create($args) {
        $class = get_called_class();
        $item = new $class($args);
        $item->save();
        return $item;
    }

    // TODO: Need to only allow approved values.
    // TODO: Need to handle errors.
    // TODO: Should there be a non-static version of this method?
    public static function delete($id) {
        $class = get_called_class();
		$table = self::setTable($class);
        if($table) {
            if(is_array($id)) {
                $first = true;
                $sql = 'DELETE FROM ' . $table . ' WHERE ';
                $values = [];
                foreach($id as $k => $v) {
                    if($first) {
                        $sql .= $k . ' = ?';
                        $values[] = $v;
                        $first = false;
                    } else {
                        $sql .= ' AND ' . $k . ' = ?';
                        $values[] = $v;
                    }   
                }   
                $data = ao()->db->query($sql, $values);
            } else {
                ao()->db->query('DELETE FROM ' . $table . ' WHERE id = ?', $id);
            }   
        } 
    }

    // TODO: Need to cache the results so that dynamic values aren't constantly being created.
    public static function find($id, $return_type = 'default') {
        $class = get_called_class();
		$table = self::setTable($class);
        $item = null;
        $output = null;
        if($table) {
            $data = ao()->db->query('SELECT * FROM ' . $table . ' WHERE id = ? LIMIT 1', $id);
            if(count($data)) {
                if($return_type == 'data') {
                    $item = new $class($data[0]);
                    $output = $item->data;
                } else {
                    $output = new $class($data[0]);
                }
            }
        }

        return $output;
    }

    public function insert($input) {
        $items = [];
        $items['created_at'] = new DateTime();
        $items['updated_at'] = new DateTime();

        $input = array_merge($items, $input);

        // If columns are set, make sure only those are used.
        if(count($this->clmns)) {
            $input = array_intersect_key($input, array_flip($this->clmns));
        }

        // Make sure to include created_at and updated_at
        $sql = 'INSERT INTO ' . $this->tbl . ' SET ';
        $args = [];
        foreach($input as $key => $value) {
            // Prep data (like converting DateTime to string
            if($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if(count($args) > 0) {
                $sql .= ',';
            }
            $sql .= '`' . $key . '`' . ' = ?';
            $args[] = $value;
        }

        //$this->db->query($sql, $args);
        ao()->db->query($sql, $args);

        //$this->data['id'] = $this->db->lastInsertId();
        $this->data['id'] = ao()->db->lastInsertId();
        $this->id = $this->data['id'];

        // Reload the data
        $this->data = self::find($this->id)->data;
    }

    public static function query() {
        $class = get_called_class();
        //$table = $class::$table;
        $output = [];

        $args = func_get_args();
        $data = ao()->db->query($args[0], array_slice($args, 1));

        foreach($data as $item) {
            $output[] = new $class($item);

            /*
            if($return_type == 'data') {
                $item = new $class($item);
                $output[] = $item->data;
            } else {
                $output[] = new $class($item);
            }
             */
        }

        return $output;
    }

    public function save() {
        // Removeing the process hooks both before and after:
        // 1. It does not make sense to use the same hook twice.
        // 2. These hooks are now called with the update() and insert() methods when the data is reloaded.
        //
        // Process the data both before and after.
        //$this->data = ao()->hook('ao_model_process_data', $this->data);
        //$this->data = ao()->hook('ao_model_process_' . $this->tbl . '_data', $this->data);
        if(isset($this->data['id'])) {
            $class = get_called_class();
            $item = $class::find($this->data['id']);

            // If item exists, run update otherwise run insert.
            if($item) {
                // Remove the updated_at date.
                unset($this->data['updated_at']);
                $this->update($this->data);
            } else {
                $this->insert($this->data);
            }
        } else {
            $this->insert($this->data);
        }

        //$this->data = ao()->hook('ao_model_process_data', $this->data);
        //$this->data = ao()->hook('ao_model_process_' . $this->tbl . '_data', $this->data);
        $this->data = ao()->hook('ao_model_save_data', $this->data);
        $this->data = ao()->hook('ao_model_save_' . $this->tbl . '_data', $this->data);
    }

    public static function setItem($item, $return_type, $table) {
        $limit = -1;
        $page = -1;
        $offset = -1;
        $url = '/';

        if(is_array($return_type)) {
            $count = count($return_type);
            if(
                $count == 4 
                && is_numeric($return_type[0])
                && is_numeric($return_type[1])
                && in_array($return_type[2], ['default', 'data', 'pagination'])
                && is_string($return_type[3])
            ) {
                $limit = $return_type[0];
                $page = $return_type[1];
                $offset = $limit * ($page - 1);
                $url = $return_type[3];

                // This one needs to be last.
                $return_type = $return_type[2];
            } elseif(
                $count == 3 
                && is_numeric($return_type[0])
                && is_numeric($return_type[1])
                && in_array($return_type[2], ['default', 'data', 'pagination'])
            ) {
                $limit = $return_type[0];
                $page = $return_type[1];
                $offset = $limit * ($page - 1);
                $return_type = $return_type[2];
            } elseif(
                $count == 2 
                && is_numeric($return_type[0])
                && is_numeric($return_type[1])
            ) {
                $limit = $return_type[0];
                $page = $return_type[1];
                $offset = $limit * ($page - 1);
                $return_type = 'default';
            } elseif(
                $count == 2 
                && is_numeric($return_type[0])
                && in_array($return_type[1], ['default', 'data', 'pagination'])
            ) {
                $limit = $return_type[0];
                $return_type = $return_type[1];
            } elseif(
                $count == 1 
                && is_numeric($return_type[0])
            ) {
                $limit = $return_type[0];
                $return_type = 'default';
            }
        }

        if($item == 'limit') {
            $limit = ao()->hook('ao_model_limit', $limit, $table);
            $limit = ao()->hook('ao_model_limit_' . $table, $limit, $table);
            return $limit;
        } elseif($item == 'offset') {
            $offset = ao()->hook('ao_model_offset', $offset, $table);
            $offset = ao()->hook('ao_model_offset_' . $table, $offset, $table);
            return $offset;
        } elseif($item == 'page') {
            $page = ao()->hook('ao_model_page', $page, $table);
            $page = ao()->hook('ao_model_page_' . $table, $page, $table);
            return $page;
        } elseif($item == 'return_type') {
            $return_type = ao()->hook('ao_model_return_type', $return_type, $table);
            $return_type = ao()->hook('ao_model_return_type_' . $table, $return_type, $table);
            return $return_type;
        } elseif($item == 'url') {
            $url = ao()->hook('ao_model_url', $url, $table);
            $url = ao()->hook('ao_model_url_' . $table, $url, $table);
            return $url;
        }
    }

    public static function setLimit($return_type, $table) {
        return self::setItem('limit', $return_type, $table);
    }

    public static function setOffset($return_type, $table) {
        return self::setItem('offset', $return_type, $table);
    }

    public static function setOrder($table) {
        $class = get_called_class();
        $order = $class::$order;
        $order = ao()->hook('ao_model_order', $order, $table);
        $order = ao()->hook('ao_model_order_' . $table, $order, $table);
        return $order;
    }

    public static function setPage($return_type, $table) {
        return self::setItem('page', $return_type, $table);
    }

    public static function setReturnType($return_type, $table) {
        return self::setItem('return_type', $return_type, $table);
    }

    public static function setTable($class) {
        $table = $class::$table;
        $table = ao()->hook('ao_model_table', $table);
        $table = ao()->hook('ao_model_table_' . $table, $table);
        return $table;
    }

    public static function setURL($return_type, $table) {
        return self::setItem('url', $return_type, $table);
    }

    // If update is being called using $this->save(), then updated_at is automatically unset
    // May need to add a way so that $this->save() can modify updated_at value.
    public function update($input = []) {
        $items = [];
        $items['updated_at'] = new DateTime();

        $input = array_merge($items, $input);

        // If columns are set, make sure only those are used.
        if(count($this->clmns)) {
            $input = array_intersect_key($input, array_flip($this->clmns));
        }

        // Make sure to include created_at and updated_at
        $sql = 'UPDATE ' . $this->tbl . ' SET ';
        $args = [];
        foreach($input as $key => $value) {
            // Prep data (like converting DateTime to string
            if($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            if(count($args) > 0) {
                $sql .= ',';
            }
            $sql .= '`' . $key . '`' . ' = ?';
            $args[] = $value;
        }
        $sql .= ' WHERE id = ?';
        //$args[] = $input['id'];
        $args[] = $this->id;

        //$this->db->query($sql, $args);
        ao()->db->query($sql, $args);

        // Reload the data
        $this->data = self::find($this->id)->data;
    }

    public static function updateWhere($input = [], $key = '', $value = '') {
        $class = get_called_class();
        $items = [];
        $items['updated_at'] = new DateTime();

        $input = array_merge($items, $input);

        // If columns are set, make sure only those are used.
        // TODO: Make clmns static
        //if(count($class::$clmns)) {
            //$input = array_intersect_key($input, array_flip($this->clmns));
        //}

        // Make sure to include created_at and updated_at
        $sql = 'UPDATE ' . $class::$table . ' SET ';
        $args = [];
        foreach($input as $k => $v) {
            // Prep data (like converting DateTime to string
            if($v instanceof DateTime) {
                $v = $v->format('Y-m-d H:i:s');
            }

            if(count($args) > 0) {
                $sql .= ', ';
            }
            $sql .= '`' . $k . '`' . ' = ?';
            $args[] = $v;
        }
        if($key && $value) {
            $sql .= ' WHERE `' . $key . '` = ?';
            $args[] = $value;
        } elseif(is_array($key)) {
            $first = true;
            $sql .= ' WHERE';
            foreach($key as $k => $v) {
                if($first) {
                    $first = false;
                } else {
                    $sql .= ' AND';
                }
                if(is_array($v) && isset($v[0]) && isset($v[1]) && in_array($v[0], self::$compare)) {
                    $sql .= ' `' . $k . '` ' . $v[0] . ' ?';
                    $args[] = $v[1];
                } elseif(is_array($v) && isset($v[0]) && isset($v[0][0]) && in_array($v[0][0], self::$compare)) {
                    foreach($v as $i => $val) {
                        if(isset($val[0]) && in_array($val[0], self::$compare)) {
                            if($i > 0) {
                                $sql .= ' AND';
                            }
                            $sql .= ' `' . $k . '` ' . $val[0] . ' ?';
                            $args[] = $val[1];
                        }
                    }
                } else {
                    $sql .= ' `' . $k . '` = ?';
                    $args[] = $v;
                }
            }
        }

        //echo '<pre>'; print_r($args);die;
        //echo $sql;die;
        ao()->db->query($sql, $args);
    }

    // TODO: *Need to add protection for user passed in columns.
    // Note that pagination will have performance issues with large datasets:
    // https://mysql.rjweb.org/doc.php/pagination
    public static function where($key, $value = '', $return_type = 'default') {
        if(is_string($value) && in_array($value, ['data', 'default']) && $return_type == 'default') {
            $return_type = $value;
        } elseif(is_array($value) && isset($value[0]) && is_numeric($value[0])) {
            $return_type = $value;
        } 

        $class = get_called_class();
        $table = self::setTable($class);
        $order = self::setOrder($table);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = []; 
        $values = [];

        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT * FROM ' . $table . ' WHERE ';
                foreach($key as $k => $v) {
                    if($first) {
                        $first = false;
                    } else {
                        $sql .= ' AND';
                    }
                    if(is_array($v) && isset($v[0]) && isset($v[1]) && in_array($v[0], self::$compare)) {
                        $sql .= ' `' . $k . '` ' . $v[0] . ' ?';
                        $values[] = $v[1];
                    } elseif(is_array($v) && isset($v[0]) && isset($v[0][0]) && in_array($v[0][0], self::$compare)) {
                        foreach($v as $i => $val) {
                            if(isset($val[0]) && in_array($val[0], self::$compare)) {
                                if($i > 0) {
                                    $sql .= ' AND';
                                }
                                $sql .= ' `' . $k . '` ' . $val[0] . ' ?';
                                $values[] = $val[1];
                            }
                        }
                    } else {
                        $sql .= ' `' . $k . '` = ?';
                        $values[] = $v;
                    }
                }
                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(count($order)) {
                    $sql .= ' ORDER BY';
                    $count = 0;
                    foreach($order as $field => $direction) {
                        if($count == 0) {
                            $sql .= ' `' . $field . '` ' . $direction;
                        } else {
                            $sql .= ', ' . $field . ' ' . $direction;
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
            } else {
                $sql = 'SELECT * FROM ' . $table . ' WHERE';
                if(is_array($value) && isset($value[0]) && isset($value[1]) && in_array($value[0], self::$compare)) {
                    $sql .= ' `' . $key . '` ' . $value[0] . ' ?';
                    $values[] = $value[1];
                } elseif(is_array($value) && isset($value[0]) && isset($value[0][0]) && in_array($value[0][0], self::$compare)) {
                    foreach($value as $i => $val) {
                        if(isset($val[0]) && in_array($val[0], self::$compare)) {
                            if($i > 0) {
                                $sql .= ' AND';
                            }
                            $sql .= ' `' . $key . '` ' . $val[0] . ' ?';
                            $values[] = $val[1];
                        }
                    }
                } else {
                    $sql .= ' `' . $key . '` = ?';
                    $values[] = $value;
                }

                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(count($order)) {
                    $sql .= ' ORDER BY';
                    $count = 0;
                    foreach($order as $field => $direction) {
                        if($count == 0) {
                            $sql .= ' `' . $field . '` ' . $direction;
                        } else {
                            $sql .= ', ' . $field . ' ' . $direction;
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
            }
            foreach($data as $item) {
                if($return_type == 'data') {
                    $item = new $class($item);
                    $output[] = $item->data;
                } else {
                    $output[] = new $class($item);
                }
            }
        }

        return $output;
    }

    public static function whereIn($key, $list = [], $and = [], $return_type = 'default') {
        // This is set up to handle pagination too.
        // If isset($and[0]) - if '0' is a $key, then it is not a normal $and array that would use string keys.
        if(
            (is_string($and) && in_array($and, ['data', 'default'])) 
            || (is_array($and) && isset($and[0]))
        ) {
            $return_type = $and;
            $and = [];
        } 

        $class = get_called_class();
        $table = self::setTable($class);
        $order = self::setOrder($table);
        $limit = self::setLimit($return_type, $table);
        $page = self::setPage($return_type, $table);
        $offset = self::setOffset($return_type, $table);
        $return_type = self::setReturnType($return_type, $table);

        $output = [];
        $values = [];

        if($table && count($list)) {
            $sql = 'SELECT * FROM ' . $table . ' WHERE `' . $key . '` IN (';
            foreach($list as $item) {
                $sql .= '?,';
                $values[] = $item;
            }
            $sql = trim($sql, ',');
            $sql .= ')';

            if(count($and)) {
                foreach($and as $k => $v) {
                    if(is_array($v) && isset($v[0]) && isset($v[1]) && in_array($v[0], self::$compare)) {
                        $sql .= ' AND `' . $k . '` ' . $v[0] . ' ?';
                        $values[] = $v[1];
                    } elseif(is_array($v) && isset($v[0]) && isset($v[0][0]) && in_array($v[0][0], self::$compare)) {
                        foreach($v as $i => $val) {
                            if(isset($val[0]) && in_array($val[0], self::$compare)) {
                                $sql .= ' AND `' . $k . '` ' . $val[0] . ' ?';
                                $values[] = $val[1];
                            }
                        }
                    } else {
                        $sql .= ' AND ' . $k . ' = ?';
                        $values[] = $v;
                    }
                }
            }

            // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
            if(is_array($order) && count($order)) {
                $sql .= ' ORDER BY';
                $count = 0;
                foreach($order as $field => $direction) {
                    if($count == 0) {
                        $sql .= ' `' . $field . '` ' . $direction;
                    } else {
                        $sql .= ', ' . $field . ' ' . $direction;
                    }
                    $count++;
                }
            }

            if(is_numeric($limit) && $limit > 0 && is_numeric($offset) && $offset > 0) {
                $sql .= ' LIMIT ' . $limit . ' OFFSET ' . $offset;
            } elseif(is_numeric($limit) && $limit > 0) {
                $sql .= ' LIMIT ' . $limit;
            }

            //$data = ao()->db->query($sql, $list);
            $data = ao()->db->query($sql, $values);

            foreach($data as $item) {
                if($return_type == 'data') {
                    $item = new $class($item);
                    $output[] = $item->data;
                } else {
                    $output[] = new $class($item);
                }
            }
        }

        return $output;
    }

}
