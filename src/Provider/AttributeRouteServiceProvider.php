<?php

namespace App\Providers;

use Core\Repositories\Delete;
use Core\Repositories\Get;
use Core\Repositories\Group;
use Core\Repositories\Middleware;
use Core\Repositories\NamespaceAttr;
use Core\Repositories\Patch;
use Core\Repositories\Post;
use Core\Repositories\Prefix;
use Core\Repositories\Put;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class AttributeRouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerRoutes();
    }

    protected function registerRoutes()
    {
        $controllers = $this->scanControllers();

        foreach ($controllers as $controller) {
            $isApiRoute = $this->isApiController($controller);
            $middleware = $isApiRoute ? ['api'] : ['web'];

            Route::middleware($middleware)
                ->group(function () use ($controller) {
                    $this->registerControllerRoutes($controller);
                });
        }
    }

    protected function registerControllerRoutes(string $controller)
    {
        $reflectionClass = new ReflectionClass($controller);

        // Initialize route attributes
        $prefix = '';
        $middleware = [];
        $namespace = '';
        $group = null;

        // Scan class attributes
        foreach ($reflectionClass->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();
            if ($instance instanceof Prefix) {
                $prefix = $instance->prefix;
            } elseif ($instance instanceof Middleware) {
                $middleware = array_merge($middleware, $instance->middleware);
            } elseif ($instance instanceof NamespaceAttr) {
                $namespace = $instance->namespace;
            } elseif ($instance instanceof Group) {
                $group = $instance->group;
            }
        }

        // Set up route group
        $routeGroup = Route::prefix($prefix);

        if ($namespace) {
            $routeGroup->namespace($namespace);
        }

        if ($group) {
            $routeGroup->group($group);
        }

        // Register method routes
        foreach ($reflectionClass->getMethods() as $method) {
            $this->registerMethodRoutes($method, $controller, $routeGroup, $middleware);
        }
    }

    protected function registerMethodRoutes($method, string $controller, $routeGroup, array $middleware)
    {
        foreach ($method->getAttributes() as $attribute) {
            $instance = $attribute->newInstance();

            // Register routes based on the HTTP method attributes
            if ($instance instanceof Get) {
                $routeGroup->middleware($middleware)->get($instance->uri, [$controller, $method->name]);
            } elseif ($instance instanceof Post) {
                $routeGroup->middleware($middleware)->post($instance->uri, [$controller, $method->name]);
            } elseif ($instance instanceof Put) {
                $routeGroup->middleware($middleware)->put($instance->uri, [$controller, $method->name]);
            } elseif ($instance instanceof Patch) {
                $routeGroup->middleware($middleware)->patch($instance->uri, [$controller, $method->name]);
            } elseif ($instance instanceof Delete) {
                $routeGroup->middleware($middleware)->delete($instance->uri, [$controller, $method->name]);
            }
        }
    }

    protected function scanControllers(string $basePath = null): array
    {
        $basePath = $basePath ?: base_path();
        $controllers = [];

        $directoryIterator = new RecursiveDirectoryIterator($basePath);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        $regex = new RegexIterator($iterator, '/^.+\/Controllers\/.+\.php$/i', RegexIterator::GET_MATCH);

        foreach ($regex as $file) {
            $filePath = $file[0];
            $className = $this->getFullClassNameFromFile($filePath);
            if ($className) {
                $controllers[] = $className;
            }
        }

        return $controllers;
    }

    protected function getFullClassNameFromFile(string $filePath): ?string
    {
        $content = file_get_contents($filePath);
        $namespace = null;
        $class = null;

        if (preg_match('/^namespace\s+(.+?);/m', $content, $matches)) {
            $namespace = $matches[1];
        }

        if (preg_match('/^class\s+(\w+)/m', $content, $matches)) {
            $class = $matches[1];
        }

        return $namespace && $class ? "$namespace\\$class" : null;
    }

    protected function isApiController(string $controller): bool
    {
        return strpos($controller, 'App\Http\Controllers\Api') === 0;
    }
}
