<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;
use OntoPress\Library\Router;

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
     * Plugin Router.
     *
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
     *
     * @param Container $container [description]
     */
    private function __construct(Container $container)
    {
        $this->container = $container;
        $this->router = $container->get('ontopress.router');
        $container->get('symfony.session')->start();

        add_action('admin_notices', array($this, 'adminNotices'));
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadResources'));
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
    }

    /**
     * Get flash messages from session and print them.
     */
    public function adminNotices()
    {
        $sessionFlash = $this->container->get('symfony.session')->getFlashBag();
        $tags = array('success', 'info', 'warning', 'error');

        foreach ($tags as $tag) {
            foreach ($sessionFlash->get($tag) as $message) {
                echo $this->container->get('symfony.twig')->render(
                    'snippets/notice.html.twig',
                    array(
                        'tag' => $tag,
                        'message' => $message,
                    )
                );
            }
        }
    }

    /**
     * Use Wordpress functions to manipulate admin menu.
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
        wp_enqueue_script('ontopress_script', $this->container->getParameter('ontopress.plugin_url').'/Resources/assets/js/main.js');
    }
}
