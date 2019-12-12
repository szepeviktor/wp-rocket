<?php
namespace WP_Rocket\Tests\Integration\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Optimization\CSS\Critical_CSS_Generation;
use WP_Rocket\Tests\Integration\TestCase;

class TestGetCurrentPageCriticalCSS extends TestCase {
	/**
	 * Should return a "non mobile" file path when `$is_mobile` argument is false.
	 */
	public function testShouldReturnNonMobilePathWhenArgFalse() {
		$expected = WP_ROCKET_CRITICAL_CSS_PATH . get_current_blog_id() . '/front_page.css';
		$exists   = file_exists( $expected );

		if ( ! $exists ) {
			\rocket_mkdir_p( dirname( $expected ) );
			\file_put_contents( $expected, 'test' );
		}

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();
		$critical_css  = new Critical_CSS( $process, $mobile_detect );

		$this->assertSame( $expected, $critical_css->get_current_page_critical_css( false ) );

		if ( ! $exists ) {
			wp_delete_file( $expected );
		}
	}

	/**
	 * Should return a "mobile" file path when `$is_mobile` argument is true.
	 */
	public function testShouldReturnMobilePathWhenArgTrue() {
		$expected = WP_ROCKET_CRITICAL_CSS_PATH . get_current_blog_id() . '/front_page-cpcss-mobile.css';
		$exists   = file_exists( $expected );

		if ( ! $exists ) {
			\rocket_mkdir_p( dirname( $expected ) );
			\file_put_contents( $expected, 'test' );
		}

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();
		$critical_css  = new Critical_CSS( $process, $mobile_detect );

		$this->assertSame( $expected, $critical_css->get_current_page_critical_css( true ) );

		if ( ! $exists ) {
			wp_delete_file( $expected );
		}
	}
}
