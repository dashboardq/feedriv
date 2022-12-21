<?php

namespace mavoc\core;

class Route {
    public static $gets = [];
    public static $posts = [];
    public static $puts = [];
    public static $patches = [];
    public static $deletes = [];

    // TODO: Don't really like the way I have restrictions set up but it will do for now.
    public static $restrictions = [];

    public static function get($uri, $method = null, $type = '') {
        if(ao()->hook('ao_route_get_enabled', true)) {
            $uri = ao()->hook('ao_router_get_uri', $uri);
            $method = ao()->hook('ao_router_get_method', $method);

            Route::$gets[$uri] = $method;

            if($type) {
                if(!isset(Route::$restrictions['GET'])) {
                    Route::$restrictions['GET'] = [];
                }
                if(!isset(Route::$restrictions['GET'][$type])) {
                    Route::$restrictions['GET'][$type] = [];
                }
                Route::$restrictions['GET'][$type][$uri] = true;
            }
        }
    }

    public static function post($uri, $method = null, $type = '') {
        if(ao()->hook('ao_route_post_enabled', true)) {
            $uri = ao()->hook('ao_router_post_uri', $uri);
            $method = ao()->hook('ao_router_post_method', $method);

            Route::$posts[$uri] = $method;

            if($type) {
                if(!isset(Route::$restrictions['POST'])) {
                    Route::$restrictions['POST'] = [];
                }
                if(!isset(Route::$restrictions['POST'][$type])) {
                    Route::$restrictions['POST'][$type] = [];
                }
                Route::$restrictions['POST'][$type][$uri] = true;
            }
        }
    }

    public static function crudlae($uri, $model = null, $controller = null) {
        if(ao()->hook('ao_route_crudlae_enabled', true)) {
            if(ao()->hook('ao_route_resource_enabled', true)) {
                $uri = ao()->hook('ao_router_crudlae_uri', $uri);
                $model = ao()->hook('ao_router_crudlae_model', $model);
                $controller = ao()->hook('ao_router_crudlae_controller', $controller);

                $uri = ao()->hook('ao_router_resource_uri', $uri);
                $model = ao()->hook('ao_router_resource_model', $model);
                $controller = ao()->hook('ao_router_resource_controller', $controller);

                Route::$gets[$uri . '/' . 'add'] = 'found';
                Route::$posts[$uri . '/' . 'add'] = 'found';
                Route::$gets[$uri . '/' . 'edit' . '/{id}'] = 'found';
                Route::$patches[$uri . '/' . 'edit' . '/{id}'] = 'found';

                /*
                Route::$list[] = [
                ];
                 */
            }
        }
    }
}
