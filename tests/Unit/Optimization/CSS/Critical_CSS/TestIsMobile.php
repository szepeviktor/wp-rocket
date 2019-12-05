<?php
namespace WP_Rocket\Tests\Unit\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Tests\Unit\TestCase;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;

class TestIsMobile extends TestCase {
	/**
	 * Should return true for mobile devices.
	 */
	public function testShouldReturnTrueForMobileDevices() {
		$mocks = $this->getConstructorMocks();

		/**
		 * Mobile should be served to mobile devices.
		 */
		// Case 1.
		Filters\expectApplied( 'rocket_cache_mobile_files_tablet' )
			->andReturn( 'mobile' ); // Simulate a filter.

		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( true );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( true );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertTrue( $critical_css->is_mobile() );

		// Case 2.
		Filters\expectApplied( 'rocket_cache_mobile_files_tablet' )
			->andReturn( 'mobile' ); // Simulate a filter.

		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( true );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( false );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertTrue( $critical_css->is_mobile() );

		// Case 3.
		$mocks['process'] = $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' );
		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( false );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( true );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertTrue( $critical_css->is_mobile() );

		/**
		 * Desktop should be served to tablets.
		 */
		// Case 4.
		Filters\expectApplied( 'rocket_cache_mobile_files_tablet' )
			->andReturn( 'desktop' ); // Simulate no filters.

		$mocks['process'] = $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' );
		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( true );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( false );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertTrue( $critical_css->is_mobile() );
	}

	/**
	 * Should return false for desktop devices.
	 */
	public function testShouldReturnFalseForDesktopDevices() {
		$mocks = $this->getConstructorMocks();

		/**
		 * Desktop should be served to desktop devices.
		 */
		// Case 1.
		Filters\expectApplied( 'rocket_cache_mobile_files_tablet' )
			->andReturn( 'mobile' ); // Simulate a filter.

		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( false );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( false );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->is_mobile() );

		/**
		 * Desktop should be served to tablets.
		 */
		// Case 2.
		Filters\expectApplied( 'rocket_cache_mobile_files_tablet' )
			->andReturn( 'desktop' ); // Simulate no filters.

		$mocks['process'] = $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' );
		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( true );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( true );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->is_mobile() );

		// Case 3.
		$mocks['process'] = $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' );
		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( false );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( false );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->is_mobile() );

		// Case 4.
		$mocks['process'] = $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' );
		$mocks['mobile_detect']
			->method( 'isMobile' )
			->willReturn( false );
		$mocks['mobile_detect']
			->method( 'isTablet' )
			->willReturn( true );

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->is_mobile() );
	}

	/**
	 * Mock all the things from the class constructor.
	 *
	 * @return array Arguments to pass to Critical_CSS.
	 */
	private function getConstructorMocks() {
		if ( ! defined( 'WP_ROCKET_CRITICAL_CSS_PATH' ) ) {
			define( 'WP_ROCKET_CRITICAL_CSS_PATH', '/Internal/path/to/root/wp-content/cache/critical-css/' );
		}

		// home_url().
		Functions\when( 'home_url' )->alias( function( $uri ) {
			return 'https://example.com' . $uri;
		} );

		// get_current_blog_id().
		Functions\when( 'get_current_blog_id' )->justReturn( 1 );

		return [
			'process'       => $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' ),
			'mobile_detect' => $this->createMock( 'Rocket_Mobile_Detect' ),
		];
	}
}
