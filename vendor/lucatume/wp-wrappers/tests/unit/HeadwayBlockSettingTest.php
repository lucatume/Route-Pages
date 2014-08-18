<?php
use tad_HeadwayBlockSettingsWrapper as Settings;

class HeadwayBlockSettingsTest extends PHPUnit_Framework_TestCase {

	protected $className = 'tad_StaticMocker';

	public function setUp() {
		tad_StaticMocker::_reset();
	}

	public function testConstructWillThrowForInvalidBlockParameter() {
		tad_StaticMocker::_addMethod( 'get_block', false );
		$this->setExpectedException( 'BadMethodCallException', '', 1 );
		$sut = new Settings( 'foo', $this->className );
	}

	public function testBlockSettingssAreExposedAsCamelBackProperties() {
		$val = array( 'settings' => array( 'foo' => 1, 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertEquals( 1, $sut->foo );
		$this->assertEquals( 2, $sut->baz );
		$this->assertEquals( 3, $sut->bar );
	}

	public function testConstructingOverBlockWithNoSettingsWillExposeNullProperties() {
		$val = array( 'foster' => array( 'foo' => 1, 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertNull( $sut->foo );
	}

	public function testSettingsAreAccessibleUsingTheGetSettingMethod() {
		$val = array( 'settings' => array( 'foo' => 1, 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertEquals( 1, $sut->getSetting( 'foo' ) );
		$this->assertEquals( 2, $sut->getSetting( 'baz' ) );
		$this->assertEquals( 3, $sut->getSetting( 'bar' ) );
	}

	public function testAccessingSettingsUsingGetSettingAllowsForADefaultValue() {
		$val = array( 'settings' => array( 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertEquals( 'someValue', $sut->getSetting( 'foo', 'someValue' ) );
	}

	public function testDefaultReturnValueForMissingSettingsIsNull() {
		$val = array( 'settings' => array( 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertNull( $sut->getSetting( 'foo' ) );
	}

	public function testOnStaticMethodWillReturnBlockSettingsObject() {
		$val = array( 'settings' => array( 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertInstanceOf( '\tad_HeadwayBlockSettingsWrapper', Settings::on( 'someBlock', $this->className ) );
	}

	public function testOnStaticMethodWillAllowFluentInteraface() {
		$val = array( 'settings' => array( 'baz' => 2, 'bar' => 3 ) );
		tad_StaticMocker::_addMethod( 'get_block', $val );
		$sut = new Settings( 'someBlock', $this->className );
		$this->assertEquals( 2, Settings::on( 'someBlock', $this->className )->baz );
	}
}