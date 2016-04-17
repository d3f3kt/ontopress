<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;

/**
 * Class FrontendInit
 *
 */
class FrontendInit
{
    /**
     *
     *
     * @var EntityManager
     */
    private static $initialize;

    /**
     *
     *
     * @var EntityManager
     */
    private $container;

    /**
     *
     *
     * @param Container $container
     *
     * @return EntityManager|FrontendInit
     */
    public static function init(Container $container)
    {
        return self::$initialize ? self::$initialize : new self($container);
    }

    /**
     * FrontendInit constructor.
     * Default constructor with no function.
     *
     * @param Container $container
     */
    private function __construct(Container $container)
    {
    }
}
