<?php

namespace OntoPress\Tests;

use Brain\Monkey\WP\Actions;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Wordpress\PluginWrapper;

/**
 * Class PluginWrapperTest
 * Tests PluginWrapper.
 */
class PluginWrapperTest extends OntoPressWPTestCase
{
    /**
     * @var PluginWrapper
     */
    private $pluginWrapper;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->pluginWrapper = new PluginWrapper(static::getContainer());
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->pluginWrapper);
        parent::tearDown();
    }

    /**
     * Tests init method.
     */
    public function testInit()
    {
        $this->pluginWrapper->init();
    }

    /**
     * Tests adminInit method.
     */
    public function testAdminInit()
    {
        Actions::expectAdded('admin_notices')->once();
        Actions::expectAdded('admin_menu')->once();
        Actions::expectAdded('admin_enqueue_scripts')->once();

        $this->pluginWrapper->adminInit();
    }

    /**
     * Tests install method.
     */
    public function testIntall()
    {
        $this->pluginWrapper->install();
        $this->pluginWrapper->uninstall();
    }
}
