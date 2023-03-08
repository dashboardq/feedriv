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
        $reserved_names = ['_settings', '_messages', '_titles'];

        $_settings = [];
        $_settings['check_referrer'] = true;
        // Don't show multiple erorrs per field. Options: 'none' || 'halt' || 'field'
        $_settings['stop'] = 'field';
        if(isset($list['_settings']) && is_array($list['_settings'])) {
            $_settings = array_merge($_settings, $list['_settings']);
        }

        $_messages = [];
        if(isset($list['_messages']) && is_array($list['_messages'])) {
            $_messages = array_merge($_messages, $list['_messages']);
        }

        // _rules are messages for specific validations. 
		// Meaning this would change the required message:
        // $val = $req->val('data', [
		// '_rules' => ['required' => 'The {title} field MUST BE REQUIRED!'],
		// ]);
        $_rules = [];
        if(isset($list['_rules']) && is_array($list['_rules'])) {
            $_rules = array_merge($_rules, $list['_rules']);
        }

        // Probably need to rename this to _names and use this to modify the names of the fields output to users.
        $_titles = [];
        if(isset($list['_titles']) && is_array($list['_titles'])) {
            $_titles = array_merge($_titles, $list['_titles']);
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
        // The $list is the entire rules passed in, $item is the field name like 'name', 
        // the $checks are the array of actual rules
        foreach($list as $item => $checks) {
            if($_settings['stop'] == 'halt' && !$pass) {
                break;
            }

            $field_key = $item;
            $field_pass = true;
            if(isset($_titles[$field_key])) {
                $field_title = $_titles[$field_key];
            } else {
                $field_title = wordify($item);
            }

            // The $checks are the array of actual rules, $rule is each individual rule.
            foreach($checks as $rule) {
                if($_settings['stop'] == 'field' && !$field_pass) {
                    break;
                }
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
                    // The $input is the entire data array.
                    $result = call_user_func([$this->rules, $rule], $input, $field_key);
                    if(!$result) {
                        $pass = false;
                        $field_pass = false;
                        if(!isset($messages[$field_key])) {
                            $messages[$field_key] = [];
                        }

                        if(isset($_rules[$rule]) || isset($_messages[$field_key])) {
                            if(isset($_messages[$field_key][$rule])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_messages[$field_key][$rule]);
                            } elseif(isset($_messages[$field_key]) && is_string($_messages[$field_key])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_messages[$field_key]);
                            } elseif(isset($_rules[$rule])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_rules[$rule]);
                            }
                        } elseif(is_callable([$this->rules, $rule . 'Message'])) {
                            // The $input is the entire data array.
                            $messages[$field_key][] = call_user_func([$this->rules, $rule . 'Message'], $input, $field_title);
                        } else {
                            // The $input is the entire data array.
                            $messages[$field_key][] = $this->rules->message($rule, $input, $field_title);
                        }
                    } else {
                        // The $input is the entire data array.
                        if(isset($input[$field_key])) {
                            $this->fields[$field_key] = $input[$field_key];
                        } else {
                            $this->fields[$field_key] = '';
                        }
                    }
                } elseif(is_array($rule) && in_array($key, $methods)) {
                    $args = $rule[$key];
                    if(!is_array($args)) {
                        $args = [$args];
                    }
                    // The $input is the entire data array.
                    $args = array_merge([$input, $field_key], $args);
                    $result = call_user_func_array([$this->rules, $key], $args);
                    if(!$result) {
                        $pass = false;
                        $field_pass = false;
                        if(!isset($messages[$field_key])) {
                            $messages[$field_key] = [];
                        }

                        if(isset($_rules[$rule]) || isset($_messages[$field_key])) {
                            if(isset($_messages[$field_key][$rule])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_messages[$field_key][$rule]);
                            } elseif(isset($_messages[$field_key]) && is_string($_messages[$field_key])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_messages[$field_key]);
                            } elseif(isset($_rules[$rule])) {
                                $messages[$field_key][] = str_replace('{title}', $field_title , $_rules[$rule]);
                            }
                        } elseif(in_array($key . 'Message', $methods)) {
                            $args[1] = $field_title;
                            $messages[$field_key][] = call_user_func_array([$this->rules, $key . 'Message'], $args);
                        } else {
                            // The $input is the entire data array.
                            $messages[$field_key][] = $this->rules->message($rule, $input, $field_title);
                        }
                    } else {
                        // The $input is the entire data array.
                        $this->fields[$field_key] = $input[$field_key];
                    }
                } else {
                    if(!isset($messages[$field_key])) {
                        $messages[$field_key] = [];
                    }
                    $messages[$field_key][] = 'There was a problem with the form validation.';
                    $pass = false;
                    $field_pass = false;
                }
            }
        }

        if(!$pass) {
            $res->error($messages, $redirect);
        }
    }
}

