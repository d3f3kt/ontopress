<?php

namespace OntoPress\Tests\Entity;

use OntoPress\Entity\OntologyField;
use OntoPress\Entity\Restriction;
use OntoPress\Library\OntoPressTestCase;

/**
 * Class RestrictionTest
 * Creates a Restriction and connected OntologyField, testing them.
 */
class RestrictionTest extends OntoPressTestCase
{
    /**
     * Restriction Entity.
     * @var Restriction
     */
    private $restriction;

    /**
     * OntologyField Entity.
     * @var OntologyField
     */
    private $ontologyField;

    /**
     * Test setUp.
     * Gets called before every test-method.
     */
    public function setUp()
    {
        $this->restriction = new Restriction();
        $this->ontologyField = new OntologyField();

        $this->restriction->setName("testName");
        $this->restriction->setOntologyField($this->ontologyField);
    }

    /**
     * Test tearDown.
     * Unsets all instances after finishing a test-method.
     */
    public function tearDown()
    {
        unset($this->restriction);
        unset($this->ontologyField);
    }

    /**
     * Tests Restrictions basic set/get-methods, which should return the new or changed attributes.
     */
    public function testRestrictionBasic()
    {
        $this->assertEquals($this->restriction->getName(), "testName");
        $this->assertNull($this->restriction->getId());
        $this->assertEquals($this->restriction->getOntologyField(), $this->ontologyField);
    }
}
