<?php


class Headway {

}


class VEPanelTest extends PHPUnit_Framework_TestCase {

	protected $className;
	protected $file;

	public function setUp() {
		$this->className = 'SomeClass';
	}

	public function testConstructWillCallAddAnActionToAfterSetupTheme() {
		$mockf = $this->getMockFunctions( array( 'add_action' ) );
		$sut   = $this->getMockBuilder( 'tad_HeadwayVEPanel' )->disableOriginalConstructor()->setMethods( array( '__construct' ) )->getMock();
		$mockf->expects( $this->once() )->method( 'add_action' )->with( 'after_setup_theme', array(
			$sut,
			'register'
		) );
		$className = 'SomeClass';
		$sut->__construct( $this->className, $mockf );
	}

	protected function getMockFunctions( array $methods ) {
		return $this->getMock( 'tad_FunctionsAdapterInterface', array_merge( array( '__call' ), $methods ) );
	}

	public function testRegisterWillAddAddAndActionToHeadwayHook() {
		$mockf = $this->getMockFunctions( array( 'add_action' ) );
		$tag   = 'headway_visual_editor_display_init';
		$mockf->expects( $this->at( 1 ) )->method( 'add_action' )->with( $tag, $this->anything(), $this->anything() );
		$sut = new tad_HeadwayVEPanel( $this->className, $mockf );
		$sut->register();
	}

	public function testRegisterWillReturnTrueUponSuccessfulActionHooking() {
		$sut = new tad_HeadwayVEPanel( $this->className );
		$this->assertTrue( $sut->register() );
	}

	public function testOnStaticMethodWillAllowFluentInterface() {
		$mockf = $this->getMockFunctions( array( 'add_action' ) );
		$mockf->expects( $this->once() )->method( 'add_action' );
		$className = 'SomeClass';
		$this->assertInstanceOf( 'tad_HeadwayVEPanel', tad_HeadwayVEPanel::on( $className, $mockf ) );
	}
}