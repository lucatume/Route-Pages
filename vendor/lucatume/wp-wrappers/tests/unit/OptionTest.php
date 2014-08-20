<?php


class OptionTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->f   = $this->getMock( 'tad_FunctionsAdapterInterface', array(
				'__call',
				'get_option',
				'get_site_option',
				'update_option',
				'update_site_option'
			) );
		$this->sut = new tad_Option( $this->f );
	}

	public function testOptionSlugWillBeSetToEmptyStringByDefault() {
		$this->assertEquals( '', $this->sut->optionSlug() );
	}

	public function testOptionSlugCanBeUsedToSetAndGetValue() {
		$this->sut->optionSlug( 'some' );
		$this->assertEquals( 'some', $this->sut->optionSlug() );
		$this->sut->optionSlug( 'else' );
		$this->assertEquals( 'else', $this->sut->optionSlug() );
		$this->sut->optionSlug( '' );
		$this->assertEquals( '', $this->sut->optionSlug() );
	}

	public function testOptionSlugWillThrowForNonStringOptionId() {
		$this->setExpectedException( 'BadMethodCallException', 1 );
		$this->sut->optionSlug( 23 );
	}

	public function testOptionSlugIsChainable() {
		$this->assertEquals( $this->sut, $this->sut->optionSlug( 'some' ) );
	}

	public function testIsSiteOptionWillBeSetToFalseByDefault() {
		$this->assertEquals( false, $this->sut->isSiteOption() );
	}

	public function testIsSiteOptionCanBeUsedToSetAndGetValue() {
		$this->assertEquals( false, $this->sut->isSiteOption() );
		$this->sut->isSiteOption( true );
		$this->assertEquals( true, $this->sut->isSiteOption() );
		$this->sut->isSiteOption( false );
		$this->assertEquals( false, $this->sut->isSiteOption() );
		$this->sut->isSiteOption( true );
		$this->assertEquals( true, $this->sut->isSiteOption() );
	}

	public function testIsSiteOptionWillThrowForNonBooleanValues() {
		$this->setExpectedException( 'BadMethodCallException', 1 );
		$this->sut->isSiteOption( 23 );
	}

	public function testIsSiteOptionInChainable() {
		$this->assertEquals( $this->sut, $this->sut->isSiteOption( false ) );
	}

	public function testUnderscorePropertiesWillBeSetToFalseByDefault() {
		$this->assertEquals( false, $this->sut->underscoreProperties() );
	}

	public function testUnderscorePropertiesCanBeUsedToSetAndGetValue() {
		$this->assertEquals( false, $this->sut->underscoreProperties() );
		$this->sut->underscoreProperties( true );
		$this->assertEquals( true, $this->sut->underscoreProperties() );
		$this->sut->underscoreProperties( false );
		$this->assertEquals( false, $this->sut->underscoreProperties() );
		$this->sut->underscoreProperties( true );
		$this->assertEquals( true, $this->sut->underscoreProperties() );
	}

	public function testUnderscorePropertiesWillThrowForNonBooleanValues() {
		$this->setExpectedException( 'BadMethodCallException', 1 );
		$this->sut->underscoreProperties( 23 );
	}

	public function testUnderscorePropertiesInChainable() {
		$this->assertEquals( $this->sut, $this->sut->underscoreProperties( false ) );
	}

	public function testLoadWillTryGetOptionWithOptionSlug() {
		$this->f->expects( $this->once() )->method( 'get_option' )->with( 'some' );
		$this->sut->optionSlug( 'some' )->load();
	}

	public function testLoadWillTryGetSiteOptionWithOptionSlug() {
		$this->f->expects( $this->once() )->method( 'get_site_option' )->with( 'some' );
		$this->sut->optionSlug( 'some' )->isSiteOption( true )->load();
	}

	public function testLoadWillLoadNonSerializedOptions() {
		$data = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->will( $this->returnValue( $data ) );
		$this->sut->optionSlug( 'some' )->load();
		$this->assertEquals( 23, $this->sut->firstValue );
		$this->assertEquals( false, $this->sut->secondValue );
		$this->assertEquals( 'lorem ipsum', $this->sut->thirdValue );
	}

	public function testLoadWillExposeValuesUsingUnderscoresForNonSerializedOptions() {
		$data = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->will( $this->returnValue( $data ) );
		$this->sut->optionSlug( 'some' )->underscoreProperties( true )->load();
		$this->assertEquals( 23, $this->sut->first_value );
		$this->assertEquals( false, $this->sut->second_value );
		$this->assertEquals( 'lorem ipsum', $this->sut->third_value );
	}

	public function testPropertiesCanBeSetUsingCamelCaseFormat() {
		$data = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->will( $this->returnValue( $data ) );
		$this->sut->optionSlug( 'some' )->load();
		$this->sut->firstValue  = 34;
		$this->sut->secondValue = true;
		$this->sut->thirdValue  = 'dolor sit';
		$this->assertEquals( 34, $this->sut->firstValue );
		$this->assertEquals( true, $this->sut->secondValue );
		$this->assertEquals( 'dolor sit', $this->sut->thirdValue );
		$newData = array( 'first_value' => 34, 'second_value' => true, 'third_value' => 'dolor sit' );
		$this->f->expects( $this->once() )->method( 'update_option' )->with( $this->equalTo( 'some' ), $this->equalTo( $newData ) );
		$this->sut->__destruct();
	}

	public function testPropertiesCanBeSetUsingUnderscoreFormat() {
		$data = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->will( $this->returnValue( $data ) );
		$this->sut->optionSlug( 'some' )->underscoreProperties( true )->load();
		$this->sut->first_value  = 34;
		$this->sut->second_value = true;
		$this->sut->third_value  = 'dolor sit';
		$this->assertEquals( 34, $this->sut->first_value );
		$this->assertEquals( true, $this->sut->second_value );
		$this->assertEquals( 'dolor sit', $this->sut->third_value );
		$newData = array( 'first_value' => 34, 'second_value' => true, 'third_value' => 'dolor sit' );
		$this->f->expects( $this->once() )->method( 'update_option' )->with( $this->equalTo( 'some' ), $this->equalTo( $newData ) );
		$this->sut->__destruct();
	}

	public function testAllowsConstructionAndSavingOfNonPreExistingOption() {
		$newData = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$this->f->expects( $this->once() )->method( 'get_option' )->will( $this->returnValue( false ) );
		$this->sut->optionSlug( 'some' )->load();
		$this->assertEquals( 'some', $this->sut->optionSlug() );
		$this->sut->firstValue  = 34;
		$this->sut->secondValue = true;
		$this->sut->thirdValue  = 'dolor sit';
		$this->assertEquals( 34, $this->sut->firstValue );
		$this->assertEquals( true, $this->sut->secondValue );
		$this->assertEquals( 'dolor sit', $this->sut->thirdValue );
		$this->f->expects( $this->once() )->method( 'update_option' )->with( $this->equalTo( 'some' ), $this->equalTo( $newData ) );
		$this->sut->__destruct();
	}

	public function testAllowsConstructionAndSavingOfNonPreExistingSiteOption() {
		$newData = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$this->f->expects( $this->once() )->method( 'get_site_option' )->will( $this->returnValue( false ) );
		$this->sut->optionSlug( 'some' )->isSiteOption( true )->load();
		$this->assertEquals( 'some', $this->sut->optionSlug() );
		$this->sut->firstValue  = 34;
		$this->sut->secondValue = true;
		$this->sut->thirdValue  = 'dolor sit';
		$this->assertEquals( 34, $this->sut->firstValue );
		$this->assertEquals( true, $this->sut->secondValue );
		$this->assertEquals( 'dolor sit', $this->sut->thirdValue );
		$this->f->expects( $this->once() )->method( 'update_site_option' )->with( $this->equalTo( 'some' ), $this->equalTo( $newData ) );
		$this->sut->__destruct();
	}

	public function testOnMethodAllowsGettingAndUpdatingOption() {
		$data    = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$newData = array( 'firstValue' => 23, 'secondValue' => false, 'thirdValue' => 'lorem ipsum');
		$this->f->expects( $this->once() )->method( 'get_option' )->with( 'some' )->will( $this->returnValue( $data ) );
		$sut = tad_Option::on( 'some', $this->f );
		$this->assertEquals( 'some', $sut->optionSlug() );
		$this->assertFalse( $sut->underscoreProperties() );
		$this->assertFalse( $sut->isSiteOption() );
		$this->assertEquals( 34, $sut->firstValue );
		$this->assertEquals( true, $sut->secondValue );
		$this->assertEquals( 'dolor sit', $sut->thirdValue );
		$sut->firstValue  = 23;
		$sut->secondValue = false;
		$sut->thirdValue  = 'lorem ipsum';
		$this->f->expects( $this->once() )->method( 'update_option' )->with( 'some', $newData );
	}

	public function testOnMethodAllowsLoadingSerializedOption() {
		$data    = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit');
		$newData = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->with( 'some' )->will( $this->returnValue( $data ) );
		$sut = tad_Option::on( 'some', $this->f );
		$this->assertEquals( 'some', $sut->optionSlug() );
		$this->assertFalse( $sut->underscoreProperties() );
		$this->assertFalse( $sut->isSiteOption() );
		$this->assertEquals( 34, $sut->firstValue );
		$this->assertEquals( true, $sut->secondValue );
		$this->assertEquals( 'dolor sit', $sut->thirdValue );
		$sut->firstValue  = 23;
		$sut->secondValue = false;
		$sut->thirdValue  = 'lorem ipsum';
		$this->f->expects( $this->once() )->method( 'update_option' )->with( 'some' )->will( $this->returnValue( $data ) );
	}

	public function testOnMethodAllowsLoadingOption() {
		$data    = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$newData = array( 'first_value' => 23, 'second_value' => false, 'third_value' => 'lorem ipsum' );
		$this->f->expects( $this->once() )->method( 'get_option' )->with( 'some' )->will( $this->returnValue( $data ) );
		$sut = tad_Option::on( 'some', $this->f );
		$this->assertEquals( 'some', $sut->optionSlug() );
		$this->assertFalse( $sut->underscoreProperties() );
		$this->assertFalse( $sut->isSiteOption() );
		$this->assertEquals( 34, $sut->firstValue );
		$this->assertEquals( true, $sut->secondValue );
		$this->assertEquals( 'dolor sit', $sut->thirdValue );
		$sut->firstValue  = 23;
		$sut->secondValue = false;
		$sut->thirdValue  = 'lorem ipsum';
		$this->f->expects( $this->once() )->method( 'update_option' )->with( 'some' )->will( $this->returnValue( $data ) );
	}

	/**
	 * @test
	 * it should allow removing key and value from the option
	 */
	public function it_should_allow_removing_key_and_value_from_the_option() {
		$in  = array( 'firstValue' => 34, 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$out = array( 'secondValue' => true, 'thirdValue' => 'dolor sit' );
		$this->f->expects( $this->once() )->method( 'get_option' )->with( 'some' )->will( $this->returnValue( $in ) );
		$sut = tad_Option::on( 'some', $this->f );
		// remove the first value
		$sut->removeValue( 'firstValue' );
		$this->assertEquals( $out, $sut->getValues() );
	}
}
