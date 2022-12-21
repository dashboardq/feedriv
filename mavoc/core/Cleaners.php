<?php

namespace mavoc\core;

class Cleaners {
    public function __construct() {
    }

    // Dynamic rules: 
    // https://stackoverflow.com/questions/7026487/how-to-add-methods-dynamically
    public function __call($name, $arguments) {
        return call_user_func_array($this->{$name}, $arguments);
    }

    public function _add($name, $method) {
        $this->{$name} = $method;
    }

    public function int($value) {
        // If it is not an integer, set it to 0.
        if(filter_var($value, FILTER_VALIDATE_INT) === false) {
            return 0;
        }

        return $value;
    }   
}
