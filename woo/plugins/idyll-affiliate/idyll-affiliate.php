<?php
/*
Plugin Name: Affiliate WooCommerce Plugin
Plugin URI: https://idyll.gr
Description: This plugin adds a tab to the My Account page in WooCommerce and displays a unique discount code to the user when they click on the tab. It also tracks the usage of the discount codes and displays the data in the plugin's admin panel.
Author: IDYL LTD
Author URI: https://idyll.gr
Version: 1.1
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wc-affiliate-plugin
*/
function wc_affiliate_plugin_styles() {
    ?>
    <style>
.wc-affiliate-plugin {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #f5f5f5;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 2em;
}

.wc-affiliate-plugin-discount-code {
    font-size: 2em;
    font-weight: bold;
    color: #3498db;
    margin: 1em 0;
}

.wc-affiliate-plugin-table {
    width: 100%;
    border-collapse: collapse;
}

.wc-affiliate-plugin-table th,
.wc-affiliate-plugin-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.wc-affiliate-plugin-table th {
    background-color: #3498db;
    color: #fff;
}

.wc-affiliate-plugin-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.wc-affiliate-plugin-table tr:hover {
    background-color: #ddd;
}

@media (max-width: 600px) {
    .wc-affiliate-plugin {
        padding: 1em;
    }

    .wc-affiliate-plugin-table {
        display: block;
        width: 100%;
        overflow-x: auto;
    }

    .wc-affiliate-plugin-table th,
    .wc-affiliate-plugin-table td {
        display: block;
        width: auto;
   
}

.wc-affiliate-plugin-table td:before {
    content: attr(data-label);
    float: left;
    font-weight: bold;
    text-transform: uppercase;
}

.wc-affiliate-plugin-table td:last-of-type {
    text-align: right;
}

    </style>
    <?php
}
add_action('wp_head', 'wc_affiliate_plugin_styles');
// Register custom post type for discount codes
function wc_affiliate_plugin_register_post_type() {
    register_post_type('wc_affiliate_discount', array(
        'labels' => array(
            'name' => __('Affiliate Discounts', 'wc-affiliate-plugin'),
            'singular_name' => __('Affiliate Discount', 'wc-affiliate-plugin')
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'woocommerce',
        'supports' => array('title', 'custom-fields')
    ));
}
add_action('init', 'wc_affiliate_plugin_register_post_type');

// Add tab to My Account page
function wc_affiliate_plugin_add_tab($items) {
    $items['affiliate'] = __('Affiliate', 'wc-affiliate-plugin');
    return $items;
}
add_filter('woocommerce_account_menu_items', 'wc_affiliate_plugin_add_tab');

// Add endpoint for Affiliate tab
function wc_affiliate_plugin_add_endpoint() {
    add_rewrite_endpoint('affiliate', EP_ROOT | EP_PAGES);
}
add_action('init', 'wc_affiliate_plugin_add_endpoint');

// Display discount code and track usage data
function wc_affiliate_plugin_content() {
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $options = get_option('wc_affiliate_plugin_settings');
        $discount_percentage = isset($options['discount_percentage']) ? $options['discount_percentage'] : 10;
        $discount_prefix = isset($options['discount_prefix']) ? $options['discount_prefix'] : '';
        $discount_code = $discount_prefix . $current_user->user_login . '-' . $discount_percentage;

        $discount_post = array(
            'post_type' => 'wc_affiliate_discount',
            'post_title' => $discount_code,
            'post_status'` => 'publish',
            'meta_input' => array(
                'user_id' => $current_user->ID,
                'usage_count' => 0
            )
        );
        $discount_id = wp_insert_post($discount_post);
        update_post_meta($discount_id, 'usage_count', 0);

        echo 'Your discount code: ' . $discount_code;
    } else {
        echo 'You must be logged in to view your discount code.';
    }
}
add_action('woocommerce_account_affiliate_endpoint', 'wc_affiliate_plugin_content');

// Register plugin settings
function wc_affiliate_plugin_settings_init() {
    register_setting('wc_affiliate_plugin', 'wc_affiliate_plugin_settings');

    add_settings_section(
        'wc_affiliate_plugin_section',
        __('Affiliate Plugin Settings', 'wc-affiliate-plugin'),
        'wc_affiliate_plugin_section_callback',
        'wc_affiliate_plugin'
    );

    add_settings_field(
        'discount_percentage',
        __('Discount Percentage', 'wc-affiliate-plugin'),
        'wc_affiliate_plugin_field_discount_percentage_render',
        'wc_affiliate_plugin',
        'wc_affiliate_plugin_section'
    );

    add_settings_field(
        'discount_prefix',
        __('Discount Code Prefix', 'wc-affiliate-plugin'),
        'wc_affiliate_plugin_field_discount_prefix_render',
        'wc_affiliate_plugin',
        'wc_affiliate_plugin_section'
    );
}
add_action('admin_init', 'wc_affiliate_plugin_settings_init');

// Render settings section
function wc_affiliate_plugin_section_callback() {
    echo __('Customize the behavior of the Affiliate Plugin', 'wc-affiliate-plugin');
}

// Render discount percentage field
function wc_affiliate_plugin_field_discount_percentage_render() {
    $options = get_option('wc_affiliate_plugin_settings');
    $value = isset($options['discount_percentage']) ? $options['discount_percentage'] : 10;
    ?>
    <input type="number" name="wc_affiliate_plugin_settings[discount_percentage]" value="<?php echo $value; ?>">
    <?php
}

// Render discount prefix field
function wc_affiliate_plugin_field_discount_prefix_render() {
    $options = get_option('wc_affiliate_plugin_settings');
    $value = isset($options['discount_prefix']) ? $options['discount_prefix'] : '';
    ?>
    <input type="text" name="wc_affiliate_plugin_settings[discount_prefix]" value="<?php echo $php $value; ?>">
    <?php
}

// Add plugin settings page
function wc_affiliate_plugin_settings_page() {
    ?>
    <form action="options.php" method="post">
        <h2>Affiliate Plugin</h2>
        <?php
        settings_fields('wc_affiliate_plugin');
        do_settings_sections('wc_affiliate_plugin');
        submit_button();
        ?>
    </form>
    <?php
}

// Add plugin settings link to plugin action links
function wc_affiliate_plugin_settings_link($links) {
    $settings_link = '<a href="admin.php?page=wc-settings&tab=affiliate">Settings</a>';
    array_push($links, $settings_link);
    return $links;
}
$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wc_affiliate_plugin_settings_link');

// Add plugin settings page to WooCommerce settings tab
function wc_affiliate_plugin_add_settings_page($pages) {
    $pages['affiliate'] = __('Affiliate', 'wc-affiliate-plugin');
    return $pages;
}
add_filter('woocommerce_settings_tabs_array', 'wc_affiliate_plugin_add_settings_page');

// Display plugin settings page content
function wc_affiliate_plugin_settings_page_content() {
    woocommerce_admin_fields($this->get_settings());
}
add_action('woocommerce_settings_tabs_affiliate', 'wc_affiliate_plugin_settings_page_content');

// Save plugin settings page
function wc_affiliate_plugin_save_settings() {
    woocommerce_update_options($this->get_settings());
}
add_action('woocommerce_update_options_affiliate', 'wc_affiliate_plugin_save_settings');

// Display usage data in admin panel
function wc_affiliate_plugin_usage_data() {
    global $wpdb;

    $discounts = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_type = 'wc_affiliate_discount'");

    echo '<table class="wp-list-table widefat fixed striped posts">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Discount Code</th>';
    echo '<th>Generator</th>';
    echo '<th>Usage Count</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($discounts as $discount) {
        $discount_code = $discount->post_title;
        $user_id = get_post_meta($discount->ID, 'user_id', true);
        $user = get_user_by('id', $user_id);
        $usage_count = get_post_meta($discount->ID, 'usage_count', true);

        echo '<tr>';
        echo '<td>' . $discount_code . '</td>';
        echo '<td>' . $user->display_name . '</td>';
        echo '<td>' . $usage_count . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

// Add usage data page to plugin menu
function wc_affiliate_plugin_add_usage_data_page() {
    add_submenu_page(
        'woocommerce',
        'Affiliate Usage Data',
        'Usage Data',
        'manage_options',
        'wc-affiliate-usage-data',
        'wc_affiliate_plugin_usage_data'
    );
}
add_action('admin_menu', 'wc_affiliate_plugin_add_usage_data_page');




