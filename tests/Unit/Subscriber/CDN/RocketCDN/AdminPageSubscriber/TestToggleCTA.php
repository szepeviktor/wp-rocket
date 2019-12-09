<?php
namespace WP_Rocket\Tests\Unit\Subscriber\CDN\RocketCDN;

use WP_Rocket\Tests\Unit\TestCase;
use WP_Rocket\Subscriber\CDN\RocketCDN\AdminPageSubscriber;
use Brain\Monkey\Functions;

/**
 * @coversDefaultClass \WP_Rocket\Subscriber\CDN\RocketCDN\AdminPageSubscriber
 */
class TestToggleCTA extends TestCase {
    /**
     * @covers ::toggle_cta
     * @group RocketCDN
     */
    public function testShouldReturnNullWhenPOSTNotSet() {
        Functions\when('check_ajax_referer')->justReturn(true);

        $page = new AdminPageSubscriber( 'views/settings/rocketcdn');
        $this->assertNull( $page->toggle_cta() );
    }

    /**
     * @covers ::toggle_cta
     * @group RocketCDN
     */
    public function testShouldReturnNullWhenInvalidPOSTAction() {
        Functions\when('check_ajax_referer')->justReturn(true);

        $_POST['status'] = 'big';
        $_POST['action'] = 'invalid';

        $page = new AdminPageSubscriber( 'views/settings/rocketcdn');
        $this->assertNull( $page->toggle_cta() );
    }

    /**
     * @covers ::toggle_cta
     * @group RocketCDN
     */
    public function testShouldReturnDeleteUserMetaWhenStatusIsBig() {
        Functions\when('check_ajax_referer')->justReturn(true);
        Functions\when('get_current_user_id')->justReturn(1);
        Functions\expect('delete_user_meta')->once();

        $_POST['status'] = 'big';
        $_POST['action'] = 'toggle_rocketcdn_cta';

        $page = new AdminPageSubscriber( 'views/settings/rocketcdn');
        $page->toggle_cta();
    }

    /**
     * @covers ::toggle_cta
     * @group RocketCDN
     */
    public function testShouldReturnUpdateUserMetaWhenStatusIsSmall() {
        Functions\when('check_ajax_referer')->justReturn(true);
        Functions\when('get_current_user_id')->justReturn(1);
        Functions\expect('update_user_meta')->once();

        $_POST['status'] = 'small';
        $_POST['action'] = 'toggle_rocketcdn_cta';

        $page = new AdminPageSubscriber( 'views/settings/rocketcdn');
        $page->toggle_cta();
    }
}