<?php

namespace plugins\example;

class Example {
    public function __construct() {
        ao()->filter('ao_example_hook', [$this, 'exclaim']);
    }

    public function exclaim($input) {
        $input = ao()->hook('example_exlaim_input', $input);

        $output = $input . '!';
        $output = ao()->hook('example_exlaim_output', $output);

        return $output;
    }
}
