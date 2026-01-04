<?php

namespace Core;

class Controller
{
    /**
     * Load a model
     *
     * @param string $model Name of the model class (without namespace)
     * @return object
     */
    protected function model(string $model)
    {
        $class = "App\\Models\\$model";

        if (!class_exists($class)) {
            throw new \Exception("Model $model not found.");
        }

        return new $class();
    }

    /**
     * Load a view
     *
     * @param string $view Path to the view relative to app/views (e.g. 'home/index')
     * @param array $data Associative array of variables to extract into the view
     */
    protected function view(string $view, array $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../app/views/$view.php";

        if (!file_exists($viewFile)) {
            throw new \Exception("View $view not found at $viewFile");
        }

        require $viewFile;
    }
}
