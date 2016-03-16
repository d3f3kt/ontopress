<?php

namespace OntoPress\Libary;

use Symfony\Component\DependencyInjection\Container;

class OntoPressTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $container;

    protected static function getContainer()
    {
        if (self::$container instanceof Container) {
            return self::$container;
        } else {
            return self::includeContainer();
        }
    }

    protected static function includeContainer()
    {
        static::$container = include __DIR__.'/../bootstrap.php';

        return static::$container;
    }
}
