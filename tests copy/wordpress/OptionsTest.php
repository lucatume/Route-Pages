<?php

class OptionsTest extends \WP_UnitTestCase
{
    public function test_will_store_and_retrieve_an_option()
    {
        add_option('some', 'foo');
        $this->assertEquals('foo', get_option('some'));
    }

}