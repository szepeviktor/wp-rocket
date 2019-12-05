<?php
namespace WP_Rocket\Tests\Unit\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

class TestGetCurrentPageCriticalCSS extends TestCase {
	/**
	 * Should return a "non mobile" file path when `$is_mobile` argument is false.
	 */
	public function testShouldReturnNonMobilePathWhenArgFalse() {
		$suffix   = '-cpcss-mobile';
		$blog_id  = 1;
		$mocks    = $this->getMocks( $blog_id, $suffix );
		$expected = WP_ROCKET_CRITICAL_CSS_PATH . $blog_id . '/home.css';

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertSame( $expected, $critical_css->get_current_page_critical_css( false ) );
	}

	/**
	 * Should return a "mobile" file path when `$is_mobile` argument is true.
	 */
	public function testShouldReturnMobilePathWhenArgTrue() {
		$suffix   = '-cpcss-mobile';
		$blog_id  = 1;
		$mocks    = $this->getMocks( $blog_id, $suffix );
		$expected = WP_ROCKET_CRITICAL_CSS_PATH . $blog_id . '/home' . $suffix . '.css';

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertSame( $expected, $critical_css->get_current_page_critical_css( true ) );
	}

	/**
	 * Mock all the things.
	 *
	 * @param  int    $log_id Site ID.
	 * @param  string $suffix File name suffix for mobile.
	 * @return array          Arguments to pass to Critical_CSS.
	 */
	private function getMocks( $blog_id, $suffix ) {
		if ( ! defined( 'WP_ROCKET_CRITICAL_CSS_PATH' ) ) {
			define( 'WP_ROCKET_CRITICAL_CSS_PATH', '/Internal/path/to/root/wp-content/cache/critical-css/' );
		}

		// home_url().
		Functions\when( 'home_url' )->alias( function( $uri ) {
			return 'https://example.com' . $uri;
		} );

		// get_current_blog_id().
		Functions\when( 'get_current_blog_id' )->justReturn( $blog_id );

		// is_home().
		Functions\when( 'is_home' )->justReturn( true );

		// get_option( 'show_on_front' ).
		Functions\when( 'get_option' )->justReturn( 'page' );

		// rocket_direct_filesystem().
		Functions\when( 'rocket_direct_filesystem' )->alias( function() {
			$filesystem = $this->getMockBuilder( 'WP_Filesystem_Direct' )
				->setMethods( [ 'is_readable' ] )
				->getMock();
			$filesystem
				->method( 'is_readable' )
				->willReturn( true );

			return $filesystem;
		} );

		$mocks = [
			'process'       => $this->createMock( 'WP_Rocket\Optimization\CSS\Critical_CSS_Generation' ),
			'mobile_detect' => $this->createMock( 'Rocket_Mobile_Detect' ),
		];

		$mocks['process']
			->method( 'get_mobile_file_suffix' )
			->willReturn( $suffix );

		return $mocks;
	}
}
