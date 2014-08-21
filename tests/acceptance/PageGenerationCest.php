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

    /**
     * @test
     * it should not generate any page if there is no persisted route meta
     */
    public function it_should_not_generate_any_page_if_there_is_no_persisted_route_meta(AcceptanceTester $I)
    {
        $I->dontSeeOptionInDatabase(['option_name' => RoutePages_GeneratingRoute::OPTION_ID]);
        $I->loginAsAdmin();
        $I->amOnPagesPage();
        $I->seeElement('#the-list .no-items');

        $I->amOnPluginsPage();
        $I->activatePlugin('wp-router');
        $I->activatePlugin('route-pages');

        $I->amOnPagesPage();
        $I->seeElement('#the-list .no-items');
    }
}