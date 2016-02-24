<?php

namespace OntoPress\Libary\Exception;

/**
 * Invalid controller call exception.
 */
class InvalidControllerCallException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($controllerCall)
    {
        parent::__construct(
            'Following controller call "'.$controllerCall.'" do not match the "controller:action" pattern.'
        );
    }
}
