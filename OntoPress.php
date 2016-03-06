<?php

/*
Plugin Name: OntoPress
Plugin URI: http://aksw.org/
Description: Wordpress Plugin which converts ontologies into HTML forms on the fly.
Version: 0.1.0
Author: WPOD16
Author URI: http://pcai042.informatik.uni-leipzig.de/~wpod16/
License: ...PENDING...
*/

$ontopressContainer = require plugin_dir_path(__FILE__).'src/OntoPress/bootstrap.php';
use OntoPress\Wordpress\PluginWrapper;

// set additional container parameters
$ontopressContainer->set('wpdb', $wpdb);
$ontopressContainer->setParameter('ontopress.plugin_dir', plugin_dir_path(__FILE__));
$ontopressContainer->setParameter('ontopress.root_dir', plugin_dir_path(__FILE__).'src/OntoPress');
$ontopressContainer->setParameter('ontopress.plugin_url', plugin_dir_url(__FILE__).'src/OntoPress');

$ontopressPlugin = new PluginWrapper($ontopressContainer);

// Wordpress plugin actions
register_activation_hook(__FILE__, array($ontopressPlugin, 'install'));
register_uninstall_hook(__FILE__, array($ontopressPlugin, 'uninstall'));

add_action('init', array($ontopressPlugin, 'init'));

if (is_admin()) {
    add_action('init', array($ontopressPlugin, 'adminInit'));
}
