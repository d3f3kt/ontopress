<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;

class FrontendInit
{
    private static $initialize;
    private $container;

    public static function init(Container $container)
    {
        return self::$initialize ? self::$initialize : new self($container);
    }

    private function __construct(Container $container)
    {
    }
}
