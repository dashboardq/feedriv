<?php

namespace mavoc\console;

// Need to add stdin interface.
class In {
    public $data = [];
    public $params = []; 

    public function __construct() {
        global $argv;
        $this->data = $argv;

        //$this->params = $this->data;
    }

    public function init() {
    }

}
