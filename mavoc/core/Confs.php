<?php

namespace mavoc\core;

class Confs {
    public $confs;

    public function __construct() {
        /* Currently disabling
         * Trying to decide if .conf.php is needed of if .env.php is enough
        $this->confs = require ao()->env('AO_BASE_DIR') . DIRECTORY_SEPARATOR . '.conf.php';
         */
    }

    public function init() {
    }

    public function conf($key, $value = null) {
        $key = ao()->hook('ao_conf_pre_key', $key);
        $key = ao()->hook('ao_conf_pre_key_' . $key, $key);

        $value = ao()->hook('ao_conf_pre_value', $value);
        $value = ao()->hook('ao_conf_pre_value_' . $key, $value);

        if($value !== null) {
            $this->confs[$key] = $value;
            $this->confs[$key] = ao()->hook('ao_conf_set_value', $this->confs[$key]);
            $this->confs[$key] = ao()->hook('ao_conf_set_value_' . $key, $this->confs[$key]);
        } else {
            $output = $this->confs[$key];
            $output = ao()->hook('ao_conf_get', $output);
            $output = ao()->hook('ao_conf_get_' . $key, $output);
            return $output;
        }
    }
}
