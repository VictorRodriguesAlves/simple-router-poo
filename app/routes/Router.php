<?php
declare(strict_types=1);
namespace app\routes;

use app\helpers\Request;
use app\helpers\Uri;
use Exception;
class Router
{

    const CONTROLLERS_NAMESPACE = "app\\controllers";

    public static function load(string $controller, string $method): void
    {
        try {
            //verifica se o controller existe
            $controllerNamespace = self::CONTROLLERS_NAMESPACE . '\\' .$controller;
            if(!class_exists($controllerNamespace)){
                throw new Exception("O Controller {$controller} não existe.");
            }

            $controllerInstance = new $controllerNamespace;

            if(!method_exists($controllerNamespace, $method)){
                throw new Exception("O metodo {$method} não existe no Controller {$controller}.");
            }

            $controllerInstance->$method();

        }catch (\Throwable $th){
            echo $th->getMessage();
        }

    }

    public static function routes(): array
    {
        return [
            'get' => [
                '/' => fn() => self::load('HomeController', 'index'),
                '/contact' => fn() => self::load('ContactController', 'index'),
                '/product' => fn() => self::load('ProductController', 'index'),
            ],
            'post' => [
                '/contact' => fn() => self::load('ContactController', 'store'),
            ],

        ];
    }

    public static function execute(): void
    {
        try {
            $routes = self::routes();
            $request = Request::get();
            $uri = Uri::getUri('path');

            if(!isset($routes[$request])){
                throw new Exception("O metodo não é suportado.");
            }

            if(!array_key_exists($uri, $routes[$request])){
                throw new Exception("A rota não existe.");
            }

            $router = $routes[$request][$uri];
            $router();

        }catch (\Throwable $th){
            echo $th->getMessage();
        }

    }

}