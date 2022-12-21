<?php

namespace mavoc\core;

class Response {
    public $output = ''; 

    public $args = []; 
    public $fields = []; 
    public $html; 
    public $req; 
    public $session; 

    public function __construct() {
        register_shutdown_function([$this, 'send']);
    }   

    public function init() {
    }

    public function back() {
        $url = $this->req->last_url;


        $this->redirect($url);
    }

    public function error($message, $redirect = null) {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $output = [];
            $output['status'] = 'error';
            if(is_array($message)) {
                $output['messages'] = $message;
            } else {
                $output['messages'] = [$message];
            }
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($output);
            exit;
        } else {
            $this->flash('error', $message);

            if($redirect !== false) {
                // If fields are not set and there is $_POST data, automatically set the $_POST data as the fields.
                if(!isset($this->session->next_flash['fields']) && count($this->req->data)) {
                    $this->flash('fields', $this->req->data);
                }

                if($redirect) {
                    $this->redirect($redirect);
                } else {
                    $this->back();
                }
            }
        }
    }   

    public function flash($type, $value) {
        $this->session->flash($type, $value);
    }

    public function json($vars) {
        header('Content-Type: application/json; charset=utf-8');
        $this->output = json_encode($vars);
    }

    public function notice($type, $message, $redirect = null) {
        $this->flash($type, $message);

        if($redirect !== false) {
            // If fields are not set and there is $_POST data, automatically set the $_POST data as the fields.
            if(!isset($this->session->next_flash['fields']) && count($this->req->data)) {
                $this->flash('fields', $this->req->data);
            }

            if($redirect) {
                $this->redirect($redirect);
            } else {
                $this->back();
            }
        }
    }   

    public function _partial($view, $args = []) {   
		$output = '';
		$dir = ao()->env('AO_APP_DIR') . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR;
		$file = $view . '.php';
		$path = $dir . $file;

        $args = array_merge($this->args, $args);
        $args = ao()->hook('ao_response_partial_args', $args, $view, $this->req, $this);

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

    public function _pathClass() {
        $output = '';
        $parts = explode('/', trim($this->req->path, '/'));

        if(count($parts) == 1 && $parts[0] == '') {
            $output = 'page_home';
        } else {
            $output = 'page_';
            $output .= implode('_', $parts);
        }

        return $output;
    }
    public function pathClass() {
        echo $this->_pathClass();
    }

    public function redirect($url, $code = 302) {
        if(strtolower(substr($url, 0, 4) != 'http')) {
            $url = _uri($url);
        }
        header('Location: ' . $url, true, $code);
        exit;
    }

    public function send() {
        echo $this->output;
        $this->output = ''; 
    }   

    public function status($code) {
        if($code == 404) {
            $title = 'Not Available';
            http_response_code($code);
            $this->view('alt/404', compact('title'));
        } else {
            // TODO: Make this exit cleaner like 404 above.
            // Maybe have a generic error page.
            // Maybe have prebuilt error views in the mavoc directory.
            http_response_code($code);
            echo 'Error code: ' . $code;
            exit;
        }
    }   

    public function success($message, $redirect = null) {
        $this->flash('success', $message);

        if($redirect !== false) {
            if($redirect) {
                $this->redirect($redirect);
            } else {
                $this->back();
            }
        }
    }   

    public function view($view, $args = []) {
        $file = ao()->dir('app/views') . DIRECTORY_SEPARATOR . $view . '.php';

        // Check if $view is a file
        if(is_file($file)) {
            if(!isset($args['user'])) {
                $args['user'] = $this->session->user;
            }
            if(!isset($args['flash'])) {
                $args['flash'] = $this->session->flash;
            }
            if(!isset($args['res'])) {
                $args['res'] = $this;
            }
            if(!isset($args['req'])) {
                $args['req'] = $this->req;
            }
            if(!isset($args['title'])) {
                $default_title = ao()->env('APP_NAME');
                $default_title = ao()->hook('ao_response_default_title', $default_title);
                $args['title'] = $default_title;
            } else {
                $args['title'] = ao()->hook('ao_response_preset_title', $args['title']);
            }

            // Save the args for use in the partials.
            $args = ao()->hook('ao_response_view_args', $args, $view, $this->req, $this);
            $this->args = $args;

            $file = ao()->hook('ao_response_view_file', $file, $view, $args, $this->req, $this);

            // Be careful, this could be dangerous if used with untrusted data.
            // Also has some gotchas. Read the docs and comments: 
            // https://www.php.net/manual/en/function.extract.php
            extract($args);

            ob_start();
            include $file;
            $this->output .= ob_get_clean();

            return true;
        }

        return false;
    }

    public function write($contents) {
        $this->output .= $contents;
    }
}



/*
class Response {
    public $output = ''; 

    public function __construct() {
        register_shutdown_function([$this, 'send']);
    }   

    public function init() {
    }

    public function error($code) {
        $item = []; 
        $item['title'] = 'Not Available';
        http_response_code($code);
        $this->view('404', compact('item'));
    }   

    public function send() {
        echo $this->output;
        $this->output = ''; 
    }   

    public function view($view, $args) {
        // Check if $view is a file
        if(is_file(Config::$views . $view . '.php')) {
            extract($args);
            ob_start();
            include Config::$views . $view . '.php';
            $this->output .= ob_get_clean();
        }
    }

    public function write($contents) {
        $this->output .= $view;
    }
}
 */
