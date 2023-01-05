<?php
/*
Plugin Name: WooCommerce Auto Category Descriptions
Plugin URI: idyll.gr
Description: Automatically sets a description for each category of products in WooCommerce.
Version: 1.0
Author: ADMIN IDYLL 
Author URI: IDYLL.GR
License: GPLv2
*/
function enqueue_plugin_styles() {
  wp_enqueue_style( 'plugin-style', plugins_url( '/css/plugin-style.css', __FILE__ ), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_plugin_styles' );

// Register the plugin settings
add_action( 'admin_init', 'register_auto_category_descriptions_settings' );

// Callback function for the "admin_init" action
function register_auto_category_descriptions_settings() {
  register_setting( 'auto_category_descriptions_settings', 'auto_category_descriptions' );
}

// Add a settings page to the WordPress admin dashboard
add_action( 'admin_menu', 'add_auto_category_descriptions_settings_page' );

// Callback function for the "admin_menu" action
function add_auto_category_descriptions_settings_page() {
  add_options_page( 'Auto Category Descriptions', 'Auto Category Descriptions', 'manage_options', 'auto_category_descriptions', 'auto_category_descriptions_settings_page' );
}

// Callback function for the settings page
function auto_category_descriptions_settings_page() {
  ?>
  <div class="wrap">
    <h1>Auto Category Descriptions</h1>
    <form action="options.php" method="post">
      <?php
      settings_fields( 'auto_category_descriptions_settings' );
      do_settings_sections( 'auto_category_descriptions_settings' );
      ?>
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row"><label for="auto_category_descriptions">Categories and Descriptions</label></th>
            <td>
              <textarea name="auto_category_descriptions" id="auto_category_descriptions" cols="50" rows="10"><?php echo get_option( 'auto_category_descriptions' ); ?></textarea>
              <p class="description">Enter one category and description per line, in the format "Category: Description".</p>
            </td>
          </tr>
        </tbody>
      </table>
      <?php
      submit_button();
      ?>
    </form>
  </div>
  <?php
}

// Hook into the "created_product_cat" action provided by WooCommerce
add_action( 'created_product_cat', 'set_category_description', 10, 2 );

// Callback function for the "created_product_cat" action
function set_category_description( $term_id, $taxonomy_id ) {
  // Get the categories and descriptions from the plugin settings
  $categories_and_descriptions = get_option( 'auto_category_descriptions' );

  // Split the categories and descriptions into an array
  $categories_and_descriptions = explode( "\n", $categories_and_descriptions );

  // Loop through the categories and descriptions
  foreach ( $categories_and_descriptions as $category_and_description ) {
    // Split the category and description into separate variables
    list( $category, $description ) = explode( ':', $category_and_description );

    // Trim any whitespace from the category and description
    $category = trim( $category );
    $description = trim( $description );

    // Check if the current category matches the one being created
    if ( $taxonomy_id == $category ) {
      // Update the term with the new description
      $result = wp_update_term( $term_id, 'product_cat', array( 'description' => $description ) );

      // Check for errors
      if ( is_wp_error( $result ) ) {
        // Display an error message
        echo '<div class="error"><p>Error setting description for ' . $category . ': ' . $result->get_error_message() . '</p></div>';
      }
    }
  }
}

