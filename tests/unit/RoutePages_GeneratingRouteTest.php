<?php

class RoutePages_GeneratingRouteTest extends \PHPUnit_Framework_TestCase
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
        $this->assertInstanceOf('RoutePages_GeneratingRoute', new RoutePages_GeneratingRoute());
    }

    /**
     * @test
     * it should extend WPRouting_PersistableRoute
     */
    public function it_should_extend_wp_routing_persistable_route()
    {
        $this->assertInstanceOf('WPRouting_PersistableRoute', new RoutePages_GeneratingRoute());
    }

    /**
     * @test
     * it should not generate a page for a route by default
     */
    public function it_should_not_generate_a_page_for_a_route_by_default()
    {
        $sut = new RoutePages_GeneratingRoute();
        $this->assertFalse($sut->isGenerating());
    }

    /**
     * @test
     * it should allow setting the type of post the route will generate
     */
    public function it_should_allow_setting_the_type_of_post_the_route_will_generate()
    {
        $sut = new RoutePages_GeneratingRoute();
        $sut->shouldGenerate('page');
        $this->assertTrue($sut->isGenerating());
        $this->assertTrue($sut->isGenerating('page'));
        $this->assertFalse($sut->isGenerating('post'));
    }

    /**
     * @test
     * it should allow fluent interface on the shouldGenerate method
     */
    public function it_should_allow_fluent_interface_on_the_should_generate_method()
    {
        $sut = new RoutePages_GeneratingRoute();
        $this->assertSame($sut, $sut->shouldGenerate('page'));
    }

    /**
     * @test
     * it should hook into PersistableRoute filter to add the generate argument to the persisted ones
     */
    public function it_should_hook_into_persistable_route_filter_to_add_the_generate_argument_to_the_persisted_ones()
        {
            $sut = new RoutePages_GeneratingRoute();
            $functions = $this->getMock('tad_FunctionsAdapterInterface', array('__call', 'add_filter'));
            $functions->expects($this->once())
                ->method('add_filter')
                ->with('WP_Routing_PersistableRoute_persist_route', array($sut, 'addRouteMetaArgs'));
            $sut->setFunctionsAdapter($functions);
            $sut->hook();
    }

    /**
     * @test
     * it should add the generate argument to the route meta
     */
    public function it_should_add_the_generate_argument_to_the_route_meta()
    {
        $sut = new RoutePages_GeneratingRoute();
        $sut->shouldGenerate();
        $routeMeta = $sut->addRouteMetaArgs(array('title' => 'Title', 'permalink' => 'permalink'), 'some-route');
        $this->assertEquals(array('title' => 'Title', 'permalink' => 'permalink', 'generate' => 'page'), $routeMeta);
    }

    /**
     * @test
     * it should add the generate argument to the route meta using the custom post type
     */
    public function it_should_add_the_generate_argument_to_the_route_meta_using_the_custom_post_type()
    {
        $sut = new RoutePages_GeneratingRoute();
        $sut->shouldGenerate()->setGeneratedPostType('custom-post-type');
        $routeMeta = $sut->addRouteMetaArgs(array('title' => 'Title', 'permalink' => 'permalink'), 'some-route');
        $this->assertEquals(array('title' => 'Title', 'permalink' => 'permalink', 'generate' => 'custom-post-type'), $routeMeta);
    }
}