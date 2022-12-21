<?php

namespace mavoc\core;

use mavoc\console\In;
use mavoc\console\Out;
use mavoc\console\Router;

class Console {
    public $app;
    public $confs;
    public $db;
    public $email;
    public $envs = [];
    public $in;
    public $hooks;
    public $out;
    public $plugins;
    public $router;

    public function __construct() {
    }

    public function init() {
    }

    public function call($command) {
        $this->router = new Router();
        $this->router = ao()->hook('ao_console_router', $this->router);
        $func = [$this->router, 'init'];
        $func = ao()->hook('ao_console_router_init', $func);
        call_user_func($func);

        $this->in = new In();
        $this->in = ao()->hook('ao_console_in', $this->in);
        $func = [$this->in, 'init'];
        $func = ao()->hook('ao_console_in_init', $func);
        call_user_func($func);

        $this->in->data = array_merge(['ao'], explode(' ', $command));

        $this->out = new Out();
        $this->out = ao()->hook('ao_console_out', $this->out);
        $func = [$this->out, 'init'];
        $func = ao()->hook('ao_console_out_init', $func);
        call_user_func($func);

        ob_start();
        $output = '';

        if(ao()->hook('ao_console_route', true)) {
            $this->router->route($this->in, $this->out);
        }

        // Capture all output
        $output .= ob_get_clean();

        return $output;
    }
}

