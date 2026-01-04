<?php

namespace Core;

class App
{
    protected $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function run(): void
    {
        $url = $this->parseUrl();

        // CONTROLLER
        if (isset($url[0])) {
            if ($this->controllerExists($url[0])) {
                $this->controller = ucfirst($url[0]) . 'Controller';
                unset($url[0]);
            } else {
                $this->show404();
                return;
            }
        }

        $controllerClass = "App\\Controllers\\{$this->controller}";

        if (!class_exists($controllerClass)) {
            $this->show404();
            return;
        }

        $this->controller = new $controllerClass();

        // METHOD
        if (isset($url[1])) {
            // Convertir les tirets en camelCase (do-login → doLogin)
            $methodName = str_replace(' ', '', ucwords(str_replace('-', ' ', $url[1])));
            $methodName = lcfirst($methodName);

            if (method_exists($this->controller, $methodName)) {
                $this->method = $methodName;
                unset($url[1]);
            }
        }

        if (!method_exists($this->controller, $this->method)) {
            $this->show404();
            return;
        }

        // PARAMS
        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function show404(): void
    {
        http_response_code(404);
        include __DIR__ . '/../app/views/errors/404.php';
    }

    protected function controllerExists(string $name): bool
    {
        $class = "App\\Controllers\\" . ucfirst($name) . "Controller";
        return class_exists($class);
    }

    protected function parseUrl(): array
    {
        if (!isset($_GET['url'])) {
            return [];
        }

        return explode(
            '/',
            filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)
        );
    }
}
