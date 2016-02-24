<?php

namespace OntoPress\Libary\Exception;

/**
 * No Controller was found exception.
 */
class NoControllerException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($controller)
    {
        parent::__construct(
            'Can not found "OntoPress\Controller\\'.$controller.'Controller".'
        );
    }
}
