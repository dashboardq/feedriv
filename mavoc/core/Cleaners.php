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

    public function array($value, $default = null) {
        if(!is_array($value)) {
            if($default !== null) {
                return $default;
            } else {
                $value = [];
            }
        }

        return $value;
    }

    public function boolean($value, $default = null) {
        if(
            $value == 1
            || $value == 'yes' 
            || $value == 'true'
            || $value === true
        ) {
            return 1;
        } else {
            if($default !== null) {
                return $default;
            }
            return 0;
        }
    }

    public function int($value, $default = null) {
        // If it is not an integer, set it to 0.
        if(filter_var($value, FILTER_VALIDATE_INT) === false) {
            if($default !== null) {
                return $default;
            } else {
                return 0;
            }
        }

        return $value;
    }   

    public function int2($value, $default = null) {
        if(!value || !is_numeric($value)) {
            if($default !== null) {
                return $default;
            } else {
                $value = 0;
            }
        }
        $output = $value * 100;
        return $output;
    }

    public function lowercase($value) {
        $output = strtolower($value);
        return $output;
    }
    public function uppercase($value) {
        $output = strtoupper($value);
        return $output;
    }
}
