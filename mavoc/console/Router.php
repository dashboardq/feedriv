<?php

namespace mavoc\console;

use mavoc\console\Route;

class Router {
    public function __construct() {
        // Add built in routes
        $dir = ao()->env('AO_MAVOC_CONSOLE_DIR') . DIRECTORY_SEPARATOR . 'routes';
        $dir = ao()->hook('ao_console_router_mavoc_dir', $dir);
        foreach(scandir($dir) as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $path = ao()->hook('ao_console_router_app_path', $path);
            if(is_file($path)) {
                require_once $path;
            }
        }


        // Add app routes
        $dir = ao()->env('AO_SETTINGS_DIR') . DIRECTORY_SEPARATOR . 'routes';
        $dir = ao()->hook('ao_console_router_app_dir', $dir);
        foreach(scandir($dir) as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            $path = ao()->hook('ao_console_router_app_path', $path);
            if(is_file($path)) {
                require_once $path;
            }
        }
    }

    public function init() {
    }

    // Route is the preset value that tells how to parse.
    // Path is the actual URL with real data.
    public function parseParams($route, $path) {
        $output = [];

        $route_parts = explode('/', $route);
        $path_parts = explode('/', $path);

        foreach($route_parts as $i => $segment) {
            if(strpos($segment, '{') !== false) {
                $key = trim($segment, '{}');
                $output[$key] = $path_parts[$i];
            }
        }

        return $output;
    }

