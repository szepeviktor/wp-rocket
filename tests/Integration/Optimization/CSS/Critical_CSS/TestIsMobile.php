<?php
namespace WP_Rocket\Tests\Integration\Optimize\CSS\Critical_CSS;

use WP_Rocket\Optimization\CSS\Critical_CSS;
use WP_Rocket\Optimization\CSS\Critical_CSS_Generation;
use PHPUnit\Framework\TestCase;

class TestIsMobile extends TestCase {
	/**
	 * Should return true when the 'rocket_cache_mobile_files_tablet' is set to 'desktop' and using a mobile (non tablet) device.
	 */
	public function testShouldReturnTrueWhenFilterIsSetToDesktopAndUsingMobile() {
		remove_all_filters( 'rocket_cache_mobile_files_tablet' );

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		$mobile_detect->setUserAgent( 'iPhone' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertTrue( $critical_css->is_mobile() );
	}

	/**
	 * Should return false when the 'rocket_cache_mobile_files_tablet' is set to 'desktop' and using a tablet device.
	 */
	public function testShouldReturnFalseWhenFilterIsSetToDesktopAndUsingTablet() {
		remove_all_filters( 'rocket_cache_mobile_files_tablet' );

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		// Tablet only.
		$mobile_detect->setUserAgent( 'iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		// Concidered as a mobile and as a tablet at the same time.
		$mobile_detect->setUserAgent( 'iPhone iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );
	}

	/**
	 * Should return true when the 'rocket_cache_mobile_files_tablet' is set to 'mobile' and using a mobile or a tablet device.
	 */
	public function testShouldReturnTrueWhenFilterIsSetToMobileAndUsingMobileOrTablet() {
		add_filter(
			'rocket_cache_mobile_files_tablet',
			function () {
				return 'mobile';
			},
			PHP_INT_MAX
		);

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		// Mobile.
		$mobile_detect->setUserAgent( 'iPhone' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertTrue( $critical_css->is_mobile() );

		// Tablet.
		$mobile_detect->setUserAgent( 'iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertTrue( $critical_css->is_mobile() );

		// Concidered as a mobile and as a tablet at the same time.
		$mobile_detect->setUserAgent( 'iPhone iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertTrue( $critical_css->is_mobile() );

		remove_all_filters( 'rocket_cache_mobile_files_tablet' );
	}

	/**
	 * Should return false when not using a mobile nor tablet device.
	 */
	public function testShouldReturnFalseWhenNotUsingMobileNorTablet() {
		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		$mobile_detect->setUserAgent( 'unknownUA' );

		// Filter set to 'desktop'.
		remove_all_filters( 'rocket_cache_mobile_files_tablet' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		// Filter set to 'mobile'.
		add_filter(
			'rocket_cache_mobile_files_tablet',
			function () {
				return 'mobile';
			},
			PHP_INT_MAX
		);

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		remove_all_filters( 'rocket_cache_mobile_files_tablet' );
	}

	/**
	 * Should return false when the 'rocket_cache_mobile_files_tablet' is not set to 'desktop' nor 'mobile'.
	 */
	public function testShouldReturnFalseWhenFilterIsNotSetToDesktopNorMobile() {
		add_filter(
			'rocket_cache_mobile_files_tablet',
			function () {
				return 'flalala';
			},
			PHP_INT_MAX
		);

		$process       = new Critical_CSS_Generation();
		$mobile_detect = new \Rocket_Mobile_Detect();

		// Mobile.
		$mobile_detect->setUserAgent( 'iPhone' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		// Tablet.
		$mobile_detect->setUserAgent( 'iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		// Concidered as a mobile and as a tablet at the same time.
		$mobile_detect->setUserAgent( 'iPhone iPad' );

		$critical_css = new Critical_CSS( $process, $mobile_detect );

		$this->assertFalse( $critical_css->is_mobile() );

		remove_all_filters( 'rocket_cache_mobile_files_tablet' );
	}
}
