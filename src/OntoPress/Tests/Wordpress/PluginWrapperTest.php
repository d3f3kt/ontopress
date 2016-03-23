<?php

namespace OntoPress\Tests;

use Brain\Monkey\WP\Actions;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Wordpress\PluginWrapper;

class PluginWrapperTest extends OntoPressWPTestCase
{
    /**
     * @var PluginWrapper
     */
    private $pluginWrapper;

    public function setUp()
    {
        parent::setUp();
        $this->pluginWrapper = new PluginWrapper(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->pluginWrapper);
        parent::tearDown();
    }

    public function testInit()
    {
        $this->pluginWrapper->init();
    }

    public function testAdminInit()
    {
        Actions::expectAdded('admin_notices')->once();
        Actions::expectAdded('admin_menu')->once();
        Actions::expectAdded('admin_enqueue_scripts')->once();

        $this->pluginWrapper->adminInit();
    }

    public function testIntall()
    {
        $this->pluginWrapper->install();
        $this->pluginWrapper->uninstall();
    }
}
