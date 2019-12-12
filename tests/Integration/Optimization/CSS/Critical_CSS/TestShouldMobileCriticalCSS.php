<?php
namespace WP_Rocket\Tests\Integration\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Optimization\CSS\Critical_CSS_Generation;
use WP_Rocket\Tests\Integration\TestCase;

class TestShouldMobileCriticalCSS extends TestCase {
	/**
	 * Should return true when all the related options are enabled.
	 */
	public function testShouldReturnTrueWhenAllOptionsAreEnabled() {
		update_option(
			'wp_rocket_settings',
			[
				'cache_mobile'                => 1,
				'do_caching_mobile_files'     => 1,
				'async_css'                   => 1,
				'mobile_critical_css_enabled' => 1,
			]
		);

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();
		$critical_css  = new Critical_CSS( $process, $mobile_detect );

		$this->assertTrue( $critical_css->should_mobile_critical_css() );
	}

	/**
	 * Should return false when a the related option is disabled.
	 */
	public function testShouldReturnTrueWhenAnOptionsIsDisabled() {
		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		// 1.
		update_option(
			'wp_rocket_settings',
			[
				'cache_mobile'                => 1,
				'do_caching_mobile_files'     => 1,
				'async_css'                   => 1,
				'mobile_critical_css_enabled' => 0,
			]
		);

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );

		// 2.
		update_option(
			'wp_rocket_settings',
			[
				'cache_mobile'                => 1,
				'do_caching_mobile_files'     => 1,
				'async_css'                   => 0,
				'mobile_critical_css_enabled' => 1,
			]
		);

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );

		// 3.
		update_option(
			'wp_rocket_settings',
			[
				'cache_mobile'                => 1,
				'do_caching_mobile_files'     => 0,
				'async_css'                   => 1,
				'mobile_critical_css_enabled' => 1,
			]
		);

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );

		// 4.
		update_option(
			'wp_rocket_settings',
			[
				'cache_mobile'                => 0,
				'do_caching_mobile_files'     => 1,
				'async_css'                   => 1,
				'mobile_critical_css_enabled' => 1,
			]
		);

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->should_mobile_critical_css() );
	}
}
