<?php

namespace mavoc\core;

class Validate {
    public $rules;
    public $fields;

    // If request is empty, throw error if check_referrer setting not set to false
    // If response is empty, don't auto respond on error
    public function __construct($input, $list = [], $req = null, $res = null, $redirect = null) {
        $this->rules = new Validators();

        $this->fields = [];

        $pass = true;   
        $messages = [];
        $reserved_names = ['_settings', '_messages', '_fields'];

        $_settings = [];
        $_settings['check_referrer'] = true;
        if(isset($list['_settings']) && is_array($list['_settings'])) {
            $_settings = array_merge($_settings, $list['_settings']);
        }

        $_messages = [];
        if(isset($list['_messages']) && is_array($list['_messages'])) {
            $_messages = array_merge($_messages, $list['_messages']);
        }

        // Probably need to rename this to _names and use this to modify the names of the fields output to users.
        $_fields = [];
        if(isset($list['_fields']) && is_array($list['_fields'])) {
            $_fields = array_merge($_fields, $list['_fields']);
        }

        // Instead of using nonces, checking the referrer.
        if($_settings['check_referrer'] && $req && $req->referrer != ao()->env('APP_HOST')) {
            $pass = false;
            $req->res->error('The submission appears to have come from an improper form or your browser is blocking the referrer information. Please try submitting the form again. If this issue persists, please contact support.', $redirect);
        }

        // Remove any of the reserved fields used above.
        foreach($reserved_names as $name) {
            unset($list[$name]);
        }

        $pass = true;
        $messages = [];
        foreach($list as $item => $checks) {
            foreach($checks as $rule) {
                if(is_array($rule)) {
                    $keys = array_keys($rule);
                    $key = $keys[0];
                }

                $methods = get_class_methods($this->rules);

                // TODO: This is broken, need to fix. (sometimes you come back to a note you wrote previously
                // and realize you should have added more details - like this note - I'm not sure what needs
                // to be fixed) (another follow up, I think the problem is that is_callable will always return
                // true when checking against a class with __call() set up).
                // (another follow up, you can see this by adding a validation rule that doesn't exist - 
                // like 'id' => ['doesnotexist'])
                if(is_callable([$this->rules, $rule])) {
                    $result = call_user_func([$this->rules, $rule], $input, $item);
                    if(!$result) {
                        $pass = false;
                        if(!isset($messages[$item])) {
                            $messages[$item] = [];
                        }
                        if(is_callable([$this->rules, $rule . 'Message'])) {
                            $messages[$item][] = call_user_func([$this->rules, $rule . 'Message'], $input, wordify($item));
                        } else {
                            $messages[$item][] = $this->rules->message($rule, $input, wordify($item));
                        }
                    } else {
                        if(isset($input[$item])) {
                            $this->fields[$item] = $input[$item];
                        } else {
                            $this->fields[$item] = '';
                        }
                    }
                } elseif(is_array($rule) && in_array($key, $methods)) {
                    $args = $rule[$key];
                    if(!is_array($args)) {
                        $args = [$args];
                    }
                    $args = array_merge([$input, $item], $args);
                    $result = call_user_func_array([$this->rules, $key], $args);
                    if(!$result) {
                        $pass = false;
                        if(!isset($messages[$item])) {
                            $messages[$item] = [];
                        }
                        if(in_array($key . 'Message', $methods)) {
                            $args[1] = wordify($args[1]);
                            $messages[$item][] = call_user_func_array([$this->rules, $key . 'Message'], $args);
                        } else {
                            $messages[$item][] = $this->rules->message($rule, $input, wordify($item));
                        }
                    } else {
                        $this->fields[$item] = $input[$item];
                    }
                } else {
                    if(!isset($messages[$item])) {
                        $messages[$item] = [];
                    }
                    $messages[$item][] = 'There was a problem with the form validation.';
                    $pass = false;
                }
            }
        }

        if(!$pass) {
            $res->error($messages, $redirect);
        }
    }
}

