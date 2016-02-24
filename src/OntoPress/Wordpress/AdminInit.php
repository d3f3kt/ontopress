<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;
use OntoPress\Libary\Router;

class AdminInit
{
    private static $initialize;
    private $container;

    public static function init(Container $container)
    {
        return self::$initialize ? self::$initialize : new self($container);
    }

    private function __construct(Container $container)
    {
        $this->container = $container;
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadResources'));
    }

    public function adminMenu()
    {
        $router = new Router($this->container);
        $router->setRoutes();
    }

    public function loadResources()
    {
        //TODO: load custom css/js
    }
}
