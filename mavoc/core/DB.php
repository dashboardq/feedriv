<?php

namespace mavoc\core;

use PDO;

class DB {
    public $dsn;
    public $options;
    public $pdo;

    public function __construct() {
    }   

    public function init() {
        // Based on / Inspired by: https://phpdelusions.net/pdo
        // If you are new to databases, you should read this: https://phpdelusions.net/sql_injection
        $host = ao()->env('DB_HOST');
        $db = ao()->env('DB_NAME');
        $user = ao()->env('DB_USER');
        $pass = ao()->env('DB_PASS');
        $charset = ao()->env('DB_CHARSET');

        // database source name
        $this->dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $this->options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($this->dsn, $user, $pass, $this->options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        ao()->hook('ao_db_loaded');
    }

    public function array($input) {
        $output = [];
        foreach($input as $row) {
            foreach($row as $key => $value) {
                $output[] = $value;
            }
        }

        return $output;
    }

    public function call($args, $type = PDO::FETCH_ASSOC) {
        $args = ao()->hook('ao_db_call_args', $args);
        $type = ao()->hook('ao_db_call_type', $type);
        $args_count = count($args);
        $output = [];

        if($args_count > 0) {
            $prepared = $this->pdo->prepare($args[0]);
            if($args_count >= 2 && is_array($args[1])) {
                $result = $prepared->execute($args[1]);
            } else {
                $result = $prepared->execute(array_slice($args, 1));
            }

            if($result === false) {
                return false;
            }

            $output = $prepared->fetchAll($type);

            $output = ao()->hook('ao_db_call_output', $output);
            return $output;
        } else {
            $output = ao()->hook('ao_db_call_output', false);
            return $output;
        }   
    } 

    // get('field_name', $sql, $values)
    public function get() {
        $args = func_get_args();
        $field = $args[0];
        $results = DB::call(array_slice($args, 1));

        if(count($results)) {
            $output = $results[0][$field];
        } else {
            $output = '';
        }

        return $output;
    }

    public function lastInsertId() {
        $output = $this->pdo->lastInsertId();
        return $output;
    }

    // list('field_name', $sql, $values)
    public function list() {
        $args = func_get_args();
        $field = $args[0];
        $results = DB::call(array_slice($args, 1));

        if(count($results)) {
            foreach($results as $item) {
                $output[] = $item[$field];
            }
        } else {
            $output = [];
        }

        return $output;
    }

    public function query() {
        $args = func_get_args();
        $args = ao()->hook('ao_db_query_args', $args);
        $output = DB::call($args);
        $output = ao()->hook('ao_db_query_output', $output);
        return $output;
    }
}
