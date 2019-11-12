<?php

class Request {

}

class Response {
    public $type = "application/json";
    public $data;
    public $code = 200;

    public function json($data) {
        $this->data = $data;
        $this->type = "application/json";
    }

    public function html($data) {
        $this->data = $data;
        $this->type = "text/html";
    }
}

class Route {
    public $method;
    public $path;
    public $path_params;
    public $actions;
    public $request;
    public $response;

    function __construct($method, $path, $actions) {
        $this->method = $method;
        $this->path = $path;
        $this->path_params = Router::parse_params($this->path);
        $this->actions = $actions;
        $this->request = new Request();
        $this->response = new Response();
    }

    public function exec() {

        $this->request->params = $this->path_params;

        foreach ($this->actions as $action) {
            if (!call_user_func_array($action, array(&$this->request, &$this->response))) {
                break;
            }
        }

        http_response_code($this->response->code);

        if ( $this->response->code !== 200 ) {
            return;
        }

        header("Content-Type: " . $this->response->type);

        if ($this->response->type === "application/json") {
            if (isset($this->response->data)) {
                echo  json_encode($this->response->data);
            }
        } else {
            echo $this->response->data;
        }

    }

    public function is_valid() {
        return $this->is_valid_path() && $this->is_valid_method();
    }

    private function is_valid_method() {
        return $_SERVER['REQUEST_METHOD'] === $this->method;
    }

    private function is_valid_path() {
        $url_params = Router::parse_params($_SERVER["QUERY_STRING"]);
        $valid = 0;
        $url_keys = array_keys($url_params);
        $request_keys = array_keys($this->path_params);

        if (count($url_keys) !== count($request_keys)) {
            return false;
        }

        for ($i=0; $i<count($request_keys); $i++) {
            if ($url_keys[$i] === $request_keys[$i]) {
                if ($this->path_params[$url_keys[$i]] === '?' || $url_params[$url_keys[$i]] === $this->path_params[$url_keys[$i]]) {
                    $valid += 1;
                }
            }
        }
    
        return $valid === count($request_keys) && $valid === count($url_keys);
    }

}

class Router {

    static $routes = array();

    static function push($method, $path, $actions) {
        array_push(Router::$routes, new Route($method, $path, $actions));
    }

    static function exec() {
        foreach(Router::$routes as $route) {
            if ( $route->is_valid() ) {
                $route->exec();
                return;
            }
        }

        http_response_code(404);

    }

    static function parse_params($url_params) {
        $query = explode('&', $url_params);
        $params = array();
        for ($i=0; $i<count($query); $i++) {
            $param = explode('=', $query[$i]);
            $params[$param[0]] = $param[1];
        }
        return $params;
    }

}