<?php


class PageCreationTest extends \WP_UnitTestCase {

	/**
	 * @test
	 * it should create a page if good route meta has been persisted
	 */
	public function it_should_create_a_page_if_good_route_meta_has_been_persisted() {
		// trigger route post generation
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		// set the route meta in the database
		$title     = 'Hello';
		$routeMeta = array(
			'hello' => array(
				'title' => $title,
				'permalink' => '^hello$',
				'generate' => 'page'
			)
		);
		add_option( WPRouting_PersistableRoute::OPTION_ID, $routeMeta );
		// make sure the page does not exist
		$this->assertNull( get_page_by_title( $title ) );
		// trigger page generation
		$sut = new RoutePages_PageManager();
		$sut->generateRoutePages();
		// check for the page to be there
		$page = get_page_by_title( $title );
		$this->assertNotNull( $page );
		$this->assertEquals( $title, $page->post_title );
		$this->assertEquals( '', $page->post_content );
		$this->assertEquals( 'hello', $page->post_name );

	}

}