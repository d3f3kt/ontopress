<?php

namespace OntoPress\Libary\Exception;

/**
 * Action method was not in controller.
 */
class NoActionException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($controller, $method)
    {
        parent::__construct(
            'Method "'.$method.'Action" was not found in "OntoPress\Controller\\'.$controller.'Controller".'
        );
    }
}
