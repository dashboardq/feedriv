<?php

namespace mavoc\console;

class Route {
    public static $commands = [];

    public static function command($uri, $method = null) {
        if(ao()->hook('ao_route_command_enabled', true)) {
            $uri = ao()->hook('ao_router_command_uri', $uri);
            $method = ao()->hook('ao_router_command_method', $method);

            Route::$commands[$uri] = $method;
        }
    }
}
