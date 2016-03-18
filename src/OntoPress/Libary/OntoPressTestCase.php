<?php

namespace OntoPress\Libary;

use Symfony\Component\DependencyInjection\Container;

class OntoPressTestCase extends \PHPUnit_Framework_TestCase
{
    protected static $container;

    /**
     * Call protected/private method of a class.
     *
     * @param object $object     Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
    
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
