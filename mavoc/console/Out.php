<?php

namespace mavoc\console;

// Need to add stdout and stderror interfaces.
class Out {
    public $data = [];
    public $output = ''; 
    public $params = []; 

    public function __construct() {
        register_shutdown_function([$this, 'send']);
    }

    public function init() {
    }

	public function log($input, $color = null, $add_newline = true) {
		$output = $input;

		// I prefer "if" over "switch".
		if($color == 'green') {
			$output = "\033[32m" . $output;
		} elseif($color == 'red') {
			$output = "\033[31m" . $output;
		}   

		if($color) {
			$output .= "\033[0m";
		}   

		if($add_newline) {
			$output .= PHP_EOL;
		}   

		fwrite(STDERR, $output);
	} 

	// Code below writes the prompt to STDERR and then reads the response.
	// readline() does not appear to use STDERR (at least in ZSH if you pipe the output the prompt is not shown). 
	//  
	// https://pubs.opengroup.org/onlinepubs/9699919799/utilities/V3_chap02.html
	// "Each time an interactive shell is ready to read a command, the value of this variable shall be subjected to parameter expansion and written to standard error."
	//  
	// Above link found here: https://github.com/att/ast/issues/1380
	public function prompt($msg, $match = [], $default = '', $error = 'Please select a valid option.') {
		if(is_string($match)) {
			$match = [$match];
		}   

		$loop = 0;
		if(count($match)) {
			do {
				if($error && $loop) {
					$this->log($error, 'red');
				}   
				$this->log($msg);
				$response = readline();
				if($default !== '' && $response === '') {
					$response = $default;
				}   
				$loop++;
			} while(!in_array($response, $match));
		} else {
			$this->log($msg);
			$response = readline();
			if($default !== '' && $response === '') {
				$response = $default;
			}   
		}   

		return $response;
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

	public function write($input, $color = null, $add_newline = true) {
		$output = $input;

		// I prefer "if" over "switch".
		if($color == 'green') {
			$output = "\033[32m" . $output;
		} elseif($color == 'red') {
			$output = "\033[31m" . $output;
		}   

		if($color) {
			$output .= "\033[0m";
		}   

		if($add_newline) {
			$output .= PHP_EOL;
		}   

		echo $output;
	}   
}
