<?php

namespace Core;

class App
{
    protected $controller = 'HomeController';
    protected string $method = 'index';
    protected array $params = [];

    public function __construct()
    {
        // Initialize error handling
        ErrorHandler::initialize();

        // Load configuration
        Config::load();

        // Log app start
        Logger::info('Application started', ['url' => $_SERVER['REQUEST_URI'] ?? 'CLI']);
    }

    public function run(): void
    {
        $url = $this->parseUrl();

        // CONTROLLER
        if (isset($url[0])) {
            if ($this->controllerExists($url[0])) {
                $this->controller = ucfirst($url[0]) . 'Controller';
                unset($url[0]);
            } else {
                Logger::warning('Controller not found: {controller}', ['controller' => $url[0]]);
                ErrorHandler::show404();
                return;
            }
        }

        $controllerClass = "App\\Controllers\\{$this->controller}";

        if (!class_exists($controllerClass)) {
            Logger::warning('Class not found: {class}', ['class' => $controllerClass]);
            ErrorHandler::show404();
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
            Logger::warning('Method not found: {class}::{method}', [
                'class' => $this->controller::class,
                'method' => $this->method
            ]);
            ErrorHandler::show404();
            return;
        }

        // PARAMS
        $this->params = $url ? array_values($url) : [];

        Logger::info('Route matched: {controller}::{method}', [
            'controller' => $this->controller,
            'method' => $this->method
        ]);

        call_user_func_array([$this->controller, $this->method], $this->params);
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
