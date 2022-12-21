<?php

namespace mavoc\core;

class GenericController {
    public function view($req, $res, $view) {
        //echo 'generic';
        //echo '<br>';
        //echo $view;
        //echo '<br>';
        //echo '<br>';
        $res->view($view);
    }
}
