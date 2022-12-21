<?php

namespace mavoc\core;

class Plugin {
    public $args = [];
    public $req;
    public $res;
    public $ses;

    public $name = '';
    public $dir = '';

    public function __construct() {
        ao()->filter('ao_request_available', [$this, 'requestAvailable']);
        ao()->filter('ao_response_available', [$this, 'responseAvailable']);
        ao()->filter('ao_session_available', [$this, 'sessionAvailable']);

        $this->name = $this->getPluginName();
        $this->dir = strtolower($this->name);
    }

    public function init() {
    }

    // https://stackoverflow.com/questions/32093354/how-to-define-a-callback-with-parameters-without-closure-and-use-in-php
    // https://stackoverflow.com/a/32095049
    public function bind($callable, $args) {
        if(!is_array($args)) {
            $args = [$args];
        }
        return function() use($callable, $args) {
            return call_user_func_array($callable, $args);
        };
    }

    public function getPluginName() {
        $class = (new \ReflectionClass($this))->getShortName();
        return $class;
    }

    public function _partial($view, $args = []) {   
		$output = '';
        $dir = ao()->dir('plugins/' . $this->dir . '/views/partials/');
		$file = $view . '.php';
		$path = $dir . $file;

        $args = array_merge($this->args, $args);
        $args = ao()->hook('ao_plugin_partial_args', $args, $view, $this->res, $this->req);

        // Be careful, this could be dangerous if used with untrusted data.
        // Also has some gotchas. Read the docs and comments: 
        // https://www.php.net/manual/en/function.extract.php
        extract($args);

        if(is_file($path)) {
            ob_start();
            include $path;
            $output .= ob_get_clean();

        }   

		return $output;
    }

    public function partial($view, $args = []) {
        echo $this->_partial($view, $args);
    }   


    public function requestAvailable($req) {
        $this->req = $req;
        return $req;
    }

    public function responseAvailable($res) {
        $this->res = $res;
        return $res;
    }

    public function sessionAvailable($ses) {
        $this->ses = $ses;
        return $ses;
    }

    public function view($view, $args = []) {
        $file = ao()->dir('plugins/' . $this->dir . '/views') . DIRECTORY_SEPARATOR . $view . '.php';

        // Check if $view is a file
        if(is_file($file)) {
            if(!isset($args['user'])) {
                $args['user'] = $this->ses->user;
            }
            if(!isset($args['flash'])) {
                $args['flash'] = $this->ses->flash;
            }
            if(!isset($args['res'])) {
                $args['res'] = $this->res;
            }
            if(!isset($args['req'])) {
                $args['req'] = $this->req;
            }
            if(!isset($args['title'])) {
                $args['title'] = ao()->env('APP_NAME');
            }

            // Save the args for use in the partials.
            $this->args = $args;

            // Be careful, this could be dangerous if used with untrusted data.
            // Also has some gotchas. Read the docs and comments: 
            // https://www.php.net/manual/en/function.extract.php
            extract($args);

            ob_start();
            include $file;
            $this->output .= ob_get_clean();
        }
    }

}
