<?php

namespace OntoPress\Libary\Twig;

use OntoPress\Libary\Router;

/**
 * Twig function to print urls from site name.
 */
class RouterExtension extends \Twig_Extension
{
    /**
     * OntoPress ROuter.
     *
     * @var Router
     */
    private $router;

    /**
     * Initialize Router Extension.
     *
     * @param Router $router OntoPress Router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('path', array($this, 'pathFunction')),
        );
    }

    /**
     * Generates an url from sitename.
     *
     * @param string $siteName   site name
     * @param array  $parameters parameters
     *
     * @return string url
     */
    public function pathFunction($siteName, $parameters = array())
    {
        return $this->router->generate($siteName, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'routerExtension';
    }
}
