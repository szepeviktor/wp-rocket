<?php
namespace WP_Rocket\Tests\Unit\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Tests\Unit\TestCase;
use Brain\Monkey\Functions;

class TestShouldMobileCriticalCSS extends TestCase {
	/**
	 * Should return true when separate mobile cache and async CSS are enabled.
	 */
	public function testShouldReturnTrueWhenMobileCacheAndAsyncCssEnabled() {
		$mocks = $this->getConstructorMocks();

		// get_rocket_option().
		Functions\when( 'get_rocket_option' )->alias(
			function( $option_name, $default = false ) {
				$values = [
					'cache_mobile'                => true,
					'do_caching_mobile_files'     => true,
					'async_css'                   => true,
					'mobile_critical_css_enabled' => true,
				];
				return isset( $values[ $option_name ] ) ? $values[ $option_name ] : $default;
			}
		);

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertTrue( $critical_css->should_mobile_critical_css() );
	}

	/**
	 * Should return false when mobile cpcss is disabled.
	 */
	public function testShouldReturnFalseWhenMobileCpcssDisabled() {
		$mocks = $this->getConstructorMocks();

		// get_rocket_option().
		Functions\when( 'get_rocket_option' )->alias(
			function( $option_name, $default = false ) {
				$values = [
					'cache_mobile'                => true,
					'do_caching_mobile_files'     => true,
					'async_css'                   => true,
					'mobile_critical_css_enabled' => false,
				];
				return isset( $values[ $option_name ] ) ? $values[ $option_name ] : $default;
			}
		);

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );
	}

	/**
	 * Should return false when async CSS is disabled.
	 */
	public function testShouldReturnFalseWhenAsyncCssDisabled() {
		$mocks = $this->getConstructorMocks();

		// get_rocket_option().
		Functions\when( 'get_rocket_option' )->alias(
			function( $option_name, $default = false ) {
				$values = [
					'cache_mobile'                => true,
					'do_caching_mobile_files'     => true,
					'async_css'                   => false,
					'mobile_critical_css_enabled' => true,
				];
				return isset( $values[ $option_name ] ) ? $values[ $option_name ] : $default;
			}
		);

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );
	}

	/**
	 * Should return false when separate mobile cache is disabled.
	 */
	public function testShouldReturnFalseWhenSeparateMobileCacheDisabled() {
		$mocks = $this->getConstructorMocks();

		// get_rocket_option().
		Functions\when( 'get_rocket_option' )->alias(
			function( $option_name, $default = false ) {
				$values = [
					'cache_mobile'                => true,
					'do_caching_mobile_files'     => false,
					'async_css'                   => true,
					'mobile_critical_css_enabled' => true,
				];
				return isset( $values[ $option_name ] ) ? $values[ $option_name ] : $default;
			}
		);

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );
	}

	/**
	 * Should return false when mobile cache is disabled.
	 */
	public function testShouldReturnFalseWhenMobileCacheDisabled() {
		$mocks = $this->getConstructorMocks();

		// get_rocket_option().
		Functions\when( 'get_rocket_option' )->alias(
			function( $option_name, $default = false ) {
				$values = [
					'cache_mobile'                => false,
					'do_caching_mobile_files'     => true,
					'async_css'                   => true,
					'mobile_critical_css_enabled' => true,
				];
				return isset( $values[ $option_name ] ) ? $values[ $option_name ] : $default;
			}
		);

		$critical_css = new Critical_CSS( $mocks['process'], $mocks['mobile_detect'] );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );
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
