<?php

namespace OntoPress\Library;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use OntoPress\Library\Exception\InvalidControllerCallException;
use OntoPress\Library\Exception\NoActionException;
use OntoPress\Library\Exception\NoControllerException;

/**
 * Main router of this plugin which creates from a config file the wordpress
 * admin navigation. Furthermore all requests gets handles by a magic function which
 * catch all function calls.
 */
class Router
{
    private $container;
    private $config;

    /**
     * Initialize router and load config.
     *
     * @param Container $container dependency injection container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->loadConfig();
    }

    /**
     * Load config from file and process it with the RouterConfig definition.
     */
    private function loadConfig()
    {
        $routingConfig = Yaml::parse(
            file_get_contents(
                $this->container->getParameter('ontopress.root_dir').'/Resources/config/routing.yml'
            )
        );

        $processor = new Processor();
        $configuration = new RouterConfig();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            array($routingConfig)
        );

        $this->config = $processedConfiguration;
    }

    /**
     * Generates an url from sitename.
     *
     * @param string $siteName   site name
     * @param array  $parameters parameters
     *
     * @return string url
     */
    public function generate($siteName, $parameters = array())
    {
        $url = '?page='.$siteName;
        foreach ($parameters as $parameter => $value) {
            $url .= '&'.$parameter.'='.urlencode($value);
        }

        return $url;
    }

    /**
     * Creates wordpress admin menu with the help of the config entries.
     */
    public function setRoutes()
    {
        foreach ($this->config['sites'] as $siteName => $site) {
            $siteName = preg_replace('/[^A-Za-z0-9 ]/', '', $siteName);
            add_menu_page(
                $site['page_title'],
                $site['menu_title'],
                $site['capability'],
                $siteName,
                array($this, 'action_'.$siteName),
                $site['icon'],
                $site['position']
            );

            foreach ($site['sub_sites'] as $subSiteName => $subSite) {
                $subSiteName = preg_replace('/[^A-Za-z0-9 ]/', '', $subSiteName);
                $subSiteName = $siteName == $subSiteName ? $siteName : $siteName.'_'.$subSiteName;
                $parent = $subSite['virtual'] ? null : $siteName;

                add_submenu_page(
                    $parent,
                    $subSite['page_title'],
                    $subSite['menu_title'],
                    $subSite['capability'],
                    $subSiteName,
                    array($this, 'action_'.$subSiteName)
                );
            }
        }
    }

    /**
     * Catch wordpress page request and call the defined controller.
     * The response of the controller is only printed on the screen and won't
     * returned by this function.
     *
     * @param string $method    method name which is called
     * @param string $arguments arguments of method
     */
    public function __call($method, $arguments)
    {
        // Check if the 'action_' keyword exist in method name
        if (substr($method, 0, 7) == 'action_') {
            $action = substr($method, 7, strlen($method));
        } else {
            throw new \BadMethodCallException(
                "Undefined method '".$method."'. The method name must start with 'action_'"
            );
        }

        // TODO: better notice/failure handling
        if (strpos($action, '_') !== false) {
            list($parent, $child) = explode('_', $action);
            $call = $this->config['sites'][$parent]['sub_sites'][$child]['controller'];
        } else {
            $parent = $action;
            $call = $this->config['sites'][$parent]['controller'];
        }

        echo $this->callController($call);
    }

    /**
     * Converts the controller call string into an function call.
     *
     * @param string $controllerCall Controller call in 'controller:action' format
     *
     * @throws InvalidControllerCallException if the Controller call is invalid
     * @throws NoActionException              if the Action method is not in the Controller
     * @throws NoControllerException          if no Controller is found
     *
     * @return string response of action method
     */
    private function callController($controllerCall)
    {
        if (strpos($controllerCall, ':') === false) {
            throw new InvalidControllerCallException($controllerCall);
        }

        list($class, $method) = explode(':', $controllerCall);

        $class = 'OntoPress\\Controller\\'.$class.'Controller';

        if (class_exists($class)) {
            $controller = new $class($this->container);
            if (method_exists($controller, $method.'Action')) {
                $request = Request::createFromGlobals();
                $this->container->get('symfony.twig')
                    ->addGlobal('request', $request);

                return call_user_func(array($controller, $method.'Action'), $request);
            } else {
                throw new NoActionException($class, $method);
            }
        } else {
            throw new NoControllerException($class);
        }
    }
}
