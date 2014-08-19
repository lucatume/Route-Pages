<?php
use AcceptanceTester;

class PageGenerationCest
{
    /**
     * @test
     * it should create a route page if a persisted route meta info is in the db
     */
    public function it_should_create_a_route_page_if_a_persisted_route_meta_info_is_in_the_db(AcceptanceTester $I)
    {
        /**
         * Prepare
         */
        $routeMeta = mysql_escape_string(serialize(array('route-one' => array('title' => 'Route one', 'permalink' => 'route-one', 'generate' => 'page'))));
        $data = array('option_name' => RoutePages_GeneratingRoute::OPTION_ID, 'option_value' => $routeMeta, 'autoload' => 'yes');
        $I->haveInDatabase('rp_options', $data);
        // on activation and anytime page generation is triggered the plugin will call this method
        // on this class
        $pageManager = new RoutePages_PageManager();
        /**
         * Execute
         */
        $pageManager->createRoutePages();
        /**
         * Verify
         */
        $I->loginAsAdmin();
        $I->amOnPagesPage();
        $I->see('Route one', '.page-title');
    }
}