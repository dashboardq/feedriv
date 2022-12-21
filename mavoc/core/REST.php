<?php

namespace mavoc\core;

// This is a class to make it easier to perform curl calls. It really doesn't have anything to do with REST
// other than that it is often used to interact with APIs that are considered "REST" APIs in the modern
// interpretation of "REST" APIs. 
//
// If you are not sure what this is talking about, this essay is probably a good starting point:
// https://htmx.org/essays/how-did-rest-come-to-mean-the-opposite-of-rest/
class REST {
    public $auth = ''; 
    public $internal;
    public $headers = [];
    public $json = true;
    public $res_headers = [];
    public $api_key = '';

    public function __construct($headers = [], $auth = '', $json = true) {
        // Not set up yet - will be used to simulate app calls without touching a network.
        $internal = new InternalREST();

        // If a string is passed in for headers, then it is part of an authorization header.
        if(is_string($headers)) {
            $this->api_key = $headers;
            $headers = ['Authorization: Bearer ' . $headers];
        }

        $this->headers = $headers;
        if($json) {
            $this->headers[] = 'Accept: application/json';
        }

        if($auth) {
            $this->auth = $auth;
        } 
    }   

    public function init() {
    }

    public function get($url, $headers = [], $returns = ['object']) {
        $this->res_headers = [];
        $final_headers = array_merge($this->headers, $headers);

        // If it is a string, turn into an array.
        //   If comma, delimited, break apart.
        // Possible values: raw; object; array; headers; headers,object; headers,array
        if(is_string($returns)) {
            $parts = explode(',', $returns);
            $returns = $parts;
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $final_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($this->auth) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->auth);
        } 

        if(in_array('headers', $returns)) {
            curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'parseHeader']);
        }

        $response = curl_exec($ch);
        /*
        if($response === false) {        
            echo 'Error in cURL : ' . curl_error($ch);
        } 
        echo 'Request Headers:';
        echo '<br>';
        echo '<pre>'; print_r($final_headers); echo '</pre>';
        echo '<br>';
        echo 'Response Headers:';
        echo '<br>';
        echo '<pre>'; print_r($this->res_headers); echo '</pre>';
        echo '<br>';
        echo 'Response:';
        echo '<br>';
        echo '<pre>'; print_r($response); echo '</pre>';
        // */
        curl_close($ch);

        // TODO: Probably need to add some error checking for json_decode.
        if(in_array('object', $returns)) {
            $body = json_decode($response);
            if(in_array('headers', $returns)) {
                return [$this->res_headers, $body];
            } else {
                return $body;
            }
        } elseif(in_array('array', $returns)) {
            $body = json_decode($response, true);
            if(in_array('headers', $returns)) {
                return [$this->res_headers, $body];
            } else {
                return $body;
            }
        } else {
            return $response;
        }

    }

    public function head($url, $headers = [], $as_array = false) {
        $final_headers = array_merge($this->headers, $headers);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $final_headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        //echo '<pre>'; esc($response);die;

        /*
        $output = [];
        $headers = explode("\r\n", $response);
        foreach($headers as $header) {
            $parts = explode(':', $header, 2);
            if(count($parts) >= 2) {
                $output[strtolower(trim($parts[0]))] = trim($parts[1]);
            } elseif(count($parts) == 1 && $parts[0]) {
                $output[strtolower(trim($parts[0]))] = '';
            }
        }
         */
        $output = $this->parseHeaders($response);

        //echo '<pre>'; esc(print_r($output));die;
        
        if($this->json) {
            $json = json_decode(json_encode($output), $as_array);
            return $json;
        } else {
            return $output;
        }

    }

    public function parseHeader($curl, $header) {
        $length = strlen($header);

        $parts = explode(':', $header, 2);
        if(count($parts) >= 2) {
            $this->res_headers[strtolower(trim($parts[0]))] = trim($parts[1]);
        } elseif(count($parts) == 1 && $parts[0]) {
            $this->res_headers[strtolower(trim($parts[0]))] = '';
        }

        return $length;
    }

    public function parseHeaders($raw) {
        $output = [];
        $headers = explode("\r\n", $raw);
        foreach($headers as $header) {
            $parts = explode(':', $header, 2);
            if(count($parts) >= 2) {
                $output[strtolower(trim($parts[0]))] = trim($parts[1]);
            } elseif(count($parts) == 1 && $parts[0]) {
                $output[strtolower(trim($parts[0]))] = '';
            }
        }

        return $output;
    }

    public function post($url, $data = [], $headers = [], $as_array = false) {
        $final_headers = array_merge($this->headers, $headers);

        //echo '<pre>'; print_r($headers); echo '</pre>';
        //echo '<pre>'; print_r($final_headers); echo '</pre>'; die;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $final_headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($this->auth) {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->auth);
        } 

        $response = curl_exec($ch);
        curl_close($ch);

        if($this->json) {
            $json = json_decode($response, $as_array);
            return $json;
        } else {
            return $response;
        }
    }
}
