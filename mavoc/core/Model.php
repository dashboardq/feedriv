<?php

namespace mavoc\core;

use DateTime;

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
        }

        $this->data = ao()->hook('ao_model_process_data', $this->data);
        $this->data = ao()->hook('ao_model_process_' . $this->tbl . '_data', $this->data);

        $this->id = ao()->hook('ao_model_process_id', $this->id);
        $this->id = ao()->hook('ao_model_process_' . $this->tbl . '_id', $this->id);
    }   

    public function init() {
    }

    public static function all($return_type = 'all') {
        $class = get_called_class();
        $table = $class::$table;
        $output = [];
        if($table) {
            $sql = 'SELECT * FROM ' . $table;
            // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
            if(count($class::$order)) {
                $sql .= ' ORDER BY';
                $count = 0;
                foreach($class::$order as $field => $direction) {
                    if($count == 0) {
                        $sql .= ' `' . $field . '` ' . $direction;
                    } else {
                        $sql .= ', ' . $field . ' ' . $direction;
                    }   
                    $count++;
                }   
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

    // TODO: Need to only allow approved values.
    public static function by($key, $value = '', $return_type = 'all') {
        $class = get_called_class();
        $table = $class::$table;
        $item = null;
        $output = null;
        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT * FROM ' . $table . ' WHERE ';
                $values = [];
                foreach($key as $k => $v) {
                    if(!$first) {
                        $sql .= ' AND ';
                    }

                    if(is_array($v) && count($v) == 2) {
                        if(strtoupper($v[1]) == 'NOW()') {
                            $sql .= '`' . $k . '` ' . $v[0] . ' NOW()';
                        } else {
                            $sql .= '`' . $k . '` ' . $v[0] . ' ?';
                            $values[] = $v;
                        }
                    } else {
                        $sql .= '`' . $k . '` = ?';
                        $values[] = $v;
                    }

                    $first = false;
                }
                $sql .= ' LIMIT 1';
                $data = ao()->db->query($sql, $values);
            } else {
                $data = ao()->db->query('SELECT * FROM ' . $table . ' WHERE ' . $key . ' = ? LIMIT 1', $value);
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
    public static function count($key = '', $value = '') {
        $class = get_called_class();
        $table = $class::$table;
        $output = [];
        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT COUNT(id) as total FROM ' . $table . ' WHERE ';
                $values = [];
                foreach($key as $k => $v) {
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
            } elseif($key) {
                $sql = 'SELECT COUNT(id) as total FROM ' . $table . ' WHERE ' . $key . ' = ?';
                $data = ao()->db->query($sql, $value);
            } else {
                $sql = 'SELECT COUNT(id) as total FROM ' . $table;
                $data = ao()->db->query($sql);
            }

            if(count($data)) {
                return $data[0]['total'];
            } else {
                return 0;
            }
        }

        return 0;
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
    public static function delete($id) {
        $class = get_called_class();
        $table = $class::$table;
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
    public static function find($id, $return_type = 'all') {
        $class = get_called_class();
        $table = $class::$table;
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
    }

    public function save() {
        // Process the data both before and after.
        $this->data = ao()->hook('ao_model_process_data', $this->data);
        $this->data = ao()->hook('ao_model_process_' . $this->tbl . '_data', $this->data);
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

        $this->data = ao()->hook('ao_model_process_data', $this->data);
        $this->data = ao()->hook('ao_model_process_' . $this->tbl . '_data', $this->data);
        $this->data = ao()->hook('ao_model_save_data', $this->data);
        $this->data = ao()->hook('ao_model_save_' . $this->tbl . '_data', $this->data);
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
            $sql .= ' WHERE ';
            foreach($key as $k => $v) {
                if($first) {
                    $sql .= '`' . $k . '` = ?';
                    $args[] = $v;
                    $first = false;
                } else {
                    $sql .= ' AND `' . $k . '` = ?';
                    $args[] = $v;
                }
            }
        }

        //echo '<pre>'; print_r($args);die;
        //echo $sql;die;
        ao()->db->query($sql, $args);
    }

    // TODO: *Need to add protection for user passed in columns.
    public static function where($key, $value = '', $return_type = 'all') {
        $class = get_called_class();
        $table = $class::$table;
        $output = [];
        if($table) {
            if(is_array($key)) {
                $first = true;
                $sql = 'SELECT * FROM ' . $table . ' WHERE ';
                $values = [];
                foreach($key as $k => $v) {
                    if($first) {
                        $sql .= $k . ' = ?';
                        $values[] = $v;
                        $first = false;
                    } else {
                        $sql .= ' AND ' . $k . ' = ?';
                        $values[] = $v;
                    }
                }
                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(count($class::$order)) {
                    $sql .= ' ORDER BY';
                    $count = 0;
                    foreach($class::$order as $field => $direction) {
                        if($count == 0) {
                            $sql .= ' `' . $field . '` ' . $direction;
                        } else {
                            $sql .= ', ' . $field . ' ' . $direction;
                        }
                        $count++;
                    }
                }

                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(isset($class::$limit)) {
                    $sql .= ' LIMIT ' . $class::$limit;
                }
                $data = ao()->db->query($sql, $values);
            } else {
                $sql = 'SELECT * FROM ' . $table . ' WHERE ' . $key . ' = ?';
                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(count($class::$order)) {
                    $sql .= ' ORDER BY';
                    $count = 0;
                    foreach($class::$order as $field => $direction) {
                        if($count == 0) {
                            $sql .= ' `' . $field . '` ' . $direction;
                        } else {
                            $sql .= ', ' . $field . ' ' . $direction;
                        }   
                        $count++;
                    }       
                }       

                // TODO: This is dangerous and needs to be cleaned up - only pass trusted data.
                if(isset($class::$limit)) {
                    $sql .= ' LIMIT ' . $class::$limit;
                }
                $data = ao()->db->query($sql, $value);
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

    public static function whereIn($key, $list = [], $and = [], $return_type = 'all') {
        $class = get_called_class();
        $table = $class::$table;
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
                    $sql .= ' AND ' . $k . ' = ?';
                    $values[] = $v;
                }
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
