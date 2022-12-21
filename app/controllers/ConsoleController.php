<?php

namespace app\controllers;

class ConsoleController {
    public function example($in, $out) {
        $out->write('This is an example.', 'green');
    }

    public function view($in, $out) {
        $out->view('console/view', ['color' => 'red']);
    }
}
