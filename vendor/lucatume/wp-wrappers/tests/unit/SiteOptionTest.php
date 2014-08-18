<?php


class SiteOptionTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->f = $this->getMock( 'tad_FunctionsAdapterInterface', array(
				'__call',
				'get_option',
				'get_site_option',
				'update_option',
				'update_site_option'
			) );
	}

	public function testOnMethodAllowsLoadingSerializedSiteOption() {
		$data    = serialize( array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' ) );
		$newData = serialize( array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' ) );
		$this->f->expects( $this->once() )->method( 'get_site_option' )->with( 'some' )->will( $this->returnValue( $data ) );
		$sut = tad_SiteOption::on( 'some', $this->f );
		$this->assertEquals( 'some', $sut->optionSlug() );
		$this->assertFalse( $sut->underscoreProperties() );
		$this->assertTrue( $sut->isSerialized() );
		$this->assertTrue( $sut->isSiteOption() );
		$this->assertEquals( 34, $sut->firstValue );
		$this->assertEquals( true, $sut->secondValue );
		$this->assertEquals( 'dolor sit', $sut->thirdValue );
		$sut->firstValue  = 23;
		$sut->secondValue = false;
		$sut->thirdValue  = 'lorem ipsum';
		$this->f->expects( $this->once() )->method( 'update_site_option' )->with( 'some' )->will( $this->returnValue( $data ) );
	}

	public function testOnMethodAllowsLoadingSiteOption() {
		$data    = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$newData = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_site_option' )->with( 'some' )->will( $this->returnValue( $data ) );
		$sut = tad_SiteOption::on( 'some', $this->f );
		$this->assertEquals( 'some', $sut->optionSlug() );
		$this->assertFalse( $sut->underscoreProperties() );
		$this->assertFalse( $sut->isSerialized() );
		$this->assertTrue( $sut->isSiteOption() );
		$this->assertEquals( 34, $sut->firstValue );
		$this->assertEquals( true, $sut->secondValue );
		$this->assertEquals( 'dolor sit', $sut->thirdValue );
		$sut->firstValue  = 23;
		$sut->secondValue = false;
		$sut->thirdValue  = 'lorem ipsum';
		$this->f->expects( $this->once() )->method( 'update_site_option' )->with( 'some' )->will( $this->returnValue( $data ) );
	}
}