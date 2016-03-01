<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;
use OntoPress\Libary\Router;

/**
 * WP Admin init hooks.
 */
class AdminInit
{
    /**
     * Singelton fallback.
     *
     * @var AdminInit
     */
    private static $initialize;

    /**
     * Dependency injection container.
     *
     * @var Container
     */
    private $container;

    /**
     * Plugin Router
     * @var Router
     */
    private $router;

    /**
     * Create Singleton and execute all hooks.
     *
     * @param Container $container Dependency injection container
     *
     * @return AdminInit Singleton
     */
    public static function init(Container $container)
    {
        return self::$initialize ? self::$initialize : new self($container);
    }

    /**
     * Add custom methods to Wordpress actions.
     * @param Container $container [description]
     */
    private function __construct(Container $container)
    {
        $this->container = $container;
        $this->router = new Router($this->container);

        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadResources'));
    }

    /**
     * Use Wordpress functions to manipulate admin menu
     */
    public function adminMenu()
    {
        $this->router->setRoutes();
    }

    /**
     * Load admin resources.
     */
    public function loadResources()
    {
        wp_enqueue_style('ontopress_style', $this->container->getParameter('ontopress.plugin_url').'/Resources/assets/css/style.css');
    }
}
