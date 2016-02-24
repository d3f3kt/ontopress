<?php

namespace OntoPress\Libary;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Container;

class Router
{
    private $container;
    private $config;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->loadConfig();
    }

    private function loadConfig()
    {
        $routingConfig = Yaml::parse(
            file_get_contents($this->container->getParameter('ontopress.root_dir').'/Resources/config/routing.yml')
        );

        $processor = new Processor();
        $configuration = new RouterConfig();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            array($routingConfig)
        );

        $this->config = $processedConfiguration;
    }

    public function setRoutes()
    {
        foreach ($this->config['sites'] as $siteName => $site) {
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
                // TODO: escaping _
                $subSiteName = $siteName == $subSiteName ? $siteName : $siteName.'_'.$subSiteName;
                add_submenu_page(
                    $siteName,
                    $subSite['page_title'],
                    $subSite['menu_title'],
                    $subSite['capability'],
                    $subSiteName,
                    array($this, 'action_'.$subSiteName)
                );
            }
        }
    }

    public function __call($method, $arguments)
    {
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

    private function callController($controllerCall)
    {
        if (strpos($controllerCall, ':') === false) {
            throw new \Exception('Controller string invalid'); //TODO: Own exception
        }

        list($class, $method) = explode(':', $controllerCall);

        $class = 'OntoPress\\Controller\\'.$class.'Controller';

        if (class_exists($class)) {
            $controller = new $class($this->container);
            if (method_exists($controller, $method.'Action')) {
                return call_user_func(array($controller, $method.'Action'));
            } else {
                echo $method;
                throw new \Exception('Method not found'); // TODO: own exception MethodNotFound
            }
        } else {
            throw new \Exception('Class not found'); // TODO: own exception ControllerNotFound
        }
    }
}
