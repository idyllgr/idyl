<?php
/*
Plugin Name: WooCommerce under_con
Plugin URI: https://bzn.gr/under_com
Description: A simple example plugin for WooCommerce
Version: 1.0
Author: BZN.GR
Author URI: https://bzn.gr
License: GPLv2
*/
// Add custom page to WooCommerce settings
add_filter( 'woocommerce_settings_tabs_array', 'wc_maintenance_mode_settings_tab', 50 );
function wc_maintenance_mode_settings_tab( $settings_tabs ) {
    $settings_tabs['maintenance_mode'] = __( 'Maintenance Mode', 'wc_maintenance_mode' );
    return $settings_tabs;
}

// Add content to custom page
add_action( 'woocommerce_settings_tabs_maintenance_mode', 'wc_maintenance_mode_settings' );
function wc_maintenance_mode_settings() {
    woocommerce_admin_fields( wc_maintenance_mode_options() );
}

// Save settings from custom page
add_action( 'woocommerce_update_options_maintenance_mode', 'wc_maintenance_mode_save_settings' );
function wc_maintenance_mode_save_settings() {
    woocommerce_update_options( wc_maintenance_mode_options() );
}

// Define settings for custom page
function wc_maintenance_mode_options() {
    $settings = array(
        'section_title' => array(
            'name'     => __( 'Maintenance Mode', 'wc_maintenance_mode' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'wc_maintenance_mode_section_title'
        ),
        'enabled' => array(
            'name' => __( 'Enable Maintenance Mode', 'wc_maintenance_mode' ),
            'type' => 'checkbox',
            'desc' => __( 'Check this box to enable maintenance mode for the WooCommerce store.', 'wc_maintenance_mode' ),
            'id'   => 'wc_maintenance_mode_enabled'
        ),
        'section_end' => array(
            'type' => 'sectionend',
            'id' => 'wc_maintenance_mode_section_end'
        )
    );
    return $settings;
}

// Check if maintenance mode is enabled
function wc_maintenance_mode_is_enabled() {
    $options = get_option( 'woocommerce_maintenance_mode_settings' );
    if ( isset( $options['enabled'] ) && $options['enabled'] === 'yes' ) {
        return true;
    } else {
        return false;
    }
}

// Maintenance mode function
function wc_maintenance_mode() {
    if ( ! current_user_can( 'manage_options' ) && ! is_ajax() && wc_maintenance_mode_is_enabled() ) {
        wp_die( 'Maintenance mode is currently enabled. Please check back later.' );
}
}
add_action( 'wp_loaded', 'wc_maintenance_mode' );
