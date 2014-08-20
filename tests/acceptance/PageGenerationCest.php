<?php

class PageGenerationCest
{
    /**
     * @test
     * it should create a route page if a persisted route meta info is in the db
     */
    public function it_should_create_a_route_page_if_a_persisted_route_meta_info_is_in_the_db(AcceptanceTester $I)
    {
        // Prepare
        // arrays are stored serialized in wp db
        $routeMeta = serialize(array('route-one' => array('title' => 'Route one', 'permalink' => 'route-one', 'generate' => 'page')));
        $I->haveOptionInDatabase(RoutePages_GeneratingRoute::OPTION_ID, $routeMeta);

        // Execute
        $I->loginAsAdmin();
        $I->amOnPluginsPage();
        $I->activatePlugin('wp-router');
        $I->activatePlugin('route-pages');

        // Verify
        $I->amOnPagesPage();
        $I->see('Route one', '.page-title');
    }
}