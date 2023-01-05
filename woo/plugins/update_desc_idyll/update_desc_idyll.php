<?php

// Hook into the "created_product_cat" action provided by WooCommerce
add_action( 'created_product_cat', 'set_category_description', 10, 2 );

// Callback function for the "created_product_cat" action
function set_category_description( $term_id, $taxonomy_id ) {
  // Check if the category is "Αφίσες > Για παιδιά"
  if ( $taxonomy_id == 'Αφίσες' && $term_id == 'Για παιδιά' ) {
    // Set the description of the category to "Blabla"
    $description = 'Blabla';
    wp_update_term( $term_id, 'product_cat', array( 'description' => $description ) );
  }
}

