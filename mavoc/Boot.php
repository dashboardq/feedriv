<?php

namespace mavoc;

use mavoc\Mavoc;

require_once 'Mavoc.php';

// Boot allows for loading config and checking for maintenance before loading the full app.
class Boot {
    public $envs = [];

    public function __construct() {
        // Load config variables.
        $this->envs = require '..' . DIRECTORY_SEPARATOR . '.env.php';
    }

    public function init() {
        global $ao;

        if(is_file('..' . DIRECTORY_SEPARATOR . '.boot_start.php')) {
            require '..' . DIRECTORY_SEPARATOR . '.boot_start.php';
        }

        // Display errors if environment is not production.
        if(!in_array($this->envs['APP_ENV'], ['prod', 'production'])) {
            ini_set('display_errors', 1);   
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL); 
        }

        // Check if maintenance needs to be loaded.
        $maintenance = $this->envs['AO_MAINTENANCE'];
        $exclude = $this->envs['AO_MAINTENANCE_EXCLUDE'];
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if($maintenance && !in_array($ip, $exclude)) {
            $app_name = $this->envs['APP_NAME'];
            $title = 'Maintenance';
            $started = $this->envs['AO_MAINTENANCE_STARTED'];
            $ending = $this->envs['AO_MAINTENANCE_ENDING'];
            $ending_relative = !preg_match('/^\d\d\d\d-\d\d-\d\d.*/', $ending);
            $view = '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'alt' . DIRECTORY_SEPARATOR . 'maintenance.php';
            if(is_file($view)) {
                include $view;
            } else {
                $htm = '';
                $htm .= '<h1>' . htmlspecialchars($title) . '</h1>';
                $htm .= '<p>';
                if($ending_relative) {
                    $htm .= 'The site is currently undergoing maintenance. ';
                    $htm .= 'It started at ' . htmlspecialchars($started) . ' ';
                    $htm .= 'and should last about ' . htmlspecialchars($ending) . '.';
                } else {
                    $htm .= 'The site is currently undergoing maintenance. ';
                    $htm .= 'It started at ' . htmlspecialchars($started) . ' ';
                    $htm .= 'and should end around ' . htmlspecialchars($ending) . '.';
                }
                $htm .= '</p>';
                echo $htm;
            }
            exit;
        }

        if(is_file('..' . DIRECTORY_SEPARATOR . '.boot_end.php')) {
            require '..' . DIRECTORY_SEPARATOR . '.boot_end.php';
        }

        $ao = new Mavoc($this->envs);
        $ao->init();
    }
}
