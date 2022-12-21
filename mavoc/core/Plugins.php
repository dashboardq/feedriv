<?php

namespace mavoc\core;

class Plugins {
    public $plugs = [];
    public $instances = [];

    public function __construct() {
        if(is_file(ao()->env('AO_BASE_DIR') . DIRECTORY_SEPARATOR . '.plug.php')) {
            $this->plugs = require ao()->env('AO_BASE_DIR') . DIRECTORY_SEPARATOR . '.plug.php';
        }
    }

    public function init() {
        // Load each of the plugins
        foreach(scandir(ao()->env('AO_PLUGIN_DIR')) as $file) {
            // Determine which classes are loaded.
            $before = get_declared_classes();

            $path = ao()->env('AO_PLUGIN_DIR') . DIRECTORY_SEPARATOR . $file;
            if(is_file($path)) {
                require_once $path;
            } elseif(is_dir($path) && is_file($path . DIRECTORY_SEPARATOR . $file . '.php')) {
                require_once $path . DIRECTORY_SEPARATOR . $file . '.php';
            } elseif(is_dir($path) && is_file($path . DIRECTORY_SEPARATOR . 'main.php')) {
                require_once $path . DIRECTORY_SEPARATOR . 'main.php';
            }

            $after = get_declared_classes();

            // Initialize the classes.
            $diff = array_diff($after, $before);
            foreach($diff as $class) {
                // Use an underscore version for cross language compatibility.
                // This will ensure the dot files stay similar.
                // replace all non-characters with underscore
                // strip out last duplicate class name
                // make lowercase
                $parts = explode('_', preg_replace('/[^0-9a-zA-Z]/', '_', $class));
                $key = strtolower(implode('_', array_slice($parts, 0, -1)));

                $class = ao()->hook('ao_plugin_class', $class);
                $class = ao()->hook('ao_plugin_class_' . $class, $class);

                // Check to see if key is enabled in plugins.
                if(isset($this->plugs[$key]) && $this->plugs[$key]) {
                    $instance = new $class();
                    $instance = ao()->hook('ao_plugin_instance', $instance);
                    $instance->init();
                    // Hook whether or not to activate the plugin
                    $this->instances[] = $instance;
                }
            }

        }

        $this->instances = ao()->hook('ao_plugin_instances', $this->instances);
    }

}
