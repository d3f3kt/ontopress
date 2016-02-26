<?php

namespace OntoPress\Libary\Twig;

use Symfony\Component\DependencyInjection\Container;

/**
 * Twig function to print urls from site name.
 */
class RouterExtension extends \Twig_Extension
{
    /**
     * Initialize Router Extension.
     *
     * @param Container $container dependency injection container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
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
        //TODO: maybe compare the siteName with the routing tree
        $url = '?page='.$siteName;
        foreach ($parameters as $parameter => $value) {
            $url .= '&'.$parameter.'='.urlencode($value);
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'routerExtension';
    }
}
