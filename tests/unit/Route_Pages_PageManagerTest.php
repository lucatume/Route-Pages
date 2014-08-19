<?php

class Route_Pages_PageManagerTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    /**
     * @test
     * it should be instantiatable
     */
    public function it_should_be_instantiatable()
    {
        $this->assertInstanceOf('RoutePages_PageManager', new RoutePages_PageManager());
    }

    /**
     * @test
     * it should allow setting the persisted routes information option name
     */
    public function it_should_allow_setting_the_persisted_routes_information_option_name()
    {
        $routesMetaOptionName = 'foo';
        $sut = new RoutePages_PageManager($routesMetaOptionName);
        $this->assertEquals($routesMetaOptionName, $sut->getRoutesMetaOptionName());
        $sut->setRoutesMetaOptionName('baz');
        $this->assertEquals('baz', $sut->getRoutesMetaOptionName());
    }

    /**
     * @test
     * it should allow setting the created pages meta option name
     */
    public function it_should_allow_setting_the_created_pages_meta_option_name()
    {
        $pagesMetaOptionName = 'foo';
        $sut = new RoutePages_PageManager(null, $pagesMetaOptionName);
        $this->assertEquals($pagesMetaOptionName, $sut->getPagesMetaOptionName());
        $sut->setPagesMetaOptionName('baz');
        $this->assertEquals('baz', $sut->getPagesMetaOptionName());
    }

    /**
     * @test
     * it should get the val of the persisted routes meta option
     */
    public function it_should_get_the_val_of_the_persisted_routes_meta_option()
    {
        $option = $this->getMock('tad_Option');
        $option->expects($this->once())
            ->method('getValues');
        $sut = new RoutePages_PageManager(null, null, $option);
        $sut->createRoutePages();
    }

    /**
     * @test
     * it should insert a page for each persisted route that should generate a page
     */
    public function it_should_insert_a_page_for_each_persisted_route_that_should_generate_a_page()
    {
        $oneRouteMeta = array('helloRoute' => array('title' => 'Hello Route', 'permalink' => 'hello-route', 'generate' => 'page'));
        $option = $this->getMock('tad_Option');
        $option->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue($oneRouteMeta));
        $insertArguments = array(
            'post_content' => '',
            'post_name' => 'hello-route',
            'post_title' => 'Hello Route',
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        $functions = $this->getMockBuilder('tad_FunctionsAdapter')
            ->disableOriginalConstructor()
            ->setMethods(array('__call', 'wp_insert_post', 'get_post_types'))
            ->getMock();
        $functions->expects($this->once())
            ->method('get_post_types')
            ->will($this->returnValue(array('page','post','some-cpt')));
        $functions->expects($this->once())
            ->method('wp_insert_post')
            ->with($this->equalTo($insertArguments))
            ->will($this->returnValue(23));
        $sut = new RoutePages_PageManager(null ,null ,$option, null, $functions);
        $sut->createRoutePages();
    }

    /**
     * @test
     * it should not insert any page if there are no routes generating pages
     */
    public function it_should_not_insert_any_page_if_there_are_no_routes_generating_pages()
    {
        $option = $this->getMock('tad_Option');
        $option->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue(array()));
        $functions = $this->getMockBuilder('tad_FunctionsAdapter')
            ->disableOriginalConstructor()
            ->setMethods(array('__call', 'wp_insert_post'))
            ->getMock();
        $functions->expects($this->never())
            ->method('wp_insert_post');
        $sut = new RoutePages_PageManager(null ,null ,$option, null, $functions);
        $sut->createRoutePages();
    }

    /**
     * @test
     * it should generate route pages for routes marked with the generate meta alone
     */
    public function it_should_generate_route_pages_for_routes_marked_with_the_generate_meta_alone()
    {
        $oneRouteMeta = array(
            'helloRoute' => array('title' => 'Hello Route', 'permalink' => 'hello-route', 'generate' => 'page'),
            'anotherRoute' => array('title' => 'Another Route', 'permalink' => 'another-route'),
        );
        $option = $this->getMock('tad_Option');
        $option->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue($oneRouteMeta));
        $insertArguments = array(
            'post_content' => '',
            'post_name' => 'hello-route',
            'post_title' => 'Hello Route',
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        $functions = $this->getMockBuilder('tad_FunctionsAdapter')
            ->disableOriginalConstructor()
            ->setMethods(array('__call', 'wp_insert_post', 'get_post_types'))
            ->getMock();
        $functions->expects($this->once())
            ->method('get_post_types')
            ->will($this->returnValue(array('page','post','some-cpt')));
        $functions->expects($this->once())
            ->method('wp_insert_post')
            ->with($this->equalTo($insertArguments))
            ->will($this->returnValue(23));
        $sut = new RoutePages_PageManager(null ,null ,$option, null, $functions);
        $sut->createRoutePages();
    }

    /**
     * @test
     * it should not generate a route page if the post type to generate is not among the supported ones
     */
    public function it_should_not_generate_a_route_page_if_the_post_type_to_generate_is_not_among_the_supported_ones()
    {
        $oneRouteMeta = array(
            'helloRoute' => array('title' => 'Hello Route', 'permalink' => 'hello-route', 'generate' => 'page'),
            'anotherRoute' => array('title' => 'Another Route', 'permalink' => 'another-route', 'generate' => 'cpt'),
        );
        $option = $this->getMock('tad_Option');
        $option->expects($this->once())
            ->method('getValues')
            ->will($this->returnValue($oneRouteMeta));
        $insertArguments = array(
            'post_content' => '',
            'post_name' => 'hello-route',
            'post_title' => 'Hello Route',
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        $functions = $this->getMockBuilder('tad_FunctionsAdapter')
            ->disableOriginalConstructor()
            ->setMethods(array('__call', 'wp_insert_post', 'get_post_types'))
            ->getMock();
        $functions->expects($this->once())
            ->method('get_post_types')
            ->will($this->returnValue(array('page','post','some-cpt')));
        $functions->expects($this->once())
            ->method('wp_insert_post')
            ->with($this->equalTo($insertArguments))
            ->will($this->returnValue(23));
        $sut = new RoutePages_PageManager(null ,null ,$option, null, $functions);
        $sut->createRoutePages();
    }
}
