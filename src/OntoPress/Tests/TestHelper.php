<?php

namespace OntoPress\Tests;

class TestHelper
{
    public static function emulateWPUser()
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
