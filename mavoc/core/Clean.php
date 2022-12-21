<?php

namespace mavoc\core;

class Clean {
    public $cleaners;
    public $fields;

    public function __construct($input, $list = [], $req = null, $res = null) {
        $this->cleaners = new Cleaners();
        $this->cleaners = ao()->hook('ao_cleaners', $this->cleaners);

        $this->fields = [];

        $pass = true;   
        $messages = [];
        $reserved_names = ['_all'];

        $_all = [];
        if(isset($list['_all']) && is_array($list['_all'])) {
            $_all = array_merge($_all, $list['_all']);
        }

        // Remove any of the reserved fields used above.
        foreach($reserved_names as $name) {
            unset($list[$name]);
        }

        foreach($input as $key => $value) {
            $this->fields[$key] = $value;
        }

        foreach($list as $item => $actions) {
            $this->processActions($item, $actions);
        }

        foreach($this->fields as $item => $value) {
            $this->processActions($item, $_all);
        }

    }

    public function processActions($item, $actions) {
        foreach($actions as $action) {
            if(is_array($action)) {
                $keys = array_keys($action);
                $key = $keys[0];
            }

            $methods = get_class_methods($this->cleaners);

            // TODO: This is broken, need to fix. (sometimes you come back to a note you wrote previously
            // and realize you should have added more details - like this note - I'm not sure what needs
            // to be fixed) (another follow up, I think the problem is that is_callable will always return
            // true when checking against a class with __call() set up).
            if(function_exists($action)) {
                $result = $action($this->fields[$item]);
                $this->fields[$item] = $result;
            } elseif(is_callable([$this->cleaners, $action])) {
                $result = call_user_func([$this->cleaners, $action], $this->fields[$item]);
                $this->fields[$item] = $result;
            } elseif(is_array($action) && in_array($key, $methods)) {
                $args = $action[$key];
                if(!is_array($args)) {
                    $args = [$args];
                }
                $args = array_merge([$this->fields[$item]], $args);
                $result = call_user_func_array([$this->cleaners, $key], $args);
                $this->fields[$item] = $result;
            }
        }
    }
}

