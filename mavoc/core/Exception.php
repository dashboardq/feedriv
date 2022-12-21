<?php

namespace mavoc\core;

use Exception as OriginalException;

class Exception extends OriginalException {
    public $redirect = '';

    public function __construct($message = '', $redirect = '', $code = 0, Throwable $previous = null) {
        $this->redirect = $redirect;
        parent::__construct($message, $code, $previous);
    }

    public function getRedirect() {
        return $this->redirect;
    }
}

