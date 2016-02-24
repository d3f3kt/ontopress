<?php

namespace OntoPress\Wordpress;

use Symfony\Component\DependencyInjection\Container;

class AdminInit
{
    private static $initialize;
    private $container;

    public static function init(Container $container)
    {
        return self::$initialize ? self::$initialize : new self($container);
    }

    private function __construct(Container $container)
    {
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('admin_enqueue_scripts', array($this, 'loadResources'));
    }

    public function adminMenu()
    {
        // TODO: Routing config
        add_menu_page('Lorum Impus', 'OntoPress', 'manage_options', 'Ontopress', array('BKeys_Admin', 'displayManagePage'), 'dashicons-list-view', 30);
        add_submenu_page('Ontopress', 'Ontologien verwalten', 'Verwalten', 'manage_options', 'Ontopress', array('ROUTING', 'displayManagePage'));
        add_submenu_page('Ontopress', 'Kampagne hinzufügen', 'Neu hinzufügen', 'manage_options', 'OntopressAdd', array('ROUTING', 'displayAddPage'));
        add_submenu_page(null, 'Ontologie bearbeiten', 'Bearbeiten', 'manage_options', 'OntopressOntologyEdit', array('ROUTING', 'displayEditPage'));
    }

    public function loadResources()
    {
        //TODO: load custom css/js
    }
}
