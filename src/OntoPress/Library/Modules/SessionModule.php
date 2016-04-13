<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Class SessionModule
 * Module loaded in AppKernel, to create new Session.
 */
class SessionModule extends AbstractModule
{
    /**
     * Creates new Session in given environment.
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($this->environment == 'test') {
            $session = new Session(new MockArraySessionStorage());
        } else {
            $session = new Session();
        }

        $container->set('symfony.session', $session);
    }
}
