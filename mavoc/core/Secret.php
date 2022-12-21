<?php

namespace mavoc\core;


// Do not use this class unless you fully understand what is going on.
// It is recommended not to write your own cryptographic code. This code was written
// so that encryption could be used without using a third party library. It is possible
// if not likely that this class contains insecure code.
//
// If you are needing an encryption library, you may want to check out these links and use a library:
// Defuse: https://github.com/defuse/php-encryption
// Sodium: https://www.php.net/manual/en/book.sodium.php
// Sodium Example: https://github.com/dwgebler/php-encryption
class Secret {
    public $key_index = -1;

    public function __construct($key_index = -1) {
        $this->key_index = $key_index;
    }   

    public function init() {
    }

    public static function generateKey() {
        return sodium_bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
    }

    public static function generateNonce() {
        return random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
    }

    // To improve security probably need to unset the data below.
    public function decrypt($json) {
        $data = json_decode($json);

        $key = sodium_hex2bin(ao()->env('APP_KEYS')[$data->key_index]);
        $nonce = sodium_hex2bin($data->nonce);
        $ciphertext = sodium_hex2bin($data->ciphertext);

        $output = sodium_crypto_secretbox_open($ciphertext, $nonce, $key);

        if($output === false) {
            throw new \Exception('Item was not properly decrypted.');
        }

        return $output;
    }

    // To improve security probably need to unset the data below.
    public function encrypt($string) {
        if($this->key_index == -1) {
            throw new \Exception('There is not a valid key to encrypt the data.');
        }
        $key_index = $this->key_index;
        $keys = ao()->env('APP_KEYS');
        if(!isset($keys[$key_index])) {
            throw new \Exception('The specified key to encrypt the data is not available.');
        }

        $key = sodium_hex2bin($keys[$key_index]);
        $nonce = Secret::generateNonce();

        $ciphertext = sodium_bin2hex(sodium_crypto_secretbox($string, $nonce, $key));
        $nonce = sodium_bin2hex($nonce);

        $output = json_encode(compact('key_index', 'nonce', 'ciphertext'));
        return $output;
    }
}
