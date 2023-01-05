<?php
/*
Plugin Name: WooCommerce Auto Category Descriptions
Plugin URI: http://idyll.gr
Description: Automatically sets a description for each category of products in WooCommerce.
Version: 1.0
Author: IDYL LTD
Author URI: http://idyll.gr
License: GPLv2
*/
function wc_auto_category_descriptions_enqueue_styles() {
  wp_register_style( 'wc-auto-category-descriptions-style', plugins_url( 'css/plugin-style.css', __FILE__ ), array(), '1.0' );
  wp_enqueue_style( 'wc-auto-category-descriptions-style' );
}
add_action( 'admin_enqueue_scripts', 'wc_auto_category_descriptions_enqueue_styles' );

// Register the plugin settings
add_action( 'admin_init', 'register_auto_category_descriptions_settings' );

// Callback function for the "admin_init" action
function register_auto_category_descriptions_settings() {
  register_setting( 'auto_category_descriptions_settings', 'auto_category_description_category' );
  register_setting( 'auto_category_descriptions_settings', 'auto_category_description' );
  register_setting( 'auto_category_descriptions_settings', 'auto_category_descriptions_recent_changes' );
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
            <th scope="row"><label for="auto_category_description_category">Category</label></th>
            <td>
              <?php
              // Get the categories
              $categories = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false
              ) );

              // Check if there are any categories
              if ( ! empty( $categories ) ) {
                // Display the dropdown
                ?>
                <select name="auto_category_description_category" id="auto_category_description_category">
                  <?php
                  // Loop through the categories
                  foreach ( $categories as $category ) {
                    // Output the option
                    ?>
                    <option value="<?php echo $category->term_id; ?>" <?php selected( get_option( 'auto_category_description_category' ), $category->term_id ); ?>><?php echo $category->name; ?></option>
                    <?php
                  }
                  ?>
                </select>
                <?php
              } else {
                // Display an error message if no categories were found
                ?>
                <p class="error">No categories found.</p>
                <?php
              }
              ?>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="auto_category_description">Description</label></th>
            <td>
              <textarea name="auto_category_description" id="auto_category_description" cols="50" rows="10"><?php echo get_option( 'auto_category_description' ); ?></textarea>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="auto_category_descriptions_recent_changes">Recent Changes</label></th>
            <td>
              <textarea name="auto_category_descriptions_recent_changes" id="auto_category_descriptions_recent_changes" cols="50" rows="10" readonly><?php echo get_option( 'auto_category_descriptions_recent_changes' ); ?></textarea>
              <p class="description">The category and description that were set by the plugin for the most recent changes.</p>
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

// Hook into the "created_product_cat" and "edited_product_cat" actions provided by WooCommerce
add_action( 'created_product_cat', 'set_category_description', 10, 2 );
add_action( 'edited_product_cat', 'set_category_description', 10, 2 );

// Callback function for the "created_product_cat" and "edited_product_cat" actions
function set_category_description( $term_id, $taxonomy_id ) {
  // Get the current category and description
  $current_category = get_term_by( 'id', $term_id, 'product_cat' );
  $current_description = $current_category->description;

  // Get the category and description from the plugin settings
  $category = get_option( 'auto_category_description_category' );
  $description = get_option( 'auto_category_description' );

  // Check if the current category matches the one being created/edited
  if ( $taxonomy_id == $category ) {
    // Check if the description has already been set by the plugin
    if ( $current_description == $description ) {
      // Skip the update and leave the description as it is
      return;
    }

    // Update the term with the new description
    $result = wp_update_term( $term_id, 'product_cat', array( 'description' => $description ) );

    // Check for errors
    if ( is_wp_error( $result ) ) {
      // Display an error message
      echo '<div class="error"><p>Error setting description for ' . $category . ': ' . $result->get_error_message() . '</p></div>';
    } else {
      // Save the recent changes to the plugin settings
      $recent_changes = get_option( 'auto_category_descriptions_recent_changes' );
      $recent_changes .= $category . ': ' . $description . "\n";
      update_option( 'auto_category_descriptions_recent_changes', $recent_changes );
    }
  } else {
    // Display a message if the category was not found
    echo '<div class="error"><p>Category ' . $category . ' not found.</p></div>';
  }
}

