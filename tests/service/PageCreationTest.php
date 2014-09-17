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
		$title = 'Hello';
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

	/**
	 * @test
	 * it should not create any page if no route meta option is in the database
	 */
	public function it_should_not_create_any_page_if_no_route_meta_option_is_in_the_database() {
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		$originalCount = get_posts( array( 'post_type' => 'page' ) );
		$sut           = new RoutePages_PageManager();
		$sut->generateRoutePages();
		$newCount = get_posts( array( 'post_type' => 'page' ) );
	}

	/**
	 * @test
	 * it should not create any page if route meta option is empty
	 */
	public function it_should_not_create_any_page_if_route_meta_option_is_empty() {
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		add_option( WPRouting_PersistableRoute::OPTION_ID, array() );
		$originalCount = get_posts( array( 'post_type' => 'page' ) );
		$sut           = new RoutePages_PageManager();
		$sut->generateRoutePages();
		$newCount = get_posts( array( 'post_type' => 'page' ) );
	}

	/**
	 * @test
	 * it should not create any page if route meta option is missing permalink
	 */
	public function it_should_not_create_any_page_if_route_meta_option_is_missing_permalink() {
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		add_option( WPRouting_PersistableRoute::OPTION_ID, array(
				'routeOne' => array(
					'title' => 'Route One',
					'generate' => 'page'
				)
			) );
		$originalCount = get_posts( array( 'post_type' => 'page' ) );
		$sut           = new RoutePages_PageManager();
		$sut->generateRoutePages();
		$newCount = get_posts( array( 'post_type' => 'page' ) );
	}

	/**
	 * @test
	 * it should not generate any page if route meta option is missing title
	 */
	public function it_should_not_generate_any_page_if_route_meta_option_is_missing_title() {
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		add_option( WPRouting_PersistableRoute::OPTION_ID, array(
				'routeOne' => array(
					'permalink' => '^route-one$',
					'generate' => 'page'
				)
			) );
		$originalCount = get_posts( array( 'post_type' => 'page' ) );
		$sut           = new RoutePages_PageManager();
		$sut->generateRoutePages();
		$newCount = get_posts( array( 'post_type' => 'page' ) );
	}

	/**
	 * @test
	 * it should not generate any page if route meta option is missing the generate value
	 */
	public function it_should_not_generate_any_page_if_route_meta_option_is_missing_the_generate_value() {
		add_filter( RoutePages::SHOULD_GENERATE_ROUTE_POSTS, '__return_true' );
		add_option( WPRouting_PersistableRoute::OPTION_ID, array(
				'routeOne' => array(
					'permalink' => '^route-one$',
					'title' => 'Route One'
				)
			) );
		$originalCount = get_posts( array( 'post_type' => 'page' ) );
		$sut           = new RoutePages_PageManager();
		$sut->generateRoutePages();
		$newCount = get_posts( array( 'post_type' => 'page' ) );
	}
}