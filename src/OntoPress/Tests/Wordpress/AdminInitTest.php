<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Wordpress\AdminInit;

/**
 * Class AdminInitTest
 * Tests AdminInit Class.
 */
class AdminInitTest extends OntoPressWPTestCase
{
    /**
     * Admin Instance.
     * @var AdminInit
     */
    private $adminInit;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        parent::setUp();
        $this->adminInit = AdminInit::init(static::getContainer());
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->adminInit);
        parent::tearDown();
    }

    /**
     * Tests adminNotices method.
     */
    public function testAdminNotices()
    {
        static::getContainer()->get('symfony.session')->getFlashBag()->add('info', 'testNotice');

        ob_start();
        $this->adminInit->adminNotices();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains('testNotice', $output);
    }

    /**
     * Tests adminMenu method.
     */
    public function testAdminMenu()
    {
        Functions::when('add_menu_page')->justReturn(true);
        Functions::when('add_submenu_page')->justReturn(true);

        $this->adminInit->adminMenu();
    }

    /**
     * Tests loadResources method.
     */
    public function testLoadResources()
    {
        Functions::when('wp_enqueue_style')->justReturn(true);
        Functions::when('wp_enqueue_script')->justReturn(true);

        $this->adminInit->loadResources();
    }
}
