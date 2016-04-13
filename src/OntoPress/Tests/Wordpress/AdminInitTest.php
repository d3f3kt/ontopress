<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use OntoPress\Library\OntoPressWPTestCase;
use OntoPress\Wordpress\AdminInit;

class AdminInitTest extends OntoPressWPTestCase
{
    /**
     * @var AdminInit
     */
    private $adminInit;

    public function setUp()
    {
        parent::setUp();
        $this->adminInit = AdminInit::init(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->adminInit);
        parent::tearDown();
    }

    public function testAdminNotices()
    {
        static::getContainer()->get('symfony.session')->getFlashBag()->add('info', 'testNotice');

        ob_start();
        $this->adminInit->adminNotices();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains('testNotice', $output);
    }

    public function testAdminMenu()
    {
        Functions::when('add_menu_page')->justReturn(true);
        Functions::when('add_submenu_page')->justReturn(true);

        $this->adminInit->adminMenu();
    }

    public function testLoadResources()
    {
        Functions::when('wp_enqueue_style')->justReturn(true);
        Functions::when('wp_enqueue_script')->justReturn(true);

        $this->adminInit->loadResources();
    }
}
