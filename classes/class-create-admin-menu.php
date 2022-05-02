<?php
/**
 * This file will create admin menu page.
 */

class WPRK_Create_Admin_Page {

    public function __construct() {
        add_action( 'admin_menu', [ $this, 'create_admin_menu' ] );
        add_action('wp_before_admin_bar_render', [ $this, 'wp_admin_bar_new_item' ]);
    }

    public function create_admin_menu() {
        $capability = 'manage_options';
        $slug = 'store-code-settings';

        add_menu_page(
            __( 'STORE', 'wp-qr-store' ),
            __( 'STORE', 'wp-qr-store' ),
            $capability,
            $slug,
            [ $this, 'menu_page_template' ],
            'dashicons-store'
        );
    }

    function wp_admin_bar_new_item() {
        global $wp_admin_bar;
        $wp_admin_bar->add_menu(array(
            'id' => 'wp-store-catalopiso',
            'title' => __('OPEN STORE'),
            'href' => '/wp-admin/admin.php?page=store-code-settings'
        ));
    }

    public function menu_page_template() {
        echo '<div class="wrap"><div id="wprk-admin-app"></div></div>';
    }

}
new WPRK_Create_Admin_Page();