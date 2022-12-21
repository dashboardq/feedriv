<?php

namespace mavoc\core;

// Currently recommend the order from less specific to more specific
// ao()->hook('some_event', $event);
// ao()->hook('some_event_' . $details, $event);
class Hooks {
    public $filters = [];
    public $id = -1;
    public $keys = [];
    public $used = [];

    public function __construct() {
    }

    public function init() {
    }

    public function filter($key, $callback, $priority = 10) {
        if(!isset($this->filters[$key])) {
            $this->filters[$key] = [];
        }

        if(!isset($this->filters[$key][$priority])) {
            $this->filters[$key][$priority] = [];
        }

        $this->id++;
        $this->filters[$key][$priority][$this->id] = $callback;
        $this->keys[$this->id] = [
            'key' => $key,
            'priority' => $priority,
            'id' => $this->id,
        ];
        ksort($this->filters[$key], SORT_NUMERIC);

        return $this->id;
    }

    //public function hook($key, $item = null, $args = []) {
    //}
    public function hook() {
        $func_args = func_get_args();
        $key = $func_args[0];
        $item = null;
        if(isset($func_args[1])) {
            $item = $func_args[1];
        }
        $args = [];
        $args[] = $item;
        for($i = 2; $i < count($func_args); $i++) {
            $args[] = $func_args[$i];
        }
        if(ao()->env('AO_OUTPUT_HOOKS')) {
            echo $key;
            echo '<br>';
        }
        if(isset($this->filters[$key])) {
            foreach($this->filters[$key] as $group) {
                foreach($group as $id => $filter) {
                    if(!isset($this->used[$id])) {
                        //$item = call_user_func($filter, $item, $args);
                        $args[0] = call_user_func_array($filter, $args);
                    } elseif(isset($this->used[$id]) && !$this->used[$id]) {
                        $this->used[$id] = true;
                        $args[0] = call_user_func_array($filter, $args);
                    }
                }
            }
        }

        return $args[0];
    }

    public function once($key, $callback, $priority = 10) {
        $this->filter($key, $callback, $priority = 10);

        $this->used[$this->id] = false;

        return $this->id;
    }

    public function unfilter($key, $callback = null, $priority = 10) {
        // If no callback, then an id was passed in.
        if(!$callback) {
            $id = $key;
            if(isset($this->keys[$id])) {
                $keys = $this->keys[$id];
                unset($this->filters[$keys['key']][$keys['priority']][$id]);
            }
        } else {
            $id = false;
            foreach($this->filters[$key] as $tmp_priority => $group) {
                foreach($group as $tmp_id => $filter) {
                    if($priority == $tmp_priority && $callback == $filter) {
                        $id = $tmp_id;
                        break 2;
                    }
                }
            }

            if($id !== false) {
                unset($this->filters[$key][$priority][$id]);
            }
        }
    }
}
