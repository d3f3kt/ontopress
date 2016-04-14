<?php

namespace OntoPress\Library\Modules;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

/**
 * Module which adds the Symfony Session component to the dependency injection component.
 */
class SessionModule extends AbstractModule
{
    /**
     * Creates new Session according to the environment.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if ($this->isTestEnv()) {
            $session = new Session(new MockArraySessionStorage());
        } else {
            $session = new Session();
        }

        $container->set('symfony.session', $session);
    }
}
