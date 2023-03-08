<?php

if(!function_exists('ao')) {
    function ao() {
        global $ao;
        return $ao;
    }
}

if(!function_exists('classify')) {
    function classify($input) {
        $words = preg_replace('/[\s,-_]+/', ' ', strtolower($input));
        $words = ucwords($words);
        $output = str_replace(' ', '', $words);
        return $output;
    }
}

if(!function_exists('dangerous')) {
    function dangerous($input) {
        echo $input;
    }
}

if(!function_exists('dashify')) {
    function dashify($input) {
        // Add a space before uppercase letters (make sure the first letter is not uppercase).
        $words = preg_replace('/(?=[A-Z])/', ' $0', lcfirst($input));
        $words = preg_replace('/[\s,-_]+/', ' ', strtolower($words));
        $parts = explode(' ', $words);
        if(count($parts)) {
            $parts[0] = strtolower($parts[0]);
        }
        $output = implode('-', $parts);
        return $output;
    }
}

if(!function_exists('dd')) {   
    function dd($input) {      
        echo '<pre>'; 
        print_r($input);       
        echo '</pre>';         
        die;
    }
}

// From: https://stackoverflow.com/questions/1416697/converting-timestamp-to-time-ago-in-php-e-g-1-day-ago-2-days-ago
// https://stackoverflow.com/a/18602474
// Slightly modified to accept DateTime objects.
if(!function_exists('elapsed')) {
    /*
    function elapsed($datetime, $full = false) {
        $now = new \DateTime;
        if(is_a($datetime, 'DateTime')) {
            $ago = $datetime;
        } else {
            $ago = new \DateTime($datetime);
        }
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        //$string = array(
            //'y' => 'year',
            //'m' => 'month',
            //'w' => 'week',
            //'d' => 'day',
            //'h' => 'hour',
            //'i' => 'minute',
            //'s' => 'second',
        //);
        //foreach ($string as $k => &$v) {
            //if ($diff->$k) {
                //$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            //} else {
                //unset($string[$k]);
            //}
        //}
        $string = array(
            'y' => 'y',
            'm' => 'm',
            'w' => 'w',
            'd' => 'd',
            'h' => 'h',
            'i' => 'm',
            's' => 's',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . '' . $v;
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
     */
    function elapsed($datetime, $full = false) {
        $now = new \DateTime;
        if(is_a($datetime, 'DateTime')) {
            $ago = $datetime;
        } else {
            $ago = new \DateTime($datetime);
        }
        $diff = $now->diff($ago);

        $output = '';
        if($diff->y) {
            $output = $diff->y . 'y ago';
        } elseif($diff->m) {
            $output = $diff->m . 'mo ago';
        } elseif($diff->d) {
            if($diff->d >= 7) {
                $output = floor($diff->d/7) . 'w ago';
            } else {
                $output = $diff->d . 'd ago';
            }
        } elseif($diff->h) {
            $output = $diff->h . 'h ago';
        } elseif($diff->i) {
            $output = $diff->i . 'm ago';
        } elseif($diff->s) {
            $output = $diff->s . 's ago';
        } else {
            $output = 'just now';
        }

        return $output;
    }
}

if(!function_exists('_esc')) {
    function _esc($value, $double_encode = true) {   
        //return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8', $double_encode);
        return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8', $double_encode);
    }   
} 

if(!function_exists('esc')) {
    function esc($value, $double_encode = true) {   
        echo _esc($value, $double_encode);
    }   
} 

if(!function_exists('methodify')) {
    function methodify($input) {
        $words = preg_replace('/[\s,-_]+/', ' ', strtolower($input));
        $words = ucwords($words);
        $parts = explode(' ', $words);
        if(count($parts)) {
            $parts[0] = strtolower($parts[0]);
        }
        $output = implode('', $parts);
        return $output;
    }
}

if(!function_exists('now')) {
    function now() {
        $dt = new \DateTime();
        return $dt->format('Y-m-d H:i:s');
    }
}

if(!function_exists('num')) {
    // Works like number_format() but allows you to pass in strings that already have a comma.
    function num($num, $decimals = 0, $decimal_separator = ".", $thousands_separator = ",") {
        // Make sure the comma is removed before running through number_format
        $output = str_replace(',', '', $num);
        $output = number_format($output, $decimals, $decimal_separator, $thousands_separator);
        return $output;
    }   
}

if(!function_exists('_out')) {
    function _out($input, $color = null) {   
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

        $output .= "\n";

        return $output;
    }   
} 

if(!function_exists('out')) {
    function out($input, $color = null) {   
        echo _out($input, $color);
    }   
} 

if(!function_exists('pluralize')) {
    function pluralize($count = 0, $singular) {   
        // Very hacky approach. Eventually need to switch to something like this:
        // http://kuwamoto.org/2007/12/17/improved-pluralizing-in-php-actionscript-and-ror/
        if($count != 1) {
            return $count . ' ' . $singular . 's';
        } else {
            return $count . ' ' . $singular;
        }
    }   
} 

if(!function_exists('underscorify')) {
    function underscorify($input) {
        $words = preg_replace('/[\s,_-]+/', ' ', strtolower($input));
        $parts = explode(' ', $words);
        if(count($parts)) {
            $parts[0] = strtolower($parts[0]);
        }
        $output = implode('_', $parts);
        return $output;
    }
}

if(!function_exists('upperfy')) {
    function upperfy($input) {
        $output = preg_replace('/[\s,_-]+/', '_', strtoupper($input));
        return $output;
    }   
}

if(!function_exists('_uri')) {
    function _uri($input) {
        $output = '';
        $output .= ao()->env('APP_SITE');
        $output .= '/';
        $output .= trim($input, '/');
        return $output;
    }
}
if(!function_exists('uri')) {
    function uri($input) {
        echo _uri($input);
    }
}

if(!function_exists('_url')) {
    function _url($input) {
        $output = '';
        $output .= ao()->env('APP_SITE');
        $output .= '/';
        $output .= trim($input, '/');

        $output = _esc($output);
        return $output;
    }
}
if(!function_exists('url')) {
    function url($input) {
        echo _url($input);
    }
}

if(!function_exists('wordify')) {
    function wordify($input) {
        $words = preg_replace('/[\s,-_]+/', ' ', strtolower($input));

        // Uppercase any abbreviations
        // Acronyms won't have any vowels (some may but this is just a rough working example for now) 
        $parts = explode(' ', $words);
        foreach($parts as $i => $part) {
            // If the word does not have a vowel or "y", it is probably an acronym.
            if(!preg_match('/[AEIOUYaeiouy]+/', $part)) {
                $parts[$i] = strtoupper($part);
            }
        }
        $words = implode(' ', $parts);

        $words = ucwords($words);
        $output = $words;
        $output = ao()->hook('helper_wordify_output', $words);
        return $output;
    }
}
