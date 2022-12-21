<?php

namespace mavoc\console\controllers;

class GenController {

    public function keys($in, $out) {
        $dir = ao()->env('AO_BASE_DIR');
        if(!is_dir($dir)) {
            out('Error: ' . 'There was a problem with the base directory. Please make sure it exists with the proper permissions.', 'red');
            exit(1);
        }

        $file = '.keys.php';

        // Check if the file already exists
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if(is_file($path)) {
            out('Error: ' . 'The .keys.php file already appears to exist.', 'red');
            exit(1);
        }

        $key_names = ['CONNECTIONS_1'];
        $key_names = ao()->hook('ao_gen_key_names', $key_names);

        $content = <<<PHP
<?php

// Changing these values, will cause all of the encrypted data in the database to become unusable.

return [

PHP;
        foreach($key_names as $name) {
            $key = sodium_bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
            $content .= "    '$name' => '$key',";
            $content .= "\n";
        }


$key = sodium_bin2hex(random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
$content .= <<<PHP
];

PHP;

        file_put_contents($path, $content);


        out('The .keys.php file has been created: ' . $file, 'green');
    }

}

