<?php

namespace OntoPress\Tests;

use Brain\Monkey\Functions;
use OntoPress\Controller\OntologyController;
use OntoPress\Libary\OntoPressWPTestCase;

class OntologyControllerTest extends OntoPressWPTestCase
{
    /**
     * @var OntologyController
     */
    private $ontologyController;

    public function setUp()
    {
        parent::setUp();
        $this->ontologyController = new OntologyController(static::getContainer());
    }

    public function tearDown()
    {
        unset($this->ontologyController);
        parent::tearDown();
    }

    public function testOntologyPages()
    {
        Functions::when('wp_get_current_user')->alias(array($this, 'emulateWPUser'));

        $manageOutput = $this->ontologyController->showManageAction();
        $addOutput = $this->ontologyController->showAddAction();
        $deleteOutput = $this->ontologyController->showDeleteAction();

        $this->assertContains('Ontologie Verwaltung', $manageOutput);
        $this->assertContains('Ontologie hochladen', $addOutput);
        $this->assertContains('Ontologie Löschen', $deleteOutput);
    }

    public function emulateWPUser()
    {
        $testUser = (object) array(
            'ID' => 2,
            'user_login' => 'TestUser',
            'user_email' => 'testUser@example.com',
            'user_firstname' => 'John',
            'user_lastname' => 'Doe',
            'user_nicename' => 'Johni',
            'display_name' => 'Johni',
        );

        return $testUser;
    }
}
