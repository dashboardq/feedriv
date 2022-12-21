<?php

namespace mavoc\console\controllers;

class MainController {
    public function help($in, $out) {
        out('The help command is not available at this time.', 'green');
    }

    public function work($in, $out) {
        out('The ao command appears to work.', 'green');
    }

}

