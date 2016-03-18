<?php

namespace OntoPress\Libary;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Abstract Controller.
 */
abstract class AbstractController
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
     * Renders a Template.
     *
     * @param string $name    The template name
     * @param array  $context An array of parameters to pass to the template
     *
     * @return string The rendered template
     */
    protected function render($name, array $context = array())
    {
        return $this->get('twig')->render($name, $context);
    }

    /**
     * Translates the given message.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param int         $number     The number to use to find the indice of the message
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     */
    protected function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }
    /**
     * Returns a form.
     *
     * @param string $type    The type of the form
     * @param null   $data    The initial data
     * @param array  $options The options
     *
     * @return FormInterface The form named after the type
     */
    protected function createForm($type = 'form', $data = null, array $options = array())
    {
        return $this->get('form')->create($type, $data, $options);
    }

    /**
     * Generates an url from sitename.
     *
     * @param string $siteName   site name
     * @param array  $parameters parameters
     *
     * @return string url
     */
    protected function generateRoute($siteName, $parameters = array())
    {
        return $this->get('ontopress.router')->generate($siteName, $parameters);
    }

    /**
     * Get doctrine instance.
     *
     * @return EntityManager Doctrine EntityManager
     */
    protected function getDoctrine()
    {
        return $this->get('doctrine');
    }

    /**
     * Validates a value.
     *
     * @param mixed      $value    The value to validate
     * @param null|array $groups   The validation groups to validate.
     * @param bool       $traverse Whether to traverse the value if it is traversable.
     * @param bool       $deep     Whether to traverse nested traversable values recursively.
     *
     * @return ConstraintViolationListInterface A list of constraint violations. If the list is empty, validation succeeded.
     */
    protected function validate($value, $groups = null, $traverse = false, $deep = false)
    {
        return $this->get('validator')->validate($value, $groups, $traverse, $deep);
    }

    /**
     * Get a service from Container.
     *
     * @param string $name Name of service
     *
     * @return mixed service
     */
    protected function get($name)
    {
        return $this->container->get($name);
    }

    /**
     * Get a parameter from Container.
     *
     * @param string $name Name of parameter
     *
     * @return string parameter
     */
    protected function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    protected function addFlashMessage($type, $message)
    {
        return $this->get('session')->getFlashBag()->add($type, $message);
    }
}
