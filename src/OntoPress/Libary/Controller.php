<?php

namespace OntoPress\Controller;

use Symfony\Component\Form\FormInterface;
/**
 * Abstract Controller.
 */
abstract class Controller
{
    /**
     * Dependency Injection Container.
     *
     * @var Container
     */
    private $container;

    /**
     * Initialize Controller.
     *
     * @param Container $container Dependency Injection Container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Renders a Template
     *
     * @param string $name The template name
     * @param array $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    protected function render($name , array $context = array())
    {
        return $this->get('twig')->render($name, $context);
    }

    /**
     * Translates the given message
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function trans($id, array $parameters = array(),  $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number
     *
     * @param string $id The message id (may also be an object that can be cast to string)
     * @param int $number The number to use to find the indice of the message
     * @param array $parameters An array of parameters for the message
     * @param string|null $domain The domain for the message or null to use the default
     * @param string|null $locale The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }
    /**
     * Returns a form
     *
     * @param string $type The type of the form
     * @param null $data The initial data
     * @param array $options The options
     *
     * @return FormInterface The form named after the type
     */
    protected function create($type = 'form', $data = null, array $options = array())
    {
        return $this->get('form')->create($type, $data, $options);
    }

    /**
     * Get a service from Container
     * @param $name Name of service
     *
     * @return mixed service
     */
    protected function get($name)
    {
        return $this->container->get($name);
    }
}