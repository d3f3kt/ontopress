<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;

/**
 * Main Wordpress Plugin Wrapper which catch the Wordpress default actions/hooks.
 */
class PluginWrapper
{
    /**
     * Dependency Injection Container.
     *
     * @var Container
     */
    private $container;

    /**
     * Initialize Wordpress Plugin.
     *
     * @param Container $container Dependency Injection Container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * This method is called when the plugin gets installed.
     */
    public function install()
    {
        //TODO: Install hooks e.g. mysql table creation
    }

    /**
     * This method is called when the plugin gets uninstalled.
     */
    public function uninstall()
    {
        //TODO: Uninstall hooks e.g. mysql table drop
    }

    /**
     * Proxy function of the WP init action, which is called in frontend and
     * backend.
     */
    public function init()
    {
        FrontendInit::init($this->container);
    }

    /**
     * Proxy function of the WP init aciton, which is only called in backend.
     */
    public function adminInit()
    {
        AdminInit::init($this->container);
    }
}
