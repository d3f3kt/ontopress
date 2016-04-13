<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionModule extends AbstractModule
{
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
