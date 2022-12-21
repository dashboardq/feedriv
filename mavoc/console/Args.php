<?php

namespace mavoc\console;

// Need to add stdout and stderror interfaces.
class Args {
    public $data = [];
    public $output = ''; 
    public $params = []; 

    public function __construct() {
        global $argv;
        $this->data = $argv;

        //$this->params = $this->data;

        register_shutdown_function([$this, 'send']);
    }

    public function init() {
    }

    public function send() {
        echo $this->output;
        $this->output = ''; 
    }   

    public function view($view, $args = []) {
        $file = ao()->dir('app/views') . DIRECTORY_SEPARATOR . $view . '.php';

        // Check if $view is a file
        if(is_file($file)) {
            extract($args);

            $args = $this;

            ob_start();
            include $file;
            $this->output .= ob_get_clean();
        }
    }

}
