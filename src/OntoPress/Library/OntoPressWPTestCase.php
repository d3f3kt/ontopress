<?php

namespace OntoPress\Library;

use Brain\Monkey;

/**
 * OntoPress Test case for classes which need Wordpress functions.
 */
class OntoPressWPTestCase extends OntoPressTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        Monkey::setUpWP();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        Monkey::tearDownWP();
        parent::tearDown();
    }
}