    public function route($in, $out) {
        if(ao()->hook('ao_console_router_route_enabled', true)) {
            $in = ao()->hook('ao_console_router_route_in', $in, $out);

            $routes = Route::$commands;
            $routes = ao()->hook('ao_console_router_route_routes', $routes, [$in, $out]);

            foreach($routes as $route => $method) {
                if(!count($in->data)) {
                    continue;
                }

                // Make sure it matches whether or not the first slash was included in the route.
                $trimmed_route = trim($route);
                $route_count = count(explode(' ', $trimmed_route));
                $trimmed_base = '';
                foreach(array_slice($in->data, 1) as $i => $part) {
                    if($i < $route_count) {
                        $trimmed_base .= ' ' . $part;
                    } else {
                        break;
                    }
                }
                $trimmed_base = trim($trimmed_base);

                $match = $trimmed_route == $trimmed_base;
                $match = ao()->hook('ao_console_router_route_match', $match, [$in, $route]);

                // If it contains '{' then it is a dynamic route.
                if(!$match && strpos($route, '{') !== false) {
                    //echo preg_replace('|{[^}/]*}|', '[a-zA-Z0-9]*', $trimmed_route);die;
                    // This is a bit complicated. Turning the route into a regex then checking against the path.
                    $match = preg_match('|^' . preg_replace('|{[^}/]*}|', '[a-zA-Z0-9]*', $trimmed_route) . '$|', $trimmed_path);
                    if($match) {
                        $req->params = $this->parseParams($trimmed_route, $trimmed_base);
                    }
                }

                if($match) {
                    $in->params = array_slice($in->data, $route_count + 1);
                    $method = ao()->hook('ao_console_router_route_method', $method, [$in, $route]);

                    if(is_array($method) && count($method) == 2) {
                        $class_name = $method[0];
                        $class_name = ao()->hook('ao_console_router_route_class_name', $class_name);

                        if(strpos($class_name, '\\') === 0) {
                            $parts = explode('\\', $class_name);

                            $class = $class_name;
                            $class = ao()->hook('ao_console_router_route_class', $class);

                            $file_name = $parts[count($parts) - 1] .'.php';
                            $file_name = ao()->hook('ao_console_router_route_file_name', $file_name);
                        } else {
                            $parts = explode('\\', '\app\controllers\\');

                            $class = '\app\controllers\\' . $class_name;
                            $class = ao()->hook('ao_console_router_route_class', $class);

                            $file_name = $method[0] .'.php';
                            $file_name = ao()->hook('ao_console_router_route_file_name', $file_name);
                        }
                        $dir = implode('/', array_slice($parts, 1, -1));


                        $file = ao()->dir($dir) . DIRECTORY_SEPARATOR . $file_name;
                        $file = ao()->hook('ao_console_router_route_file', $file);

                        $method_name = $method[1];
                        $method_name = ao()->hook('ao_console_router_route_method_name', $method_name);

                        // Include controller file
                        if(is_file($file)) {
                            include_once $file;
                        }

                        // Instantiate the controller
                        if(ao()->hook('ao_console_router_route_controller_init', true)) {
                            $controller = new $class();
                            $controller = ao()->hook('ao_console_router_route_controller', $controller);
                        }

                        // Call the method on the controller
                        if(ao()->hook('ao_console_router_route_controller_call', true, $in, $out, $controller, $method_name)) {
                            $vars = call_user_func([$controller, $method_name], $in, $out);
                        }

                        // Make sure it doesn't keep looping once found.
                        if(ao()->hook('ao_console_router_route_break', true)) {
                            break;
                        }
                    } elseif($method) {
                        // String passed in for method.
                        // Break into parts
                        $parts = explode('/', trim($req->path, '/'));

                        if(count($parts)) {
                            $class_name = $method;
                            $class_name = ao()->hook('ao_console_router_route_class_name_controller', $class_name);

                            $class = '\app\controllers\\' . $class_name;
                            $class = ao()->hook('ao_console_router_route_class_controller', $class);

                            $file_name = $method . '.php';
                            $file_name = ao()->hook('ao_console_router_route_file_name_controller', $file_name);

                            $file = ao()->dir('app/controllers') . DIRECTORY_SEPARATOR . $file_name;
                            $file = ao()->hook('ao_console_router_route_file_controller', $file);

                            $file_view = ao()->dir('app/views') . DIRECTORY_SEPARATOR . $file_name;
                            $file_view = ao()->hook('ao_console_router_route_file_view', $file_view);

                            // Include controller file
                            $use_generic = false;
                            if(is_file($file)) {
                                include_once $file;
                            } else {
                                $use_generic = true;
                            }

                            if($req->method == 'GET') {
                                $method_name = methodify($parts[0]);
                            } else {
                                $method_name = methodify($parts[0]) . classify($req->method);
                            }
                            if($use_generic) {
                                $class = '\ao\core\GenericController';
                                $method_name = 'view';
                                if(is_file($file_view)) {
                                    $view = dashify($method);
                                    $view = ao()->hook('ao_console_router_route_method_view_controller', $view);
                                } else {
                                    $view = dashify($parts[0]);
                                    $view = ao()->hook('ao_console_router_route_method_view_controller', $view);
                                }
                            }
                            $method_name = ao()->hook('ao_console_router_route_method_name_controller', $method_name);

                            // Instantiate the controller
                            if(ao()->hook('ao_console_router_route_controller_init_controller', true)) {
                                $controller = new $class();
                                $controller = ao()->hook('ao_console_router_route_controller_controller', $controller);
                            }

                            // Call the method on the controller
                            if(ao()->hook('ao_console_router_route_controller_call_controller', true)) {
                                if($use_generic) {
                                    $vars = call_user_func([$controller, $method_name], $in, $out, $view);
                                } else {
                                    $vars = call_user_func([$controller, $method_name], $in, $out);
                                }
                            }

                        }
                        // Make sure it doesn't keep looping once found.
                        if(ao()->hook('ao_console_router_route_break', true)) {
                            break;
                        }
                    } else {
                        // No method passed in.
                        // First try to find the controller
                        $parts = explode('/', trim($req->path, '/'));

                        if(count($parts)) {
                            $class_name = classify($parts[0]) . 'Controller';
                            $class_name = ao()->hook('ao_console_router_route_class_name_missing', $class_name);

                            $class = '\app\controllers\\' . $class_name;
                            $class = ao()->hook('ao_console_router_route_class_missing', $class);

                            $file_name = classify($parts[0]) .'Controller.php';
                            $file_name = ao()->hook('ao_console_router_route_file_name_missing', $file_name);

                            $file = ao()->dir('app/controllers') . DIRECTORY_SEPARATOR . $file_name;
                            $file = ao()->hook('ao_console_router_route_file_missing', $file);

                            // Include controller file
                            $use_generic = false;
                            if(is_file($file)) {
                                include_once $file;
                            } else {
                                $use_generic = true;
                            }

                            if($req->method == 'GET') {
                                $method_name = methodify($parts[0]);

                                if($use_generic) {
                                    $class = '\ao\core\GenericController';
                                    $method_name = 'view';
                                    $view = dashify($parts[0]);
                                    $view = ao()->hook('ao_console_router_route_method_view_missing', $view);
                                }
                            }
                            $method_name = ao()->hook('ao_console_router_route_method_name_missing', $method_name);

                            // Instantiate the controller
                            if(ao()->hook('ao_console_router_route_controller_init_missing', true)) {
                                $controller = new $class();
                                $controller = ao()->hook('ao_console_router_route_controller_missing', $controller);
                            }

                            // Call the method on the controller
                            if(ao()->hook('ao_console_router_route_controller_call_missing', true)) {
                                if($use_generic) {
                                    $vars = call_user_func([$controller, $method_name], $req, $res, $view);
                                } else {
                                    $vars = call_user_func([$controller, $method_name], $req, $res);
                                }
                            }

                        }
                        // Make sure it doesn't keep looping once found.
                        if(ao()->hook('ao_console_router_route_break', true)) {
                            break;
                        }
                    }
                }
            }
        }
    }
}
